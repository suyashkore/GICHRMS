<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Assets_management extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('expenses_model');
    }

    public function index()
    {
        if (!has_permission('hrp_assets_management', '', 'view')) {
            access_denied('hrp_assets_management');
        }

        $data['title'] = _l('assets_management');
        $this->load->view('admin/hr/assets_management/manage', $data);
    }

    public function add()
    {
        if (!has_permission('hrp_assets_management', '', 'create')) {
            access_denied('hrp_assets_management');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            redirect(admin_url('hr/assets_management'));
        }

        $data['title'] = _l('add_asset');
        $this->load->view('admin/hr/assets_management/add', $data);
    }
}
