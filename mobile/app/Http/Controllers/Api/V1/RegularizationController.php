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

class RegularizationController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function applyRegularization(Request $request){
		$request->validate([
			'attendance_date'   	=> 'required|date',
			'requested_in_time' 	=> 'nullable|date_format:H:i:s',
			'requested_out_time'	=> 'nullable|date_format:H:i:s',
			'regularization_type' => 'required|string|in:forgot_punch,biometri_error,application_error,official_work,weekoff_work,network_issue,other',
			'reason'            	=> 'required|string|max:500',
			'attachment'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
		]);

		try {
				$user = $request->user();
				$date = Carbon::parse($request->attendance_date)->toDateString();

				// Prevent future date request
				if ($date > now()->toDateString()) {
					return response()->json([
						'status' => false,
						'message' => 'Regularization cannot be applied for future date.'
					], 422);
				}

				$requestType = HrmRequestType::where('code', 'REGULARISATION')->first();

				if (!$requestType) {
					return response()->json([
						'status'  => false,
						'message' => 'Regularization request type not configured.',
					], 404);
				}

				// Check duplicate pending request
				$existing = HrmEmployeeRequest::where('staff_id', $user->staffid)
					->where('request_type_id', $requestType->id)
					->whereDate('from_date', $date)
					->whereIn('status', ['pending', 'approved'])
					->first();

				if ($existing) {
					return response()->json([
						'status' => false,
						'message' => 'Regularization request already exists for this date.'
					], 422);
				}

				$filePath = null;

				if ($request->hasFile('attachment')) {
					$file = $request->file('attachment');
					$fileName = time() . '_' . $file->getClientOriginalName();
					$filePath = $file->storeAs(
						'regularization-attachments',
						$fileName,
						'public'
					);
				}

				$requestData = HrmEmployeeRequest::create([
					'staff_id'     => $user->staffid,
					'request_type_id' => $requestType->id,
					'request_date'    => now(),
					'from_date'    => $date,
					'to_date'      => $date,
					'reason'       => $request->reason,
					'status'       => 'pending',
					'attachment' => $filePath,
					'created_at'   => now()
				]);

				HrmRequestDetail::create([
					'request_id' => $requestData->id,
					'request_data' => [
						'attendance_date'    => $request->attendance_date,
						'regularization_type'=> $request->regularization_type,
						'requested_in_time'  => $request->requested_in_time,
						'requested_out_time' => $request->requested_out_time,
						'reason'             => $request->reason,
						'attachment'         => $filePath,
					],
				]);

				return response()->json([
					'status' => true,
					'message' => 'Regularization request submitted successfully.',
					'data' => $requestData
				]);

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function cancelRegularization(Request $request) {
		try {
			$user = $request->user();

			$request->validate([
				'request_id' => 'required|exists:hrm_employee_requests,id',
				'reason'     => 'nullable|string|max:500',
			]);

			$regularizationRequestType = HrmRequestType::where('code', 'REGULARISATION')->first();

			if (!$regularizationRequestType) {
					return response()->json([
						'status'  => false,
						'message' => 'Regularization request type not configured.',
					], 404);
			}

			$regularizationRequest = HrmEmployeeRequest::where('id', $request->request_id)
					->where('staff_id', $user->staffid)
					->where('request_type_id', $regularizationRequestType->id)
					->first();

			if (!$regularizationRequest) {
				return response()->json([
						'status'  => false,
						'message' => 'Regularization request not found.',
				], 404);
			}

			if (in_array($regularizationRequest->status, ['cancelled', 'rejected'])) {
				return response()->json([
					'status'  => false,
					'message' => 'This regularization request cannot be cancelled.',
				], 422);
			}

			DB::beginTransaction();

			try {
					$oldStatus = $regularizationRequest->status;

					$regularizationRequest->update([
						'status' => 'cancelled',
					]);

					HrmRequestApproval::create([
						'request_id'  => $regularizationRequest->id,
						'action_by'   => $user->staffid,
						'action_type' => 'cancelled',
						'remarks'     => $request->reason ?? 'Regularization cancelled by employee',
						'action_at'   => now(),
					]);

					DB::commit();

					return response()->json([
						'status'  => true,
						'message' => 'Regularization cancelled successfully.',
						'data'    => [
							'request_id' => $regularizationRequest->id,
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

	public function regularizationHistory(Request $request) {
		try {
			$user = $request->user();

    	$regularizationRequestType = HrmRequestType::where('code', 'REGULARISATION')->first();

    	if (!$regularizationRequestType) {
        return response()->json([
            'status'  => false,
            'message' => 'Regularization request type not configured.',
            'data'    => []
        ], 404);
    	}

    	$history = HrmEmployeeRequest::with([
					'details',
					'approvals',
        ])
        ->where('staff_id', $user->staffid)
        ->where('request_type_id', $regularizationRequestType->id)
        ->orderBy('created_at', 'desc')
        ->get();

    	return response()->json([
        'status'  => true,
        'message' => 'Regularization history fetched successfully.',
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

	public function applyRegularizationFromCI(Request $request) {
		try {
			if ($request->route() && $request->route()->getName() === 'regularization.apply-ci-web') {
				if (!$this->isSameOriginWebRequest($request)) {
					return response()->json([
						'status' => false,
						'message' => 'Invalid web request origin.',
					], 403);
				}
			}

			$request->validate([
				'staff_email'       	=> 'required|email|exists:tblstaff,email',
				'attendance_date'   	=> 'required|date',
				'requested_in_time' 	=> 'nullable|date_format:H:i:s',
				'requested_out_time'	=> 'nullable|date_format:H:i:s',
				'regularization_type' => 'required|string|in:forgot_punch,biometri_error,application_error,official_work,weekoff_work,network_issue,other',
				'reason'            	=> 'required|string|max:500',
				'attachment'          => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048'
			]);

			$user = Staff::where('email', $request->staff_email)->first();

			if (!$user) {
				return response()->json([
					'status' => false,
					'message' => 'Staff account not found.',
				], 404);
			}

			$date = Carbon::parse($request->attendance_date)->toDateString();

			// Prevent future date request
			if ($date > now()->toDateString()) {
				return response()->json([
					'status' => false,
					'message' => 'Regularization cannot be applied for future date.'
				], 422);
			}

			$requestType = HrmRequestType::where('code', 'REGULARISATION')->first();

			if (!$requestType) {
				return response()->json([	
					'status'  => false,
					'message' => 'Regularization request type not configured.',
				], 404);
			}

			// Check duplicate pending request
			$existing = HrmEmployeeRequest::where('staff_id', $user->staffid)
				->where('request_type_id', $requestType->id)
				->whereDate('from_date', $date)
				->whereIn('status', ['pending', 'approved'])
				->first();

			if ($existing) {
				return response()->json([
					'status' => false,
					'message' => 'Regularization request already exists for this date.'
				], 422);
			}

			$filePath = null;

			if ($request->hasFile('attachment')) {
				$file = $request->file('attachment');
				$fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $file->getClientOriginalName());
				$filePath = $file->storeAs(
					'regularization-attachments',
					$fileName,
					'public'
				);
			}

			DB::beginTransaction();

			try {
				$requestData = HrmEmployeeRequest::create([
					'staff_id'     => $user->staffid,
					'request_type_id' => $requestType->id,
					'request_date'    => now(),
					'from_date'    => $date,
					'to_date'      => $date,
					'reason'       => $request->reason,
					'status'       => 'pending',
					'attachment' => $filePath,
					'created_at'   => now()
				]);

				HrmRequestDetail::create([
					'request_id' => $requestData->id,
					'request_data' => [
						'attendance_date'    => $request->attendance_date,
						'regularization_type'=> $request->regularization_type,
						'requested_in_time'  => $request->requested_in_time,
						'requested_out_time' => $request->requested_out_time,
						'reason'             => $request->reason,
						'attachment'         => $filePath,
					],
				]);

				HrmRequestApproval::create([
					'request_id'  => $requestData->id,
					'action_by'   => $user->staffid,
					'action_type' => 'submitted',
					'remarks'     => 'Regularization applied by employee',
					'action_at'   => now(),
				]);

				DB::commit();

				return response()->json([
					'status' => true,
					'message' => 'Regularization request submitted successfully.',
					'data' => $requestData
				], 201);
			} catch (\Exception $e) {
				DB::rollBack();
				return response()->json([
					'status' => false,
					'message' => $e->getMessage(),
				], 500);
			}
		} catch (\Illuminate\Validation\ValidationException $ex) {
			return response()->json([
				'status' => false,
				'message' => 'Validation failed.',
				'errors' => $ex->errors(),
			], 422);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	private function isSameOriginWebRequest(Request $request): bool
	{
		$origin = $request->headers->get('origin');
		$requestedWith = $request->header('X-Requested-With');

		if (strcasecmp($requestedWith, 'XMLHttpRequest') !== 0) {
			return false;
		}

		if (!$origin) {
			// Some browsers omit Origin for same-origin GET requests.
			return true;
		}

		$originHost = parse_url($origin, PHP_URL_HOST);
		return $originHost && strcasecmp($originHost, $request->getHost()) === 0;
	}

}
