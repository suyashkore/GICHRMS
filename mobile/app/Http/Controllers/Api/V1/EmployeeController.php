<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Staff;
use App\Models\AttendanceDaily;
use App\Models\HrmEmployeeRequest;

class EmployeeController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	public function directory(Request $request){
		try {
			$today = Carbon::today()->toDateString();

			$rows = Staff::from('tblstaff as s')
				->join('tblstaff_departments as sd', 'sd.staffid', '=', 's.staffid')
				->join('tbldepartments as d', 'd.departmentid', '=', 'sd.departmentid')
				->leftJoin('tblroles as r', 'r.roleid', '=', 's.role')
				->where('s.active', 1)
				->select(
					'd.departmentid',
					'd.name as department_name',
					's.staffid',
					DB::raw("CONCAT(s.firstname, ' ', s.lastname) as staffname"),
					'r.name as role_name',
					's.profile_image'
				)
				->get();

			$attendance = AttendanceDaily::whereDate('attendance_date', $today)
				->pluck('attendance_status', 'staff_id')   // [staff_id => status]
				->toArray();

			$leaves = HrmEmployeeRequest::where('status', 'approved')
				->whereDate('from_date', '<=', $today)
				->whereDate('to_date', '>=', $today)
				->pluck('staff_id')
				->toArray();

			$leaveMap = array_flip($leaves);

			$directory = [];

			foreach ($rows as $row) {
				if (!isset($directory[$row->departmentid])) {
					$directory[$row->departmentid] = [
						'department' => $row->department_name,
						'employee' => []
					];
				}

				if (isset($attendance[$row->staffid])) {
					$status = $attendance[$row->staffid];
				} elseif (isset($leaveMap[$row->staffid])) {
					$status = 'on_leave';
				} else {
					$status = 'absent';
				}

				$directory[$row->departmentid]['employee'][] = [
					'id' => $row->staffid,
					'name' => $row->staffname,
					'role' => explode('- ', $row->role_name)[1] ?? $row->role_name,
					'profile_image' => null,
					'status' => ucwords(strtolower(str_replace('_', ' ', $status)))
				];
			}

			$directory = array_values($directory);

			return response()->json([
				'status' => true,
				'message' => 'Directory fetched successfully.',
				'data' => $directory
			]);

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
}
