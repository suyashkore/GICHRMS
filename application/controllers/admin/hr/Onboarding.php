<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Onboarding extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hr_onboarding_model');
    }

    public function index()
    {
        // Allow access if user is authenticated
        close_setup_menu();

        $data['title'] = 'Onboarding';
        
        // Get all onboarding records if table exists
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $table_exists = $this->db->query("SHOW TABLES LIKE 'hrm_onboarding'")->num_rows() > 0;
        $this->db->dbprefix = $oldPrefix;
        
        if ($table_exists) {
            $data['onboarding_records'] = $this->hr_onboarding_model->get_all_onboarding();
        } else {
            $data['onboarding_records'] = [];
        }

        $this->load->view('admin/hr/onboarding/manage', $data);
    }

    public function view($id = null)
    {
        if (!has_permission('hrp_onboarding', '', 'view') && !has_permission('hrp_onboarding', '', 'view_own')) {
            access_denied('hrp_onboarding');
        }

        if (!$id) {
            redirect(admin_url('hr/onboarding'));
        }

        close_setup_menu();

        $data['title'] = 'Onboarding Details';
        $data['onboarding'] = $this->hr_onboarding_model->get_onboarding_by_id($id);

        if (!$data['onboarding']) {
            redirect(admin_url('hr/onboarding'));
        }

        $this->load->view('admin/hr/onboarding/view', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = [
                'candidate_name'      => $this->input->post('candidate_name'),
                'proposed_ctc'        => $this->input->post('proposed_ctc'),
                'joining_date'        => $this->input->post('joining_date'),
                'department'          => $this->input->post('department'),
                'approval_notes'      => $this->input->post('approval_notes'),
                'created_by'          => get_staff_user_id(),
                'created_date'        => date('Y-m-d H:i:s'),
                'status'              => 'pending',
            ];

            // Check if table exists, if not create it
            $oldPrefix = $this->db->dbprefix;
            $this->db->dbprefix = '';
            $table_exists = $this->db->query("SHOW TABLES LIKE 'hrm_onboarding'")->num_rows() > 0;
            $this->db->dbprefix = $oldPrefix;

            if (!$table_exists) {
                // Run migration to create table
                $this->load->library('migration');
                if ($this->migration->version(322)) {
                    $table_exists = true;
                }
            }

            if ($table_exists) {
                $insert_id = $this->hr_onboarding_model->add_onboarding($data);

                if ($insert_id) {
                    set_alert('success', 'Onboarding request submitted successfully!');
                    redirect(admin_url('hr/onboarding'));
                } else {
                    set_alert('danger', 'Failed to submit onboarding request.');
                    redirect(admin_url('hr/onboarding/add'));
                }
            } else {
                set_alert('danger', 'Database table not available. Please contact administrator.');
                redirect(admin_url('hr/onboarding/add'));
            }
        } else {
            close_setup_menu();

            $data['title'] = 'Add Onboarding';
            $this->load->view('admin/hr/onboarding/add', $data);
        }
    }

    public function edit($id = null)
    {
        if (!has_permission('hrp_onboarding', '', 'edit')) {
            access_denied('hrp_onboarding');
        }

        if (!$id) {
            redirect(admin_url('hr/onboarding'));
        }

        $onboarding = $this->hr_onboarding_model->get_onboarding_by_id($id);

        if (!$onboarding) {
            redirect(admin_url('hr/onboarding'));
        }

        if ($this->input->post()) {
            $data = [
                'candidate_name'      => $this->input->post('candidate_name'),
                'proposed_ctc'        => $this->input->post('proposed_ctc'),
                'joining_date'        => $this->input->post('joining_date'),
                'department'          => $this->input->post('department'),
                'approval_notes'      => $this->input->post('approval_notes'),
                'updated_by'          => get_staff_user_id(),
                'updated_date'        => date('Y-m-d H:i:s'),
            ];

            $update = $this->hr_onboarding_model->update_onboarding($id, $data);

            if ($update) {
                set_alert('success', 'Onboarding request updated successfully!');
                redirect(admin_url('hr/onboarding'));
            } else {
                set_alert('danger', 'Failed to update onboarding request.');
            }
        }

        close_setup_menu();

        $data['title'] = 'Edit Onboarding';
        $data['onboarding'] = $onboarding;
        $this->load->view('admin/hr/onboarding/edit', $data);
    }

    public function delete($id = null)
    {
        if (!has_permission('hrp_onboarding', '', 'delete')) {
            access_denied('hrp_onboarding');
        }

        if (!$id) {
            redirect(admin_url('hr/onboarding'));
        }

        $delete = $this->hr_onboarding_model->delete_onboarding($id);

        if ($delete) {
            set_alert('success', 'Onboarding request deleted successfully!');
        } else {
            set_alert('danger', 'Failed to delete onboarding request.');
        }

        redirect(admin_url('hr/onboarding'));
    }

    public function approve($id = null)
    {
        if (!has_permission('hrp_onboarding', '', 'edit')) {
            access_denied('hrp_onboarding');
        }

        if (!$id) {
            redirect(admin_url('hr/onboarding'));
        }

        $data = [
            'status'       => 'approved',
            'approved_by'  => get_staff_user_id(),
            'approved_date' => date('Y-m-d H:i:s'),
        ];

        $update = $this->hr_onboarding_model->update_onboarding($id, $data);

        if ($update) {
            set_alert('success', 'Onboarding request approved!');
        } else {
            set_alert('danger', 'Failed to approve onboarding request.');
        }

        redirect(admin_url('hr/onboarding'));
    }

    public function reject($id = null)
    {
        if (!has_permission('hrp_onboarding', '', 'edit')) {
            access_denied('hrp_onboarding');
        }

        if (!$id) {
            redirect(admin_url('hr/onboarding'));
        }

        $data = [
            'status'       => 'rejected',
            'rejected_by'  => get_staff_user_id(),
            'rejected_date' => date('Y-m-d H:i:s'),
        ];

        $update = $this->hr_onboarding_model->update_onboarding($id, $data);

        if ($update) {
            set_alert('success', 'Onboarding request rejected!');
        } else {
            set_alert('danger', 'Failed to reject onboarding request.');
        }

        redirect(admin_url('hr/onboarding'));
    }
}
