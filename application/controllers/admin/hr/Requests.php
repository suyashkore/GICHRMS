<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Requests extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hr_requests_model');
    }

    public function index()
    {
        if (!has_permission('hrp_requests', '', 'view') && !has_permission('hrp_requests', '', 'view_own')) {
            access_denied('hrp_requests');
        }

        close_setup_menu();

        $month = $this->input->get('month');
        $data['current_month'] = !empty($month) ? date('Y-m', strtotime($month . '-01')) : date('Y-m');
        $data['month_name'] = date('F Y', strtotime($data['current_month'] . '-01'));
        $data['calendar_data'] = $this->hr_requests_model->get_calendar_data(get_staff_user_id(), $data['current_month']);
        // echo json_encode($data);exit;
        $data['title'] = 'Requests';

        $this->load->view('admin/hr/requests/manage', $data);
    }

    public function load_leave_forms()
    {
        $data['leave_types'] = $this->hr_requests_model->get_active_leave_types();
        $this->load->view('admin/hr/requests/leave_forms', $data);
    }

    public function load_attendance_forms()
    {
        $this->load->view('admin/hr/requests/attendance_forms');
    }

    public function load_report_forms()
    {
        $this->load->view('admin/hr/requests/report_forms');
    }

    public function load_expense_form()
    {
        $this->load->view('admin/hr/requests/expense_form');
    }

    public function load_assets_forms()
    {
        $this->load->view('admin/hr/requests/asset_forms');
    }

    public function load_onboarding_forms()
    {
        $this->load->view('admin/hr/requests/onboarding_forms');
    }

    public function load_offboarding_forms()
    {
        $this->load->view('admin/hr/requests/offboarding_forms');
    }
}