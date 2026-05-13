<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_requests_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_calendar_data($staff_id, $month)
    {
        $year        = date('Y', strtotime($month . '-01'));
        $monthNumber = date('m', strtotime($month . '-01'));
        $daysInMonth = date('t', strtotime($month . '-01'));
        $today       = date('Y-m-d');

        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $attendanceRows     = $this->db
            ->where('staff_id', $staff_id)
            ->where('YEAR(attendance_date)', $year, false)
            ->where('MONTH(attendance_date)', $monthNumber, false)
            ->get('attendance_daily')
            ->result();
        $this->db->dbprefix = $oldPrefix;

        $attendances = [];
        foreach ($attendanceRows as $row) {
            $attendances[date('Y-m-d', strtotime($row->attendance_date))] = $row;
        }

        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $requestRows        = $this->db
            ->select('r.*')
            ->from('hrm_employee_requests r')
            ->where('r.staff_id', $staff_id)
            ->group_start()
                ->group_start()
                    ->where('YEAR(r.from_date)', $year, false)
                    ->where('MONTH(r.from_date)', $monthNumber, false)
                ->group_end()
                ->or_group_start()
                    ->where('YEAR(r.to_date)', $year, false)
                    ->where('MONTH(r.to_date)', $monthNumber, false)
                ->group_end()
            ->group_end()
            ->get()
            ->result();
        $this->db->dbprefix = $oldPrefix;

        $requests = [];
        foreach ($requestRows as $requestItem) {
            $startDate = strtotime($requestItem->from_date);
            $endDate   = strtotime($requestItem->to_date);
            while ($startDate <= $endDate) {
                $requests[date('Y-m-d', $startDate)][] = $requestItem;
                $startDate = strtotime('+1 day', $startDate);
            }
        }

        $summary = [
            'present'  => 0,
            'absent'   => 0,
            'half_day' => 0,
            'week_off' => 0,
            'upcoming' => 0,
            'leave'    => 0,
        ];
        $calendar = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateKey = date('Y-m-d', strtotime($year . '-' . $monthNumber . '-' . $day));

            if (isset($attendances[$dateKey])) {
                $status     = $this->calendar_status_from_attendance($attendances[$dateKey]);
                $calendar[] = [
                    'date'       => $dateKey,
                    'day'        => $day,
                    'status'     => $status['status'],
                    'label'      => $status['label'],
                    'punch_in'   => $status['punch_in'],
                    'punch_out'  => $status['punch_out'],
                    'work_hours' => $status['work_hours'],
                ];
                if ($status['status'] === 'half_day') {
                    $summary['half_day']++;
                } else {
                    $summary['present']++;
                }
            } elseif (isset($requests[$dateKey])) {
                $calendar[] = [
                    'date'       => $dateKey,
                    'day'        => $day,
                    'status'     => 'leave',
                    'label'      => 'Leave',
                    'punch_out'  => '',
                    'punch_in'   => '',
                    'work_hours' => '',
                ];
                $summary['leave']++;
            } elseif ($this->is_week_off($dateKey)) {
                $calendar[] = [
                    'date'       => $dateKey,
                    'day'        => $day,
                    'status'     => 'week_off',
                    'label'      => 'Week Off',
                    'punch_out'  => '',
                    'punch_in'   => '',
                    'work_hours' => '',
                ];
                $summary['week_off']++;
            } elseif ($dateKey > $today) {
                $calendar[] = [
                    'date'       => $dateKey,
                    'day'        => $day,
                    'status'     => 'upcoming',
                    'label'      => 'Upcoming',
                    'punch_in'   => '',
                    'punch_out'  => '',
                    'work_hours' => '',
                ];
                $summary['upcoming']++;
            } else {
                $calendar[] = [
                    'date'       => $dateKey,
                    'day'        => $day,
                    'status'     => 'absent',
                    'label'      => 'Absent',
                    'punch_in'   => '',
                    'punch_out'  => '',
                    'work_hours' => '',
                ];
                $summary['absent']++;
            }
        }

        return [
            'summary'  => $summary,
            'calendar' => $calendar,
        ];
    }

    private function calendar_status_from_attendance($attendance)
    {
        // ── Punch In display ──
        $punch_in = '';
        if (!empty($attendance->punch_in_time)) {
            $punch_in = date('h:i A', strtotime($attendance->punch_in_time));
        }

        // ── Punch Out display ──
        $punch_out = '';
        if (!empty($attendance->punch_out_time)) {
            $punch_out = date('h:i A', strtotime($attendance->punch_out_time));
        }

        // ── Actual minutes calculate from punch times ──
        // Base date prefix  - time-only strings  strtotime safe 
        $actual_minutes = 0;
        if (!empty($attendance->punch_in_time) && !empty($attendance->punch_out_time)) {
            $base      = '2000-01-01 ';
            $in_str    = $attendance->punch_in_time;
            $out_str   = $attendance->punch_out_time;

            // जर already datetime format  (2026-05-11 10:11:00)  prefix 
            $in_ts  = (strpos($in_str,  '-') !== false)
                        ? strtotime($in_str)
                        : strtotime($base . $in_str);
            $out_ts = (strpos($out_str, '-') !== false)
                        ? strtotime($out_str)
                        : strtotime($base . $out_str);

            $diff = $out_ts - $in_ts;
            if ($diff > 0) {
                $actual_minutes = (int) round($diff / 60);
            }
        }

        // ── Work hours string ──
        $work_hours = '';
        if ($actual_minutes > 0) {
            $hours      = floor($actual_minutes / 60);
            $mins       = $actual_minutes % 60;
            $work_hours = $hours . 'h ' . sprintf('%02d', $mins) . 'm';
        }

        // ── Thresholds ──
        $full_day_minutes = 9 * 60; // 540 min = Present
        $half_day_minutes = 4 * 60; // 240 min = Half Day

        // ── Status logic ──
        if (!empty($attendance->punch_in_time) && !empty($attendance->punch_out_time) && $actual_minutes > 0) {
            // Both punch in & out present - minutes  decide 
            if ($actual_minutes >= $full_day_minutes) {
                $cal_status = 'present';
                $label      = 'Present';
            } elseif ($actual_minutes >= $half_day_minutes) {
                $cal_status = 'half_day';
                $label      = 'Half Day';
            } else {
                // < 4 hours - Half Day (business rule   )
                $cal_status = 'half_day';
                $label      = 'Half Day';
            }
        } elseif (!empty($attendance->punch_in_time) && empty($attendance->punch_out_time)) {
            // Punch in , punch out 
            $cal_status = 'present';
            $label      = 'Present';
        } else {
            // Punch data नाही - DB status वर fallback
            $db_status = !empty($attendance->attendance_status)
                ? strtolower(trim($attendance->attendance_status))
                : 'present';

            $status_map = [
                'present'  => ['status' => 'present',  'label' => 'Present'],
                'half_day' => ['status' => 'half_day', 'label' => 'Half Day'],
                'halfday'  => ['status' => 'half_day', 'label' => 'Half Day'],
                'half day' => ['status' => 'half_day', 'label' => 'Half Day'],
                'absent'   => ['status' => 'absent',   'label' => 'Absent'],
                'late'     => ['status' => 'present',  'label' => 'Late'],
                'early'    => ['status' => 'present',  'label' => 'Early Out'],
            ];

            $mapped     = isset($status_map[$db_status])
                            ? $status_map[$db_status]
                            : ['status' => 'present', 'label' => 'Present'];
            $cal_status = $mapped['status'];
            $label      = $mapped['label'];
        }

        return [
            'status'     => $cal_status,
            'label'      => $label,
            'punch_in'   => $punch_in,
            'punch_out'  => $punch_out,
            'work_hours' => $work_hours,
        ];
    }

    private function is_week_off($date)
    {
        $dayOfWeek = (int) date('w', strtotime($date));

        // Sunday -  week off
        if ($dayOfWeek == 0) {
            return true;
        }

        // Saturday - 2nd  4th week off
        if ($dayOfWeek == 6) {
            $dayOfMonth = (int) date('d', strtotime($date));
            $weekNumber = (int) ceil($dayOfMonth / 7);
            if ($weekNumber == 2 || $weekNumber == 4) {
                return true;
            }
        }

        return false;
    }
}