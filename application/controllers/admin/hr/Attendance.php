<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Attendance extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hr_attendance_model');
    }

    public function index()
    {
        if (!has_permission('hrp_attendance', '', 'view') && !has_permission('hrp_attendance', '', 'view_own')) {
            access_denied('hrp_attendance');
        }

        close_setup_menu();

        $month = $this->input->get('month');
        $data['current_month'] = !empty($month) ? date('Y-m', strtotime($month . '-01')) : date('Y-m');
        $data['month_name'] = date('F Y', strtotime($data['current_month'] . '-01'));
        $data['calendar_data'] = $this->hr_attendance_model->get_calendar_data(get_staff_user_id(), $data['current_month']);
        // echo json_encode($data);exit;
        $data['title'] = 'Attendance';

        $this->load->view('admin/hr/attendance/manage', $data);
    }

    public function manage()
    {
        $this->index();
    }
}
