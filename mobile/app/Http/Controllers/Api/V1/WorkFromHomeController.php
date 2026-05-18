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

class WorkFromHomeController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function workFromHomeBalance(Request $request){
		$quota = 28;
		$user = $request->user();
		$wfhRequestType = HrmRequestType::where('code', 'WFH')->first();

		if (!$wfhRequestType) {
			return response()->json([
				'status'  => false,
				'message' => 'WFH request type not configured.',
			], 404);
		}

		$used = HrmEmployeeRequest::where('staff_id', $user->staffid)
			->where('request_type_id', $wfhRequestType->id)
			->whereIn('status', ['pending', 'approved'])
			->sum('total_days');

		return response()->json([
			'status'  => true,
			'message' => 'Work from home balance fetched successfully.',
			'data'    => [
				'quota'   => $quota,
				'used'    => (int)$used,
				'balance' => $quota - (int)$used
			]
		], 200);
	}

	public function applyWorkFromHome(Request $request){
		$request->validate([
			'from_date'   	=> 'required|date',
			'to_date' 			=> 'required|date|after_or_equal:from_date',
			'type' 					=> 'required|string|in:full_day,first_half,second_half',
			'work_location' => 'required|string',
			'reason'				=> 'required|string|max:500',
			'notify_team'  	=> 'nullable|boolean'
		]);

		try {
				$user = $request->user();
				$wfhRequestType = HrmRequestType::where('code', 'WFH')->first();

				if (!$wfhRequestType) {
					return response()->json([
						'status'  => false,
						'message' => 'WFH request type not configured.',
					], 404);
				}

				$exists = HrmEmployeeRequest::where('staff_id', $user->staffid)
					->where('request_type_id', $wfhRequestType->id)
					->whereIn('status', ['pending', 'approved'])
					->where(function ($q) use ($request) {
						$q->whereBetween('from_date', [$request->from_date, $request->to_date])
							->orWhereBetween('to_date', [$request->from_date, $request->to_date]);
					})
					->exists();

        if ($exists) {
					return response()->json([
						'status'  => false,
						'message' => 'You already have a WFH request in this date range.'
					], 422);
        }

				$days = Carbon::parse($request->from_date)
    			->diffInDays(Carbon::parse($request->to_date)) + 1;
				
				// // check if user has enough balance
				// $quota = 14;
				// $used = HrmEmployeeRequest::where('staff_id', $user->staffid)
				// 	->where('request_type_id', $wfhRequestType->id)
				// 	->whereIn('status', ['pending', 'approved'])
				// 	->sum('total_days');
				// $balance = $quota - (int)$used;

				// if ($balance < $days) {
				// 	return response()->json([
				// 		'status'  => false,
				// 		'message' => 'You do not have enough WFH balance. Remaining balance: '.$balance.' days.'
				// 	], 422);
				// }

				DB::beginTransaction();
				try{
				
					$wfhRequest = HrmEmployeeRequest::create([
						'staff_id'     		=> $user->staffid,
						'request_type_id' => $wfhRequestType->id,
						'request_date'    => now()->toDateString(),
						'from_date'       => $request->from_date,
						'to_date'         => $request->to_date,
						'total_days'      => $days,
						'status'          => 'pending',
						'reason'          => $request->reason
					]);

					HrmRequestDetail::create([
						'request_id' => $wfhRequest->id,
						'request_data' => [
							'work_location'   => $request->work_location,
							'type'            => $request->type,
							'notify_team'     => $request->notify_team ?? false,
						],
					]);

					HrmRequestApproval::create([
						'request_id' => $wfhRequest->id,
						'action_by'  => $user->staffid,
						'action_type'=> 'submitted',
						'remarks'    => "WFH applied by $user->firstname $user->lastname",
						'action_at'  => now(),
					]);

					DB::commit();

					return response()->json([
						'status' => true,
						'message' => 'Work From Home request submitted successfully.',
						'data' => $wfhRequest
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

	public function cancelWorkFromHome(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$wfhRequestType = HrmRequestType::where('code', 'WFH')->first();

			if (!$wfhRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'Work From Home request type not configured.',
				], 404);
			}

			$wfhRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $wfhRequestType->id)
					->first();

			if (!$wfhRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'Work From Home request not found.',
				], 404);
			}

			if (in_array($wfhRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This Work From Home request cannot be cancelled.',
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
						'remarks'     => $request->reason ?? 'Work From Home cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'Work From Home cancelled successfully.',
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

	public function workFromHomeHistory(Request $request) {
		try {
			$user = $request->user();

    	$wfhRequestType = HrmRequestType::where('code', 'WFH')->first();

    	if (!$wfhRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'Work From Home request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $wfhRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'Work From Home history fetched successfully.',
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
