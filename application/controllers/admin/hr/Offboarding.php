<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Offboarding extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('hr_offboarding_model');
    }

    public function index()
    {
        // Allow access if user is authenticated
        close_setup_menu();

        $data['title'] = 'Offboarding';

        // Get all offboarding records if table exists
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';
        $table_exists = $this->db->query("SHOW TABLES LIKE 'hrm_offboarding'")->num_rows() > 0;
        $this->db->dbprefix = $oldPrefix;

        if ($table_exists) {
            $data['offboarding_records'] = $this->hr_offboarding_model->get_all_offboarding();
        } else {
            $data['offboarding_records'] = [];
        }

        $this->load->view('admin/hr/offboarding/manage', $data);
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = [
                'employee_name'       => $this->input->post('employee_name'),
                'employee_id'         => $this->input->post('employee_id'),
                'department'          => $this->input->post('department'),
                'designation'         => $this->input->post('designation'),
                'resignation_date'    => $this->input->post('resignation_date'),
                'last_working_date'   => $this->input->post('last_working_date'),
                'reason'              => $this->input->post('reason'),
                'comments'            => $this->input->post('comments'),
                'created_by'          => get_staff_user_id(),
                'created_date'        => date('Y-m-d H:i:s'),
                'status'              => 'pending',
            ];

            // Check if table exists, if not create it
            $oldPrefix = $this->db->dbprefix;
            $this->db->dbprefix = '';
            $table_exists = $this->db->query("SHOW TABLES LIKE 'hrm_offboarding'")->num_rows() > 0;
            $this->db->dbprefix = $oldPrefix;

            if (!$table_exists) {
                // Run migration to create table
                $this->load->library('migration');
                if ($this->migration->version(323)) {
                    $table_exists = true;
                }
            }

            if ($table_exists) {
                $insert_id = $this->hr_offboarding_model->add_offboarding($data);

                if ($insert_id) {
                    set_alert('success', 'Resignation request submitted successfully!');
                    redirect(admin_url('hr/offboarding'));
                } else {
                    set_alert('danger', 'Failed to submit resignation request.');
                    redirect(admin_url('hr/offboarding/add'));
                }
            } else {
                set_alert('danger', 'Database table not available. Please contact administrator.');
                redirect(admin_url('hr/offboarding/add'));
            }
        } else {
            close_setup_menu();

            $data['title'] = 'Submit Resignation';
            $this->load->view('admin/hr/offboarding/add', $data);
        }
    }

    public function view($id = null)
    {
        if (!$id) {
            redirect(admin_url('hr/offboarding'));
        }

        close_setup_menu();

        $data['title'] = 'Resignation Details';
        $data['offboarding'] = $this->hr_offboarding_model->get_offboarding_by_id($id);

        if (!$data['offboarding']) {
            redirect(admin_url('hr/offboarding'));
        }

        $this->load->view('admin/hr/offboarding/view', $data);
    }

    public function edit($id = null)
    {
        if (!$id) {
            redirect(admin_url('hr/offboarding'));
        }

        $offboarding = $this->hr_offboarding_model->get_offboarding_by_id($id);

        if (!$offboarding) {
            redirect(admin_url('hr/offboarding'));
        }

        if ($this->input->post()) {
            $data = [
                'employee_name'       => $this->input->post('employee_name'),
                'employee_id'         => $this->input->post('employee_id'),
                'department'          => $this->input->post('department'),
                'designation'         => $this->input->post('designation'),
                'resignation_date'    => $this->input->post('resignation_date'),
                'last_working_date'   => $this->input->post('last_working_date'),
                'reason'              => $this->input->post('reason'),
                'comments'            => $this->input->post('comments'),
                'updated_by'          => get_staff_user_id(),
                'updated_date'        => date('Y-m-d H:i:s'),
            ];

            $update = $this->hr_offboarding_model->update_offboarding($id, $data);

            if ($update) {
                set_alert('success', 'Resignation request updated successfully!');
                redirect(admin_url('hr/offboarding'));
            } else {
                set_alert('danger', 'Failed to update resignation request.');
            }
        }

        close_setup_menu();

        $data['title'] = 'Edit Resignation';
        $data['offboarding'] = $offboarding;
        $this->load->view('admin/hr/offboarding/edit', $data);
    }

    public function delete($id = null)
    {
        if (!$id) {
            redirect(admin_url('hr/offboarding'));
        }

        $delete = $this->hr_offboarding_model->delete_offboarding($id);

        if ($delete) {
            set_alert('success', 'Resignation request deleted successfully!');
        } else {
            set_alert('danger', 'Failed to delete resignation request.');
        }

        redirect(admin_url('hr/offboarding'));
    }

    public function approve($id = null)
    {
        if (!$id) {
            redirect(admin_url('hr/offboarding'));
        }

        $data = [
            'status'         => 'approved',
            'approved_by'    => get_staff_user_id(),
            'approved_date'  => date('Y-m-d H:i:s'),
        ];

        $update = $this->hr_offboarding_model->update_offboarding($id, $data);

        if ($update) {
            set_alert('success', 'Resignation request approved!');
        } else {
            set_alert('danger', 'Failed to approve resignation request.');
        }

        redirect(admin_url('hr/offboarding'));
    }

    public function reject($id = null)
    {
        if (!$id) {
            redirect(admin_url('hr/offboarding'));
        }

        $data = [
            'status'         => 'rejected',
            'rejected_by'    => get_staff_user_id(),
            'rejected_date'  => date('Y-m-d H:i:s'),
        ];

        $update = $this->hr_offboarding_model->update_offboarding($id, $data);

        if ($update) {
            set_alert('success', 'Resignation request rejected!');
        } else {
            set_alert('danger', 'Failed to reject resignation request.');
        }

        redirect(admin_url('hr/offboarding'));
    }
}
