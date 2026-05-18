<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\HrmLeaveType;
use App\Models\HrmEmployeeLeaveBalance;
use App\Models\HrmEmployeeRequest;
use App\Models\HrmRequestType;
use App\Models\HrmRequestDetail;
use App\Models\HrmRequestApproval;

class LeaveController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function leaveTypes(Request $request) {
		try {
			$leaveTypes = HrmLeaveType::select(
					'id',
					'code',
					'name',
					'yearly_limit',
					'is_paid',
					'carry_forward'
        )
        ->where('is_active', true)
        // ->orderBy('name', 'asc')
        ->get();

			return response()->json([
				'status' => true,
				'message' => 'Leave types fetched successfully.',
				'data' => $leaveTypes
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function leaveBalance(Request $request) {
		try {
			$user = $request->user();
			$currentYear = date('Y');
			$balances = HrmEmployeeLeaveBalance::with([
            'leaveType:id,code,name'
        ])
        ->where('staff_id', $user->staffid)
        ->where('leave_year', $currentYear)
        ->get()
        ->map(function ($item) {
				return [
					'leave_type_id'   => $item->leave_type_id,
					'code'            => $item->leaveType->code ?? null,
					'name'            => $item->leaveType->name ?? null,
					'allocated'       => $item->allocated,
					'used'            => $item->used,
					'remaining'       => $item->remaining,
					'leave_year'      => $item->leave_year,
				];
			});

    	return response()->json([
        'status'  => true,
        'message' => 'Leave balance fetched successfully.',
        'data'    => $balances
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function leaveHistory(Request $request) {
		try {
			$user = $request->user();

    	$leaveRequestType = HrmRequestType::where('code', 'LEAVE')->first();

    	if (!$leaveRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'Leave request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $leaveRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'Leave history fetched successfully.',
        'data'    => $history
    	], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function applyLeave(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'leave_type_id' => 'required|exists:hrm_leave_types,id',
        'from_date'     => 'required|date',
        'to_date'       => 'required|date|after_or_equal:from_date',
        'reason'        => 'required|string|max:500',
				'type'          => 'required|string|in:full_day,first_half,second_half',
				'notify_team'  	=> 'nullable|boolean',
			]);

    	$leaveType = HrmLeaveType::find($request->leave_type_id);

    	$days = Carbon::parse($request->from_date)
    		->diffInDays(Carbon::parse($request->to_date)) + 1;

			if ($request->type !== 'full_day') {
				if ($request->from_date !== $request->to_date) {
					return response()->json([
						'status'  => false,
						'message' => 'Half day leave can only be applied for a single day.',
					], 422);
				}

				$days = 0.5;
			}

    	$currentYear = date('Y');

    	$balance = HrmEmployeeLeaveBalance::where('staff_id', $user->staffid)
        ->where('leave_type_id', $request->leave_type_id)
        ->where('leave_year', $currentYear)
        ->first();

    	if (!$balance) {
        return response()->json([
					'status'  => false,
					'message' => 'Leave balance not found.',
        ], 404);
    	}

    	if ($leaveType->code !== 'LOP' && $balance->remaining < $days) {
        return response()->json([
					'status'  => false,
					'message' => 'Insufficient leave balance.',
        ], 422);
    	}

    	$leaveRequestType = HrmRequestType::where('code', 'LEAVE')->first();

    	if (!$leaveRequestType) {
        return response()->json([
					'status'  => false,
					'message' => 'Leave request type not configured.',
        ], 404);
    	}

    	$overlap = HrmEmployeeRequest::where('staff_id', $user->staffid)
        ->where('request_type_id', $leaveRequestType->id)
        ->whereIn('status', ['pending', 'approved'])
        ->where(function ($query) use ($request) {
            $query->whereBetween('from_date', [$request->from_date, $request->to_date])
                ->orWhereBetween('to_date', [$request->from_date, $request->to_date]);
        })
        ->exists();

    	if ($overlap) {
        return response()->json([
					'status'  => false,
					'message' => 'Leave already applied for selected dates.',
        ], 422);
    	}

    	DB::beginTransaction();

			try {
				$leaveRequest = HrmEmployeeRequest::create([
					'staff_id'     		=> $user->staffid,
					'request_type_id' => $leaveRequestType->id,
					'request_date'    => now()->toDateString(),
					'from_date'       => $request->from_date,
					'to_date'         => $request->to_date,
					'total_days'      => $days,
					'status'          => 'pending',
					'reason'          => $request->reason,
				]);

				HrmRequestDetail::create([
						'request_id' => $leaveRequest->id,
						'request_data' => [
							'leave_type_id'   => $leaveType->id,
							'leave_type_code' => $leaveType->code,
							'leave_type_name' => $leaveType->name,
							'type'            => $request->type,
							'notify_team'     => $request->notify_team ?? false,
						],
				]);

				HrmRequestApproval::create([
						'request_id' => $leaveRequest->id,
						'action_by'  => $user->staffid,
						'action_type'=> 'submitted',
						'remarks'    => 'Leave applied by employee',
						'action_at'  => now(),
				]);

				DB::commit();

				return response()->json([
						'status'  => true,
						'message' => 'Leave applied successfully.',
						'data'    => $leaveRequest,
				], 201);
			} catch (\Exception $e) {
				DB::rollBack();

				return response()->json([
					'status' => false,
					'message' => $e->getMessage(),
				], 500);
			}
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function cancelLeave(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$leaveRequestType = HrmRequestType::where('code', 'LEAVE')->first();

			if (!$leaveRequestType) {
					return response()->json([
						'status'  => false,
						'message' => 'Leave request type not configured.',
					], 404);
			}

			$leaveRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $leaveRequestType->id)
					->first();

			if (!$leaveRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'Leave request not found.',
				], 404);
			}

			if (in_array($leaveRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This leave request cannot be cancelled.',
				], 422);
			}

			DB::beginTransaction();

			try {
					$oldStatus = $leaveRequest->status;

					$leaveRequest->update([
						'status' => 'cancelled',
					]);

					HrmRequestApproval::create([
						'request_id'  => $leaveRequest->id,
						'action_by'   => $user->staffid,
						'action_type' => 'cancelled',
						'remarks'     => $request->reason ?? 'Leave cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'Leave cancelled successfully.',
						'data'    => [
							'request_id' => $leaveRequest->id,
							'old_status' => $oldStatus,
							'new_status' => 'cancelled',
						]
					], 200);

			} catch (\Exception $e) {
				DB::rollBack();

				return response()->json([
					'status' => false,
					'message' => $e->getMessage(),
				], 500);
			}

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	// Short leave section ========================
	public function applyShortLeave(Request $request){
		$request->validate([
			'date'			=> 'required|date',
			'from_time'	=> 'required|date_format:H:i:s',
			'to_time'		=> 'required|date_format:H:i:s',
			'reason'		=> 'required|string|max:500'
		]);

		try {
				$user = $request->user();
				$shortLeaveRequestType = HrmRequestType::where('code', 'SHORT_LEAVE')->first();

				if (!$shortLeaveRequestType) {
					return response()->json([
						'status'  => false,
						'message' => 'Short Leave request type not configured.',
					], 404);
				}

				$exists = HrmEmployeeRequest::where('staff_id', $user->staffid)
					->where('request_type_id', $shortLeaveRequestType->id)
					->whereDate('from_date', $request->date)
					->whereIn('status', ['pending', 'approved'])
					->where(function ($query) use ($request) {
            $query->where('from_time', '<', $request->to_time)
              ->where('to_time', '>', $request->from_time);
					})
					->exists();

        if ($exists) {
					return response()->json([
						'status'  => false,
						'message' => 'You already have a short leave request in this date time.'
					], 422);
        }

				DB::beginTransaction();
				try{
				
					$shortLeaveRequest = HrmEmployeeRequest::create([
						'staff_id'     		=> $user->staffid,
						'request_type_id' => $shortLeaveRequestType->id,
						'request_date'    => now()->toDateString(),
						'from_date'       => $request->date,
						'to_date'         => $request->date,
						'from_time'       => $request->from_time,
						'to_time'         => $request->to_time,
						'total_days'      => 1,
						'status'          => 'pending',
						'reason'          => $request->reason
					]);

					HrmRequestDetail::create([
						'request_id' => $shortLeaveRequest->id,
						'request_data' => [
							'date' => $request->date,
							'from_time' => $request->from_time,
							'to_time' => $request->to_time
						],
					]);

					HrmRequestApproval::create([
						'request_id' => $shortLeaveRequest->id,
						'action_by'  => $user->staffid,
						'action_type'=> 'submitted',
						'remarks'    => "Short Leave applied by $user->firstname $user->lastname",
						'action_at'  => now(),
					]);

					DB::commit();

					return response()->json([
						'status' => true,
						'message' => 'Short Leave request submitted successfully.',
						'data' => $shortLeaveRequest
					]);

				} catch (\Exception $e) {
					DB::rollBack();
					return response()->json([
						'status' => false,
						'message' => 'Something went wrong.',
						'error' => $e->getMessage()
					], 500);
				}

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function cancelShortLeave(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$shortLeaveRequestType = HrmRequestType::where('code', 'SHORT_LEAVE')->first();

			if (!$shortLeaveRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'Short Leave request type not configured.',
				], 404);
			}

			$wfhRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $shortLeaveRequestType->id)
					->first();

			if (!$wfhRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'Short Leave request not found.',
				], 404);
			}

			if (in_array($wfhRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This Short Leave request cannot be cancelled.',
				], 422);
			}

			DB::beginTransaction();

			try {
					$oldStatus = $wfhRequest->status;

					$wfhRequest->update([
						'status' => 'cancelled',
					]);

					HrmRequestApproval::create([
						'request_id'  => $wfhRequest->id,
						'action_by'   => $user->staffid,
						'action_type' => 'cancelled',
						'remarks'     => $request->reason ?? 'Short Leave cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'Short Leave cancelled successfully.',
						'data'    => [
							'request_id' => $wfhRequest->id,
							'old_status' => $oldStatus,
							'new_status' => 'cancelled',
						]
					], 200);

			} catch (\Exception $e) {
				DB::rollBack();

				return response()->json([
					'status' => false,
					'message' => $e->getMessage(),
				], 500);
			}

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function shortLeaveHistory(Request $request) {
		try {
			$user = $request->user();

    	$shortLeaveRequestType = HrmRequestType::where('code', 'SHORT_LEAVE')->first();

    	if (!$shortLeaveRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'Short Leave request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $shortLeaveRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'Short Leave history fetched successfully.',
        'data'    => $history
    	], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

}
