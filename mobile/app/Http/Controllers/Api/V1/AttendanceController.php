<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HrmEmployeeRequest;
use App\Models\AttendanceDaily;
use App\Models\AttendanceRawLog;
use App\Models\AttendanceBreak;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
  public function __construct() { }
	
	// CRUD operations
  public function index() { }
  public function store(Request $request) { }
  public function show(string $id) { }
  public function update(Request $request, string $id) { }
  public function destroy(string $id) { }

	// Attendance specific operations
	public function punchIn(Request $request) 
	{ 
		$request->validate([
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'device_name' => 'nullable|string|max:150',
		]);

		$user = $request->user();
		$today = now()->toDateString();

		try{
			// Check already punched in today
			$existingAttendance = AttendanceDaily::where('staff_id', $user->staffid)
				->whereDate('attendance_date', $today)
				->first();

			if ($existingAttendance && $existingAttendance->punch_in_time) {
				return response()->json([
					'status' => false,
					'message' => 'Already punched in for today.',
					'data' => $existingAttendance
				], 400);
			}

			// Insert raw log
			AttendanceRawLog::create([
				'staff_id' => $user->staffid,
				'employee_code' => $user->employee_code ?? null,
				'log_time' => now(),
				'direction' => 'PUNCH_IN',
				'device_name' => $request->device_name,
				'latitude' => $request->latitude,
				'longitude' => $request->longitude,
				'gps' => 'ACTIVE',
			]);

			$currentTime = now();
			$currentHourMinute = $currentTime->format('H:i');

			$status = 'PRESENT';

			if ($currentHourMinute > '13:00') {
				$status = 'HALF_DAY';
			} elseif ($currentHourMinute > '10:00') {
				$status = 'LATE';
			}

			// Create or update daily attendance
			$attendance = AttendanceDaily::updateOrCreate(
				[
					'staff_id' => $user->staffid,
					'attendance_date' => $today
				],
				[
					'employee_code' => $user->employee_code ?? null,
					'punch_in_time' => now(),
					'attendance_status' => $status
				]
			);
			// if any backday punch_out_time is null then update it 23:59:59
			// if today is 2026-05-23 then update 2026-05-22 23:59:59
			$records = AttendanceDaily::where('staff_id', $user->staffid)
				->whereDate('attendance_date', '<', $today)
				->whereNull('punch_out_time')
				->get();
				
			if (count($records) > 0) {
				foreach ($records as $row) {
					$row->punch_out_time = Carbon::parse($row->attendance_date)->endOfDay();
					$row->save();
				}
			}

			return response()->json([
				'status' => true,
				'message' => 'Punch in successful.',
				'data' => $attendance
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function punchOut(Request $request) 
	{
		$request->validate([
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'device_name' => 'nullable|string|max:150',
		]);

		$user = $request->user();
		$today = now()->toDateString();

		try{
			$attendance = AttendanceDaily::where('staff_id', $user->staffid)
					->whereDate('attendance_date', $today)
					->first();

			if (!$attendance || !$attendance->punch_in_time) {
				return response()->json([
					'status' => false,
					'message' => 'Punch in not found for today.'
				], 400);
			}

			if ($attendance->punch_out_time) {
					return response()->json([
						'status' => false,
						'message' => 'Already punched out for today.',
						'data' => $attendance
					], 400);
			}

			$punchOutTime = now();

			// Calculate total work minutes
			$totalMinutes = $attendance->punch_in_time
				->diffInMinutes($punchOutTime);

			// Insert raw log
			AttendanceRawLog::create([
				'staff_id' => $user->staffid,
				'employee_code' => $user->employee_code ?? null,
				'log_time' => $punchOutTime,
				'direction' => 'PUNCH_OUT',
				'device_name' => $request->device_name,
				'latitude' => $request->latitude,
				'longitude' => $request->longitude,
				'gps' => 'ACTIVE',
			]);

			// Update daily attendance
			$attendance->update([
				'punch_out_time' => $punchOutTime,
				'total_work_minutes' => $totalMinutes
			]);

			return response()->json([
				'status' => true,
				'message' => 'Punch out successful.',
				'data' => $attendance->fresh()
			], 200);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function breakIn(Request $request)
	{
		$request->validate([
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'device_name' => 'nullable|string|max:150',
		]);

    $user = $request->user();
    $today = now()->toDateString();

		try{
			$attendance = AttendanceDaily::where('staff_id', $user->staffid)
				->whereDate('attendance_date', $today)
				->first();

			if (!$attendance || !$attendance->punch_in_time) {
				return response()->json([
					'status' => false,
					'message' => 'Please punch in first.'
				], 400);
			}

			if ($attendance->punch_out_time) {
				return response()->json([
					'status' => false,
					'message' => 'Attendance already closed for today.',
					'data' => $attendance
				], 400);
			}

			// Check active break
			$activeBreak = AttendanceBreak::where('staff_id', $user->staffid)
				->where('attendance_daily_id', $attendance->id)
				->whereNull('break_out_time')
				->first();

			if ($activeBreak) {
				return response()->json([
					'status' => false,
					'message' => 'Previous break is still active. Please break out first.',
					'data' => $activeBreak
				], 400);
			}

			// Count previous breaks
			$usedBreaks = AttendanceBreak::where('staff_id', $user->staffid)
				->where('attendance_daily_id', $attendance->id)
				->pluck('break_type')
				->toArray();

			$breakType = 'OTHER';

			if (!in_array('LUNCH', $usedBreaks)) {
				$breakType = 'LUNCH';
			} elseif (!in_array('TEA', $usedBreaks)) {
				$breakType = 'TEA';
			} elseif (!in_array('PERSONAL', $usedBreaks)) {
				$breakType = 'PERSONAL';
			}

			$break = AttendanceBreak::create([
				'staff_id' => $user->staffid,
				'attendance_daily_id' => $attendance->id,
				'break_in_time' => now(),
				'break_type' => $breakType
			]);

			AttendanceRawLog::create([
				'staff_id' => $user->staffid,
				'employee_code' => $user->employee_code ?? null,
				'log_time' => now(),
				'direction' => 'BREAK_IN',
				'device_name' => $request->device_name,
				'latitude' => $request->latitude,
				'longitude' => $request->longitude,
				'gps' => 'ACTIVE'
			]);

			return response()->json([
				'status' => true,
				'message' => 'Break started successfully.',
				'data' => $break
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
	public function breakOut(Request $request)
	{
		$request->validate([
			'latitude' => 'required|numeric',
			'longitude' => 'required|numeric',
			'device_name' => 'nullable|string|max:150',
		]);

    $user = $request->user();
    $today = now()->toDateString();

		try{
			$attendance = AttendanceDaily::where('staff_id', $user->staffid)
				->whereDate('attendance_date', $today)
				->first();

			if (!$attendance) {
				return response()->json([
					'status' => false,
					'message' => 'Attendance not found.',
				], 400);
			}

			$activeBreak = AttendanceBreak::where('staff_id', $user->staffid)
				->where('attendance_daily_id', $attendance->id)
				->whereNull('break_out_time')
				->latest()
				->first();

			if (!$activeBreak) {
				return response()->json([
					'status' => false,
					'message' => 'No active break found.'
				], 400);
			}

			$breakOutTime = now();

			$minutes = $activeBreak->break_in_time
				->diffInMinutes($breakOutTime);

			$activeBreak->update([
				'break_out_time' => $breakOutTime,
				'break_minutes' => $minutes
			]);

			// Update daily total break
			$attendance->increment('total_break_minutes', $minutes);

			AttendanceRawLog::create([
				'staff_id' => $user->staffid,
				'employee_code' => $user->employee_code ?? null,
				'log_time' => now(),
				'direction' => 'BREAK_OUT',
				'device_name' => $request->device_name,
				'latitude' => $request->latitude,
				'longitude' => $request->longitude,
				'gps' => 'ACTIVE',
			]);

			return response()->json([
				'status' => true,
				'message' => 'Break ended successfully.',
				'data' => $activeBreak->fresh()
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function todayStatus(Request $request) {
		try {
			$user = $request->user();
			$today = now()->toDateString();

			// Main attendance record (punch in/out)
			$attendance = AttendanceDaily::where('staff_id', $user->staffid)
					->whereDate('attendance_date', $today)
					->first();

			if (!$attendance) {
					return response()->json([
							'status' => true,
							'message' => 'No attendance found for today.',
							'data' => [
									'is_punched_in' => false,
									'is_punched_out' => false,
									'is_break_active' => false,
									'attendance' => null,
									'active_break' => null
							]
					]);
			}

			// Check active break separately from break table
			$activeBreak = AttendanceBreak::where('staff_id', $user->staffid)
					->where('attendance_daily_id', $attendance->id)
					->whereNull('break_out_time')
					->latest()
					->first();

			return response()->json([
					'status' => true,
					'message' => 'Today status fetched successfully.',
					'data' => [
							'is_punched_in' => !empty($attendance->punch_in_time),
							'is_punched_out' => !empty($attendance->punch_out_time),
							'is_break_active' => $activeBreak ? true : false,
							'attendance_status' => $attendance->attendance_status,
							'attendance' => $attendance,
							'active_break' => $activeBreak
					]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function dayStatus(Request $request)
	{
    try {
        $user = $request->user();

        $selectedDate = $request->filled('date')
            ? Carbon::parse($request->date)->toDateString()
            : now()->toDateString();

        /*
        |--------------------------------------------------------------------------
        | Check Leave / Request
        |--------------------------------------------------------------------------
        */
        $leaveRequest = HrmEmployeeRequest::with('requestType')
            ->where('staff_id', $user->staffid)
            ->whereDate('from_date', '<=', $selectedDate)
            ->whereDate('to_date', '>=', $selectedDate)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Check Attendance
        |--------------------------------------------------------------------------
        */
        $attendance = AttendanceDaily::where('staff_id', $user->staffid)
            ->whereDate('attendance_date', $selectedDate)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Check Week Off
        |--------------------------------------------------------------------------
        */
        $isWeekOff = $this->isWeekOff($selectedDate);

        /*
        |--------------------------------------------------------------------------
        | Active Break
        |--------------------------------------------------------------------------
        */
        $activeBreak = null;

        if ($attendance) {
            $activeBreak = AttendanceBreak::where('staff_id', $user->staffid)
                ->where('attendance_daily_id', $attendance->id)
                ->whereNull('break_out_time')
                ->latest()
                ->first();
        }

        /*
        |--------------------------------------------------------------------------
        | Response Type Logic
        |--------------------------------------------------------------------------
        */
        $dayType = 'absent';

        if ($leaveRequest) {
            $dayType = 'leave';
        } elseif ($attendance) {
            $dayType = 'attendance';
        } elseif ($isWeekOff) {
            $dayType = 'week_off';
        } elseif ($selectedDate > now()->toDateString()) {
            $dayType = 'upcoming';
        }

        return response()->json([
            'status' => true,
            'message' => 'Day status fetched successfully.',
            'data' => [
                'date' => $selectedDate,
                'type' => $dayType,
                'is_punched_in' => $attendance ? !empty($attendance->punch_in_time) : false,
                'is_punched_out' => $attendance ? !empty($attendance->punch_out_time) : false,
                'is_break_active' => $activeBreak ? true : false,
                'attendance_status' => $attendance->attendance_status ?? null,
                'attendance' => $attendance,
                'active_break' => $activeBreak,
                'leave_request' => $leaveRequest,
                'request_type' => $leaveRequest->requestType->name ?? null
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Something went wrong.',
            'error' => $e->getMessage()
        ], 500);
    }
	}

	public function calendarView(Request $request)
	{
		$request->validate([
			'month' => 'required|date_format:Y-m',
		]);
		try {
			$user = $request->user();
			$month = Carbon::parse($request->month);
			$year = $month->year;
			$monthNumber = $month->month;

			// Get all attendance records for the month
			$attendances = AttendanceDaily::where('staff_id', $user->staffid)
					->whereYear('attendance_date', $year)
					->whereMonth('attendance_date', $monthNumber)
					->get()
					->keyBy(function ($item) {
							return Carbon::parse($item->attendance_date)->format('Y-m-d');
					});

			// Get all requests (leaves/holidays) for the month
			$rawRequests = HrmEmployeeRequest::with('requestType')
					->where('staff_id', $user->staffid)
					->where(function ($query) use ($year, $monthNumber) {
							$query->whereYear('from_date', $year)
										->whereMonth('from_date', $monthNumber)
										->orWhere(function ($q) use ($year, $monthNumber) {
												$q->whereYear('to_date', $year)
													->whereMonth('to_date', $monthNumber);
										});
					})
					->get();

			$requests = [];

			foreach ($rawRequests as $requestItem) {
					$startDate = Carbon::parse($requestItem->from_date);
					$endDate   = Carbon::parse($requestItem->to_date);

					while ($startDate <= $endDate) {
							$dateKey = $startDate->format('Y-m-d');

							$requests[$dateKey][] = $requestItem;

							$startDate->addDay();
					}
			}

			// Build calendar data
			$daysInMonth = Carbon::parse($request->month)->daysInMonth;
			$calendarData = [];
			$summary = [
				'present'  => 0,
				'absent'   => 0,
				'half_day' => 0,
				'leave'    => 0,
				'week_off' => 0,
				'upcoming'   => 0
			];

			for ($day = 1; $day <= $daysInMonth; $day++) {
					$dateKey = Carbon::createFromDate($year, $monthNumber, $day)->format('Y-m-d');

					if (isset($attendances[$dateKey])) {
							$statusData = $this->calendarStatusFromAttendance($attendances[$dateKey]);
							$calendarData[] = array_merge([
									'date' => $dateKey,
									'type' => 'attendance'
							], $statusData);

							if (($statusData['status'] ?? '') === 'present') {
								$summary['present']++;
							} elseif (($statusData['status'] ?? '') === 'half_day') {
								$summary['half_day']++;
							} else {
								$summary['present']++;
							}
					} elseif (isset($requests[$dateKey])) {
							foreach ($requests[$dateKey] as $requestItem) {
									$statusData = $this->calendarStatusFromRequest($requestItem);
									$calendarData[] = array_merge([
											'date' => $dateKey,
											'type' => 'request',
											'request_id' => $requestItem->id,
											'request_type' => $requestItem->requestType->name ?? null,
									], $statusData);
							}

							$summary['leave']++;
					} elseif ($this->isWeekOff($dateKey)) {
							$calendarData[] = [
									'date' => $dateKey,
									'type' => 'holiday',
									'label' => 'WO',
									'holiday_name' => 'Week Off'
							];

							$summary['week_off']++;
					} elseif ($dateKey > now()->format('Y-m-d')) {
							$calendarData[] = [
									'date' => $dateKey,
									'type' => 'upcoming',
							];

							$summary['upcoming']++;
					}else {
							$calendarData[] = [
									'date' => $dateKey,
									'type' => 'absent',
							];

							$summary['absent']++;
					}
			}

			return response()->json([
					'status' => true,
					'message' => 'Calendar view fetched successfully.',
					'data' => [
						'summary' => $summary,
						'calendar' => $calendarData
					]
			]);

		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	private function isWeekOff($date){
    $carbonDate = Carbon::parse($date);
    // Every Sunday
    if ($carbonDate->dayOfWeek === Carbon::SUNDAY) {
      return true;
    }
    // Only Saturday check
    if ($carbonDate->dayOfWeek === Carbon::SATURDAY) {
			$weekNumber = ceil($carbonDate->day / 7);

			// 2nd and 4th Saturday
			if (in_array($weekNumber, [2, 4])) {
				return true;
			}
    }
    return false;
	}

	private function calendarStatusFromAttendance(AttendanceDaily $attendance): array
	{
		return match ($attendance->attendance_status) {
			'LATE' => [
				'status' => 'late',
				'label' => 'L',
				'color' => '#FFC107',
			],
			'HALF_DAY' => [
				'status' => 'half_day',
				'label' => 'HD',
				'color' => '#FD7E14',
			],
			'LEAVE' => [
				'status' => 'leave',
				'label' => 'LV',
				'color' => '#17A2B8',
			],
			'HOLIDAY' => [
				'status' => 'holiday',
				'label' => 'H',
				'color' => '#6C757D',
			],
			'WEEK_OFF' => [
				'status' => 'week_off',
				'label' => 'WO',
				'color' => '#343A40',
			],
			'ABSENT' => [
				'status' => 'absent',
				'label' => 'A',
				'color' => '#DC3545',
			],
			default => [
				'status' => 'present',
				'label' => 'P',
				'color' => '#28A745',
			],
		};
	}

	private function calendarStatusFromRequest(HrmEmployeeRequest $request): array
	{
		if ($request->requestType?->code === 'RESTRICTED_HOLIDAY') {
			return [
				'status' => 'holiday',
				'label' => 'H',
				'color' => $request->requestType->color_code ?: '#6C757D',
			];
		}

		return [
			'status' => 'leave',
			'label' => 'LV',
			'color' => $request->requestType?->color_code ?: '#17A2B8',
		];
	}

	public function monthlyList(Request $request) {
		try {
			$user = $request->user();

			$month = $request->month ?? now()->month;
			$year  = $request->year ?? now()->year;

			$records = AttendanceDaily::where('staff_id', $user->staffid)
					->whereMonth('attendance_date', $month)
					->whereYear('attendance_date', $year)
					->orderBy('attendance_date', 'desc')
					->get();

			return response()->json([
					'status'  => true,
					'message' => 'Monthly attendance fetched successfully.',
					'data'    => [
							'month'   => (int) $month,
							'year'    => (int) $year,
							'total'   => $records->count(),
							'records' => $records
					]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function dateRangeList(Request $request) {
		try {
			$request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date',
        'status'    => 'nullable|string'
			]);

			$user = $request->user();

			$query = AttendanceDaily::where('staff_id', $user->staffid)
					->whereBetween('attendance_date', [
							$request->from_date,
							$request->to_date
					]);

			// Optional status filter
			if ($request->filled('status')) {
					$query->where('attendance_status', $request->status);
			}

			$records = $query
					->orderBy('attendance_date', 'desc')
					->get();

			return response()->json([
					'status'  => true,
					'message' => 'Attendance date range list fetched successfully.',
					'data'    => [
							'from_date' => $request->from_date,
							'to_date'   => $request->to_date,
							'total'     => $records->count(),
							'records'   => $records
					]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function reportSummary(Request $request) {
		try {
			$request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date'
			]);

			$user = $request->user();

			$fromDate = \Carbon\Carbon::parse($request->from_date);
			$toDate   = \Carbon\Carbon::parse($request->to_date);

			$query = AttendanceDaily::where('staff_id', $user->staffid)
					->whereBetween('attendance_date', [
							$fromDate->toDateString(),
							$toDate->toDateString()
					]);

			// Employee office days (attendance entries)
			$presentDays = (clone $query)->count();

			// Working days excluding Saturday and Sunday
			$workingDays = 0;
			$loopDate = $fromDate->copy();

			while ($loopDate <= $toDate) {
					if (!$loopDate->isSaturday() && !$loopDate->isSunday()) {
							$workingDays++;
					}
					$loopDate->addDay();
			}

			$fullDays = (clone $query)
					->where('attendance_status', 'PRESENT')
					->count();

			$lateDays = (clone $query)
					->where('attendance_status', 'LATE')
					->count();

			$halfDays = (clone $query)
					->where('attendance_status', 'HALF_DAY')
					->count();

			$totalWorkMinutes = (clone $query)->sum('total_work_minutes');
			$totalBreakMinutes = (clone $query)->sum('total_break_minutes');

			$absentDays = $workingDays - $presentDays;

			return response()->json([
					'status'  => true,
					'message' => 'Attendance summary fetched successfully.',
					'data'    => [
							'from_date'           => $request->from_date,
							'to_date'             => $request->to_date,
							'working_days'        => $workingDays,
							'present_days'        => $presentDays,
							'full_days'         	=> $fullDays,
							'late_days'           => $lateDays,
							'half_days'           => $halfDays,
							'absent_days'         => $absentDays,
							'total_work_minutes'  => $totalWorkMinutes,
							'total_break_minutes' => $totalBreakMinutes,
							'total_work_hours'    => round($totalWorkMinutes / 60, 2),
							'total_break_hours'   => round($totalBreakMinutes / 60, 2)
					]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}
	
	public function breakHistory(Request $request) {
		try {
			$request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date',
			]);

			$user = $request->user();

			$query = AttendanceBreak::where('staff_id', $user->staffid);

			// Optional date range filter
			if ($request->filled('from_date') && $request->filled('to_date')) {
					$query->whereBetween('break_in_time', [
							$request->from_date . ' 00:00:00',
							$request->to_date . ' 23:59:59'
					]);
			} else {
					// Default: current month
					$query->whereMonth('break_in_time', now()->month)
								->whereYear('break_in_time', now()->year);
			}

			$records = $query->orderBy('break_in_time', 'desc')->get();

			return response()->json([
					'status'  => true,
					'message' => 'Break history fetched successfully.',
					'data'    => [
							'total'   => $records->count(),
							'records' => $records
					]
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function travelReport(Request $request) {
		try {
			return response()->json([
				'status' => true,
				'message' => 'Travel report fetched successfully.',
				'data' => 'This endpoint is under development.'
			]);
		} catch (\Exception $e) {
			return response()->json([
				'status' => false,
				'message' => 'Something went wrong.',
				'error' => $e->getMessage()
			], 500);
		}
	}

	public function rawLogs(Request $request) {
		try {
			$request->validate([
        'from_date' => 'required|date',
        'to_date'   => 'required|date|after_or_equal:from_date',
        'direction' => 'nullable|string'
			]);

			$user = $request->user();

			$query = AttendanceRawLog::where('staff_id', $user->staffid);

			// Optional date range filter
			if ($request->filled('from_date') && $request->filled('to_date')) {
					$query->whereBetween('log_time', [
							$request->from_date . ' 00:00:00',
							$request->to_date . ' 23:59:59'
					]);
			} else {
					// Default current month
					$query->whereMonth('log_time', now()->month)
								->whereYear('log_time', now()->year);
			}

			// Optional direction filter
			if ($request->filled('direction')) {
					$query->where('direction', $request->direction);
			}

			$logs = $query
					->orderBy('log_time', 'desc')
					->get();

			return response()->json([
					'status'  => true,
					'message' => 'Raw logs fetched successfully.',
					'data'    => [
							'total'   => $logs->count(),
							'records' => $logs
					]
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
