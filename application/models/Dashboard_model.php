<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return array
     * Used in home dashboard page
     * Return all upcoming events this week
     */
    public function get_upcoming_events()
    {
        $monday_this_week = date('Y-m-d', strtotime('monday this week'));
        $sunday_this_week = date('Y-m-d', strtotime('sunday this week'));

        $this->db->where("(start BETWEEN '$monday_this_week' and '$sunday_this_week')");
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');
        $this->db->order_by('start', 'desc');
        $this->db->limit(6);

        return $this->db->get(db_prefix() . 'events')->result_array();
    }

    /**
     * @param  integer (optional) Limit upcoming events
     * @return integer
     * Used in home dashboard page
     * Return total upcoming events next week
     */
    public function get_upcoming_events_next_week()
    {
        $monday_this_week = date('Y-m-d', strtotime('monday next week'));
        $sunday_this_week = date('Y-m-d', strtotime('sunday next week'));
        $this->db->where("(start BETWEEN '$monday_this_week' and '$sunday_this_week')");
        $this->db->where('(userid = ' . get_staff_user_id() . ' OR public = 1)');

        return $this->db->count_all_results(db_prefix() . 'events');
    }

    /**
     * @param  mixed
     * @return array
     * Used in home dashboard page, currency passed from javascript (undefined or integer)
     * Displays weekly payment statistics (chart)
     */
    public function get_weekly_payments_statistics($currency)
    {
        $all_payments                 = [];
        $has_permission_payments_view = staff_can('view',  'payments');
        $this->db->select(db_prefix() . 'invoicepaymentrecords.id, amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where('YEARWEEK(' . db_prefix() . 'invoicepaymentrecords.date) = YEARWEEK(CURRENT_DATE)');
        $this->db->where('' . db_prefix() . 'invoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }

        if (!$has_permission_payments_view) {
            $this->db->where('invoiceid IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE addedfrom=' . get_staff_user_id() . ' and addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature="invoices" AND capability="view_own"))');
        }

        // Current week
        $all_payments[] = $this->db->get()->result_array();
        $this->db->select(db_prefix() . 'invoicepaymentrecords.id, amount,' . db_prefix() . 'invoicepaymentrecords.date');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where('YEARWEEK(' . db_prefix() . 'invoicepaymentrecords.date) = YEARWEEK(CURRENT_DATE - INTERVAL 7 DAY) ');

        $this->db->where('' . db_prefix() . 'invoices.status !=', 5);
        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }

        if (!$has_permission_payments_view) {
            $this->db->where('invoiceid IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE addedfrom=' . get_staff_user_id() . ' and addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature="invoices" AND capability="view_own"))');
        }

        // Last Week
        $all_payments[] = $this->db->get()->result_array();

        $chart = [
            'labels'   => get_weekdays(),
            'datasets' => [
                [
                    'label'           => _l('this_week_payments'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
                [
                    'label'           => _l('last_week_payments'),
                    'backgroundColor' => 'rgba(197, 61, 169, 0.5)',
                    'borderColor'     => '#c53da9',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => [
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                        0,
                    ],
                ],
            ],
        ];


        for ($i = 0; $i < count($all_payments); $i++) {
            foreach ($all_payments[$i] as $payment) {
                $payment_day = date('l', strtotime($payment['date']));
                $x           = 0;
                foreach (get_weekdays_original() as $day) {
                    if ($payment_day == $day) {
                        $chart['datasets'][$i]['data'][$x] += $payment['amount'];
                    }
                    $x++;
                }
            }
        }

        return $chart;
    }


    /**
     * @param  mixed
     * @return array
     * Used in home dashboard page, currency passed from javascript (undefined or integer)
     * Displays monthly payment statistics (chart)
     */
    public function get_monthly_payments_statistics($currency)
    {
        $all_payments                 = [];
        $has_permission_payments_view = staff_can('view',  'payments');
        $this->db->select('SUM(amount) as total, MONTH(' . db_prefix() . 'invoicepaymentrecords.date) as month');
        $this->db->from(db_prefix() . 'invoicepaymentrecords');
        $this->db->join(db_prefix() . 'invoices', '' . db_prefix() . 'invoices.id = ' . db_prefix() . 'invoicepaymentrecords.invoiceid');
        $this->db->where('YEAR(' . db_prefix() . 'invoicepaymentrecords.date) = YEAR(CURRENT_DATE)');
        $this->db->where('' . db_prefix() . 'invoices.status !=', 5);
        $this->db->group_by('month');

        if ($currency != 'undefined') {
            $this->db->where('currency', $currency);
        }

        if (!$has_permission_payments_view) {
            $this->db->where('invoiceid IN (SELECT id FROM ' . db_prefix() . 'invoices WHERE addedfrom=' . get_staff_user_id() . ' and addedfrom IN (SELECT staff_id FROM ' . db_prefix() . 'staff_permissions WHERE feature="invoices" AND capability="view_own"))');
        }

        $all_payments = $this->db->get()->result_array();

        for ($i = 1; $i <= 12; $i++) {
            if (!isset($all_payments[$i])) {
                $all_payments[$i]['total'] = 0;
                $all_payments[$i]['month'] = $i;
            }
            $all_payments[$i]['label'] = _l(date("F", mktime(0, 0, 0, $i, 1)));
        }
        usort($all_payments, function($a, $b) {
            return (int) $a['month'] <=> (int) $b['month'];
        });

        $chart = [
            'labels'   => array_column($all_payments, 'label'),
            'datasets' => [
                [
                    'label'           => _l('report_sales_type_income'),
                    'backgroundColor' => 'rgba(37,155,35,0.2)',
                    'borderColor'     => '#84c529',
                    'borderWidth'     => 1,
                    'tension'         => false,
                    'data'            => array_column($all_payments, 'total'),
                ],
            ],
        ];
        return $chart;
    }

    public function projects_status_stats()
    {
        $this->load->model('projects_model');
        $statuses = $this->projects_model->get_project_statuses();
        $colors   = get_system_favourite_colors();

        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];


        $has_permission = staff_can('view',  'projects');
        $sql            = '';
        foreach ($statuses as $status) {
            $sql .= ' SELECT COUNT(*) as total';
            $sql .= ' FROM ' . db_prefix() . 'projects';
            $sql .= ' WHERE status=' . $status['id'];
            if (!$has_permission) {
                $sql .= ' AND id IN (SELECT project_id FROM ' . db_prefix() . 'project_members WHERE staff_id=' . get_staff_user_id() . ')';
            }
            $sql .= ' UNION ALL ';
            $sql = trim($sql);
        }

        $result = [];
        if ($sql != '') {
            // Remove the last UNION ALL
            $sql    = substr($sql, 0, -10);
            $result = $this->db->query($sql)->result();
        }

        foreach ($statuses as $key => $status) {
            array_push($_data['statusLink'], admin_url('projects?status=' . $status['id']));
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'], $result[$key]->total);
        }

        $chart['datasets'][]           = $_data;
        $chart['datasets'][0]['label'] = _l('home_stats_by_project_status');

        return $chart;
    }

    public function leads_status_stats()
    {
        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];

        $result = get_leads_summary();

        foreach ($result as $status) {
            if ($status['color'] == '') {
                $status['color'] = '#737373';
            }
            array_push($chart['labels'], $status['name']);
            array_push($_data['backgroundColor'], $status['color']);
            if (!isset($status['junk']) && !isset($status['lost'])) {
                array_push($_data['statusLink'], admin_url('leads?status=' . $status['id']));
            }
            array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['color'], -20));
            array_push($_data['data'], $status['total']);
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    /**
     * Display total tickets awaiting reply by department (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_department()
    {
        $this->load->model('departments_model');
        $departments = $this->departments_model->get();
        $colors      = get_system_favourite_colors();
        $chart       = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];

        $i = 0;
        foreach ($departments as $department) {
            if (!is_admin()) {
                if (get_option('staff_access_only_assigned_departments') == 1) {
                    $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                    $departments_ids      = [];
                    if (count($staff_deparments_ids) == 0) {
                        $departments = $this->departments_model->get();
                        foreach ($departments as $department) {
                            array_push($departments_ids, $department['departmentid']);
                        }
                    } else {
                        $departments_ids = $staff_deparments_ids;
                    }
                    if (count($departments_ids) > 0) {
                        $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                    }
                }
            }
            $this->db->where_in('status', [
                1,
                2,
                4,
            ]);

            $this->db->where('department', $department['departmentid']);
            $this->db->where(db_prefix() . 'tickets.merged_ticket_id IS NULL', null, false);
            $total = $this->db->count_all_results(db_prefix() . 'tickets');

            if ($total > 0) {
                $color = '#333';
                if (isset($colors[$i])) {
                    $color = $colors[$i];
                }
                array_push($chart['labels'], $department['name']);
                array_push($_data['backgroundColor'], $color);
                array_push($_data['hoverBackgroundColor'], adjust_color_brightness($color, -20));
                array_push($_data['data'], $total);
            }
            $i++;
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    /**
     * Display total tickets awaiting reply by status (chart)
     * @return array
     */
    public function tickets_awaiting_reply_by_status()
    {
        $this->load->model('tickets_model');
        $statuses             = $this->tickets_model->get_ticket_status();
        $_statuses_with_reply = [
            1,
            2,
            4,
        ];

        $chart = [
            'labels'   => [],
            'datasets' => [],
        ];

        $_data                         = [];
        $_data['data']                 = [];
        $_data['backgroundColor']      = [];
        $_data['hoverBackgroundColor'] = [];
        $_data['statusLink']           = [];

        foreach ($statuses as $status) {
            if (in_array($status['ticketstatusid'], $_statuses_with_reply)) {
                if (!is_admin()) {
                    if (get_option('staff_access_only_assigned_departments') == 1) {
                        $staff_deparments_ids = $this->departments_model->get_staff_departments(get_staff_user_id(), true);
                        $departments_ids      = [];
                        if (count($staff_deparments_ids) == 0) {
                            $departments = $this->departments_model->get();
                            foreach ($departments as $department) {
                                array_push($departments_ids, $department['departmentid']);
                            }
                        } else {
                            $departments_ids = $staff_deparments_ids;
                        }
                        if (count($departments_ids) > 0) {
                            $this->db->where('department IN (SELECT departmentid FROM ' . db_prefix() . 'staff_departments WHERE departmentid IN (' . implode(',', $departments_ids) . ') AND staffid="' . get_staff_user_id() . '")');
                        }
                    }
                }

                $this->db->where('status', $status['ticketstatusid']);
                $this->db->where(db_prefix() . 'tickets.merged_ticket_id IS NULL', null, false);
                $total = $this->db->count_all_results(db_prefix() . 'tickets');
                if ($total > 0) {
                    array_push($chart['labels'], ticket_status_translate($status['ticketstatusid']));
                    array_push($_data['statusLink'], admin_url('tickets/index/' . $status['ticketstatusid']));
                    array_push($_data['backgroundColor'], $status['statuscolor']);
                    array_push($_data['hoverBackgroundColor'], adjust_color_brightness($status['statuscolor'], -20));
                    array_push($_data['data'], $total);
                }
            }
        }

        $chart['datasets'][] = $_data;

        return $chart;
    }

    public function get_calendar_view($staff_id, $month)
    {
        $year = date('Y', strtotime($month));
        $monthNumber = date('m', strtotime($month));
        $daysInMonth = date('t', strtotime($month));

        /*
        |--------------------------------------------------------------------------
        | Get Attendance
        |--------------------------------------------------------------------------
        */
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $attendanceRows = $this->db
            ->where('staff_id', $staff_id)
            ->where('YEAR(attendance_date)', $year, false)
            ->where('MONTH(attendance_date)', $monthNumber, false)
            ->get('attendance_daily')
            ->result();
        $this->db->dbprefix = $oldPrefix;
            
        $attendances = [];
        foreach ($attendanceRows as $row) {
            $dateKey = date('Y-m-d', strtotime($row->attendance_date));
            $attendances[$dateKey] = $row;
        }

        /*
        |--------------------------------------------------------------------------
        | Get Requests
        |--------------------------------------------------------------------------
        */
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $requestRows = $this->db
            ->select('r.*, t.name as request_type_name')
            ->from('hrm_employee_requests r')
            ->join('hrm_request_types t', 't.id = r.request_type_id', 'left')
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
                $dateKey = date('Y-m-d', $startDate);

                $requests[$dateKey][] = $requestItem;

                $startDate = strtotime('+1 day', $startDate);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Build Calendar
        |--------------------------------------------------------------------------
        */
        $calendarData = [];
        $summary = [
            'present'  => 0,
            'absent'   => 0,
            'half_day' => 0,
            'leave'    => 0,
            'week_off' => 0,
            'upcoming' => 0
        ];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateKey = date('Y-m-d', strtotime($year . '-' . $monthNumber . '-' . $day));

            if (isset($attendances[$dateKey])) {
                $statusData = $this->calendar_status_from_attendance($attendances[$dateKey]);

                $calendarData[] = array_merge([
                    'date' => $dateKey,
                    'type' => 'attendance'
                ], $statusData);

                if ($statusData['status'] == 'half_day') {
                    $summary['half_day']++;
                } else {
                    $summary['present']++;
                }

            } elseif (isset($requests[$dateKey])) {
                foreach ($requests[$dateKey] as $requestItem) {
                    $calendarData[] = [
                        'date' => $dateKey,
                        'type' => 'request',
                        'request_id' => $requestItem->id,
                        'request_type' => $requestItem->request_type_name,
                        'status' => 'leave'
                    ];
                }

                $summary['leave']++;

            } elseif ($this->is_week_off($dateKey)) {
                $calendarData[] = [
                    'date' => $dateKey,
                    'type' => 'holiday',
                    'label' => 'WO',
                    'holiday_name' => 'Week Off'
                ];

                $summary['week_off']++;

            } elseif ($dateKey > date('Y-m-d')) {
                $calendarData[] = [
                    'date' => $dateKey,
                    'type' => 'upcoming'
                ];

                $summary['upcoming']++;

            } else {
                $calendarData[] = [
                    'date' => $dateKey,
                    'type' => 'absent'
                ];

                $summary['absent']++;
            }
        }

        return [
            'summary' => $summary,
            'calendar' => $calendarData
        ];
    }

    private function calendar_status_from_attendance($attendance)
    {
        if ($attendance->attendance_status == 'half_day') {
            return [
                'status' => 'half_day',
                'label' => 'HD'
            ];
        }

        return [
            'status' => 'present',
            'label' => 'P'
        ];
    }

    private function is_week_off($date)
    {
        $dayOfWeek = date('w', strtotime($date)); // 0 = Sunday
        $day = date('j', strtotime($date));

        if ($dayOfWeek == 0) {
            return true;
        }

        if ($dayOfWeek == 6) {
            $weekNumber = ceil($day / 7);

            if (in_array($weekNumber, [2, 4])) {
                return true;
            }
        }

        return false;
    }

    public function get_day_status($staff_id, $selectedDate = null)
    {
        $selectedDate = !empty($selectedDate)
            ? date('Y-m-d', strtotime($selectedDate))
            : date('Y-m-d');

        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';

        try {
            
            $attendance = $this->db
                ->where('staff_id', $staff_id)
                ->where('attendance_date', $selectedDate)
                ->limit(1)
                ->get('attendance_daily')
                ->row_array();

            if (empty($attendance)) {
                $attendance = [
                    'attendance_status' => null,
                    'punch_in_time' => null,
                    'punch_out_time' => null,
                    'total_work_minutes' => null,
                ];
            }

            if (isset($attendance['total_work_minutes']) && $attendance['total_work_minutes'] == 0 && ! empty($attendance['attendance_status']) && ! empty($attendance['punch_in_time'])) {
                // calculate time from punch in HH:mm
                $punchInTime = strtotime($attendance['punch_in_time']);
                if ($punchInTime !== false) {
                    $currentTime = time();
                    $totalWorkMinutes = round(($currentTime - $punchInTime) / 60);
                    $hours = floor($totalWorkMinutes / 60);
                    $minutes = $totalWorkMinutes % 60;
                    if ($hours > 12) {
                        $hours = 12;
                        $minutes = 0;
                    }
                    $attendance['total_work_minutes'] = sprintf('%02d Hr %02d Min', $hours, $minutes);
                } else {
                    $attendance['total_work_minutes'] = '-';
                }
            } elseif (! empty($attendance['total_work_minutes']) || $attendance['total_work_minutes'] === 0) {
                $hours = floor($attendance['total_work_minutes'] / 60);
                $minutes = $attendance['total_work_minutes'] % 60;
                $attendance['total_work_minutes'] = sprintf('%02d Hr %02d Min', $hours, $minutes);
            } else {
                $attendance['total_work_minutes'] = '-';
            }
            return [
                'status' => true,
                'message' => 'Day status fetched successfully.',
                'data' => [
                    // 'attendance' => $attendance,
                    'staff_id' => $staff_id,
                    'date' => $selectedDate,
                    'display_date' => date('D, d M Y', strtotime($selectedDate)),
                    'attendance_status' => $attendance['attendance_status'] ?? 'absent',
                    'punch_in_time' => (!empty($attendance['punch_in_time']) ? date('H:i:s', strtotime($attendance['punch_in_time'])) : '-'),
                    'punch_out_time' => (!empty($attendance['punch_out_time']) ? date('H:i:s', strtotime($attendance['punch_out_time'])) : '-'),
                    'total_work_minutes' => $attendance['total_work_minutes'] ?? '-',
                    // 'is_break_active' => !empty($activeBreak),
                    // 'attendance_status' => $attendance['attendance_status'] ?? null,
                    // 'attendance' => $attendance,
                    // 'active_break' => $activeBreak,
                    // 'leave_request' => $leaveRequest,
                    // 'request_type' => $leaveRequest['request_type_name'] ?? null
                ]
            ];

        } catch (Exception $e) {
            return [
                'status' => false,
                'message' => 'Something went wrong.',
                'error' => $e->getMessage()
            ];
        } finally {
            $this->db->dbprefix = $oldPrefix;
        }
    }
}
