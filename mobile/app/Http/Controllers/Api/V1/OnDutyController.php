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

class OnDutyController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function applyOnDuty(Request $request){
		$request->validate([
			'date'   => 'required|array|min:1',
			'date.*' => 'required|date_format:Y-m-d',
    ]);

		try {
			$user = $request->user();

			$ondutyRequestType = HrmRequestType::where('code', 'ON_DUTY')->first();

			if (!$ondutyRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'On Duty request type not configured.',
				], 404);
			}

			DB::beginTransaction();
			try{

        $createdRequests = [];

        foreach ($request->date as $date) {
					$exists = HrmEmployeeRequest::where('staff_id', $user->staffid)
						->where('request_type_id', $ondutyRequestType->id)
						->whereIn('status', ['pending', 'approved'])
						->whereDate('from_date', $date)
						->exists();

					if ($exists) {
						DB::rollBack();
						return response()->json([
							'status'  => false,
							'message' => "On Duty already exists for date: $date"
						], 422);
					}
					
					$ondutyRequest = HrmEmployeeRequest::create([
						'staff_id'        => $user->staffid,
						'request_type_id' => $ondutyRequestType->id,
						'request_date'    => now(),
						'from_date'       => $date,
						'to_date'         => $date,
						'total_days'      => 1,
						'status'          => 'pending',
						'reason'          => 'on duty request',
					]);
					
					HrmRequestApproval::create([
						'request_id' => $ondutyRequest->id,
						'action_by'  => $user->staffid,
						'action_type'=> 'submitted',
						'remarks'    => "On Duty applied for $date by {$user->firstname} {$user->lastname}",
						'action_at'  => now(),
					]);

					$createdRequests[] = $ondutyRequest;
        }

        DB::commit();

        return response()->json([
					'status'  => true,
					'message' => 'On Duty requests submitted successfully.',
					'data'    => $createdRequests
        ]);

    	} catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
					'status'  => false,
					'message' => 'Something went wrong.',
					'error'   => $e->getMessage()
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

	public function cancelOnDuty(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$ondutyRequestType = HrmRequestType::where('code', 'ON_DUTY')->first();

			if (!$ondutyRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'On Duty request type not configured.',
				], 404);
			}

			$ondutyRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $ondutyRequestType->id)
					->first();

			if (!$ondutyRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'On Duty request not found.',
				], 404);
			}

			if (in_array($ondutyRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This On Duty request cannot be cancelled.',
				], 422);
			}

			DB::beginTransaction();

			try {
					$oldStatus = $ondutyRequest->status;

					$ondutyRequest->update([
						'status' => 'cancelled',
					]);

					HrmRequestApproval::create([
						'request_id'  => $ondutyRequest->id,
						'action_by'   => $user->staffid,
						'action_type' => 'cancelled',
						'remarks'     => $request->reason ?? 'On Duty cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'On Duty cancelled successfully.',
						'data'    => [
							'request_id' => $ondutyRequest->id,
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

	public function onDutyHistory(Request $request) {
		try {
			$user = $request->user();

    	$ondutyRequestType = HrmRequestType::where('code', 'ON_DUTY')->first();

    	if (!$ondutyRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'On Duty request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $ondutyRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'On Duty history fetched successfully.',
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
