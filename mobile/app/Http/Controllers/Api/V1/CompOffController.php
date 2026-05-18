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

class CompOffController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function applyCompOff(Request $request){
		$request->validate([
			'working_date'	=> 'required|date_format:Y-m-d',
			'compoff_date'	=> 'required|date_format:Y-m-d',
			'reason'				=> 'required|string|max:500'
    ]);

		try {
			$user = $request->user();

			$compOffRequestType = HrmRequestType::where('code', 'COMP_OFF')->first();

			if (!$compOffRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'Comp Off request type not configured.',
				], 404);
			}

			$exists = HrmEmployeeRequest::where('staff_id', $user->staffid)
				->where('request_type_id', $compOffRequestType->id)
				->whereIn('status', ['pending', 'approved'])
				->where('from_date', $request->working_date)
				->exists();

			if ($exists) {
				return response()->json([
					'status'  => false,
					'message' => 'You already have a Comp Off request in this date.'
				], 422);
			}

			DB::beginTransaction();
			try{

        $compOffRequest = HrmEmployeeRequest::create([
					'staff_id'     		=> $user->staffid,
					'request_type_id' => $compOffRequestType->id,
					'request_date'    => now()->toDateString(),
					'from_date'       => $request->working_date,
					'to_date'         => $request->compoff_date,
					'total_days'      => 1,
					'status'          => 'pending',
					'reason'          => $request->reason
				]);

				HrmRequestDetail::create([
					'request_id' => $compOffRequest->id,
					'request_data' => [
						'working_date' => $request->working_date,
						'compoff_date' => $request->compoff_date,
						'reason'       => $request->reason
					],
				]);

				HrmRequestApproval::create([
					'request_id' => $compOffRequest->id,
					'action_by'  => $user->staffid,
					'action_type'=> 'submitted',
					'remarks'    => "Comp Off applied by $user->firstname $user->lastname",
					'action_at'  => now(),
				]);

				DB::commit();

				return response()->json([
					'status' => true,
					'message' => 'Comp Off request submitted successfully.',
					'data' => $compOffRequest
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

	public function cancelCompOff(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$compOffRequestType = HrmRequestType::where('code', 'COMP_OFF')->first();

			if (!$compOffRequestType) {
				return response()->json([
					'status'  => false,
					'message' => 'Comp Off request type not configured.',
				], 404);
			}

			$compOffRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $compOffRequestType->id)
					->first();

			if (!$compOffRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'Comp Off request not found.',
				], 404);
			}

			if (in_array($compOffRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This Comp Off request cannot be cancelled.',
				], 422);
			}

			DB::beginTransaction();

			try {
					$oldStatus = $compOffRequest->status;

					$compOffRequest->update([
						'status' => 'cancelled',
					]);

					HrmRequestApproval::create([
						'request_id'  => $compOffRequest->id,
						'action_by'   => $user->staffid,
						'action_type' => 'cancelled',
						'remarks'     => $request->reason ?? 'Comp Off cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'Comp Off cancelled successfully.',
						'data'    => [
							'request_id' => $compOffRequest->id,
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

	public function compOffHistory(Request $request) {
		try {
			$user = $request->user();

    	$compOffRequestType = HrmRequestType::where('code', 'COMP_OFF')->first();

    	if (!$compOffRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'Comp Off request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $compOffRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'Comp Off history fetched successfully.',
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
