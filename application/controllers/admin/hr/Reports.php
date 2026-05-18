<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends AdminController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        if (!has_permission('hrp_attendance', '', 'view') && !has_permission('hrp_attendance', '', 'view_own')) {
            access_denied('hrp_attendance');
        }

        close_setup_menu();

        $data['title'] = 'Reports';

        $this->load->view('admin/hr/reports/manage', $data);
    }

    public function manage()
    {
        $this->index();
    }
}
