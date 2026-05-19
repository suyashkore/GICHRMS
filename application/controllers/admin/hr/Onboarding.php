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
            // Prepare form data
            $data = [
                // Section 1: Personal Details
                'full_name'              => $this->input->post('full_name'),
                'parent_name'            => $this->input->post('parent_name'),
                'dob'                    => $this->input->post('dob'),
                'gender'                 => $this->input->post('gender'),
                'marital_status'         => $this->input->post('marital_status'),
                'blood_group'            => $this->input->post('blood_group'),
                'nationality'            => $this->input->post('nationality'),
                'mobile_number'          => $this->input->post('mobile_number'),
                'personal_email'         => $this->input->post('personal_email'),
                'current_address'        => $this->input->post('current_address'),
                'permanent_address'      => $this->input->post('permanent_address'),
                
                // Section 2: Identity & KYC Details
                'aadhaar_number'         => $this->input->post('aadhaar_number'),
                'pan_number'             => $this->input->post('pan_number'),
                'passport_number'        => $this->input->post('passport_number'),
                'dl_number'              => $this->input->post('dl_number'),
                'uan_number'             => $this->input->post('uan_number'),
                'esic_number'            => $this->input->post('esic_number'),
                
                // Section 3: Employment Details
                'employee_id'            => $this->input->post('employee_id'),
                'designation'            => $this->input->post('designation'),
                'department'             => $this->input->post('department'),
                'joining_date'           => $this->input->post('joining_date'),
                'reporting_manager'      => $this->input->post('reporting_manager'),
                'employment_type'        => $this->input->post('employment_type'),
                'work_location'          => $this->input->post('work_location'),
                
                // Section 4: Educational Qualification
                'edu_10th_institute'     => $this->input->post('edu_10th_institute'),
                'edu_10th_year'          => $this->input->post('edu_10th_year'),
                'edu_10th_percentage'    => $this->input->post('edu_10th_percentage'),
                'edu_12th_institute'     => $this->input->post('edu_12th_institute'),
                'edu_12th_year'          => $this->input->post('edu_12th_year'),
                'edu_12th_percentage'    => $this->input->post('edu_12th_percentage'),
                'edu_graduation_institute'    => $this->input->post('edu_graduation_institute'),
                'edu_graduation_year'         => $this->input->post('edu_graduation_year'),
                'edu_graduation_percentage'   => $this->input->post('edu_graduation_percentage'),
                'edu_postgraduation_institute' => $this->input->post('edu_postgraduation_institute'),
                'edu_postgraduation_year'      => $this->input->post('edu_postgraduation_year'),
                'edu_postgraduation_percentage' => $this->input->post('edu_postgraduation_percentage'),
                
                // Section 5: Previous Employment Details
                'total_experience'       => $this->input->post('total_experience'),
                'last_company'           => $this->input->post('last_company'),
                'last_designation'       => $this->input->post('last_designation'),
                'last_ctc'               => $this->input->post('last_ctc'),
                'notice_period'          => $this->input->post('notice_period'),
                
                // New JSON sections for multiple entries
                'education_details'      => null,
                'employment_history'     => null,
                
                // Section 6: Bank Details
                'bank_name'              => $this->input->post('bank_name'),
                'account_holder'         => $this->input->post('account_holder'),
                'account_number'         => $this->input->post('account_number'),
                'ifsc_code'              => $this->input->post('ifsc_code'),
                'branch_name'            => $this->input->post('branch_name'),
                
                // Section 7: Emergency Contact Details
                'emergency_contact_name' => $this->input->post('emergency_contact_name'),
                'emergency_relationship' => $this->input->post('emergency_relationship'),
                'emergency_mobile'       => $this->input->post('emergency_mobile'),
                
                // Section 8: Statutory Information
                'pf_applicable'          => $this->input->post('pf_applicable'),
                'pf_uan'                 => $this->input->post('pf_uan'),
                'esic_applicable'        => $this->input->post('esic_applicable'),
                'nominee_name'           => $this->input->post('nominee_name'),
                'nominee_relationship'   => $this->input->post('nominee_relationship'),
                
                // Section 10: Declaration
                'employee_signature'     => $this->input->post('employee_signature'),
                'declaration_date'       => $this->input->post('declaration_date'),
                'declaration_place'      => $this->input->post('declaration_place'),
                
                // System fields
                'created_by'             => get_staff_user_id(),
                'created_date'           => date('Y-m-d H:i:s'),
                'status'                 => 'pending',
            ];

            $education_entries = $this->input->post('education');
            if (!empty($education_entries) && is_array($education_entries)) {
                $data['education_details'] = json_encode(array_values($education_entries));
                foreach ($education_entries as $row) {
                    $qualification = strtolower(trim($row['qualification'] ?? ''));
                    if (strpos($qualification, '10') !== false || strpos($qualification, 'ssc') !== false) {
                        $data['edu_10th_institute'] = $row['institute'] ?? $data['edu_10th_institute'];
                        $data['edu_10th_year'] = $row['year'] ?? $data['edu_10th_year'];
                        $data['edu_10th_percentage'] = $row['percentage'] ?? $data['edu_10th_percentage'];
                    } elseif (strpos($qualification, '12') !== false || strpos($qualification, 'hsc') !== false) {
                        $data['edu_12th_institute'] = $row['institute'] ?? $data['edu_12th_institute'];
                        $data['edu_12th_year'] = $row['year'] ?? $data['edu_12th_year'];
                        $data['edu_12th_percentage'] = $row['percentage'] ?? $data['edu_12th_percentage'];
                    } elseif (strpos($qualification, 'post') !== false) {
                        $data['edu_postgraduation_institute'] = $row['institute'] ?? $data['edu_postgraduation_institute'];
                        $data['edu_postgraduation_year'] = $row['year'] ?? $data['edu_postgraduation_year'];
                        $data['edu_postgraduation_percentage'] = $row['percentage'] ?? $data['edu_postgraduation_percentage'];
                    } elseif (strpos($qualification, 'grad') !== false) {
                        $data['edu_graduation_institute'] = $row['institute'] ?? $data['edu_graduation_institute'];
                        $data['edu_graduation_year'] = $row['year'] ?? $data['edu_graduation_year'];
                        $data['edu_graduation_percentage'] = $row['percentage'] ?? $data['edu_graduation_percentage'];
                    }
                }
            }

            $employment_entries = $this->input->post('employment_history');
            if (!empty($employment_entries) && is_array($employment_entries)) {
                $formatted_employment = [];
                foreach ($employment_entries as $row) {
                    if (!empty($row['start_date'])) {
                        $row['start_date'] = $this->_convert_date_to_mysql($row['start_date']);
                    }
                    if (!empty($row['end_date'])) {
                        $row['end_date'] = $this->_convert_date_to_mysql($row['end_date']);
                    }
                    $formatted_employment[] = $row;
                }
                $data['employment_history'] = json_encode(array_values($formatted_employment));
                $last_entry = end($formatted_employment);
                if (!empty($last_entry)) {
                    $data['last_company'] = $last_entry['company'] ?? $data['last_company'];
                    $data['last_designation'] = $last_entry['designation'] ?? $data['last_designation'];
                    $data['last_ctc'] = $last_entry['ctc'] ?? $data['last_ctc'];
                    $data['notice_period'] = $last_entry['notice_period'] ?? $data['notice_period'];
                }
            }

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
                $this->_ensure_onboarding_json_columns();
                $insert_id = $this->hr_onboarding_model->add_onboarding($data);

                if ($insert_id) {
                    // Handle file uploads
                    $this->_handle_document_uploads($insert_id);
                    
                    set_alert('success', 'Onboarding form submitted successfully!');
                    redirect(admin_url('hr/onboarding'));
                } else {
                    set_alert('danger', 'Failed to submit onboarding form.');
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

    private function _ensure_onboarding_json_columns()
    {
        $oldPrefix = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $fields = $this->db->list_fields('hrm_onboarding');

        if (!in_array('education_details', $fields)) {
            $this->db->query('ALTER TABLE `hrm_onboarding` ADD COLUMN `education_details` JSON NULL AFTER `edu_postgraduation_percentage`;');
        }

        if (!in_array('employment_history', $fields)) {
            $this->db->query('ALTER TABLE `hrm_onboarding` ADD COLUMN `employment_history` JSON NULL AFTER `education_details`;');
        }

        $this->db->dbprefix = $oldPrefix;
    }

    private function _convert_date_to_mysql($date)
    {
        if (empty($date)) {
            return $date;
        }

        if (strpos($date, '/') !== false) {
            $parts = explode('/', $date);
            if (count($parts) === 3) {
                $day = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $month = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                $year = $parts[2];
                if (strlen($year) === 2) {
                    $year = '20' . $year;
                }
                return $year . '-' . $month . '-' . $day;
            }
        }

        return $date;
    }

    /**
     * Handle document uploads for onboarding
     */
    private function _handle_document_uploads($onboarding_id)
    {
        // Define acceptable file types
        $allowed_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'doc', 'docx'];
        $max_file_size = 5 * 1024 * 1024; // 5 MB

        // Create upload directory
        $upload_base_dir = APPPATH . '../uploads/onboarding/' . $onboarding_id;
        if (!is_dir($upload_base_dir)) {
            mkdir($upload_base_dir, 0755, true);
        }

        // Document fields mapping
        $document_fields = [
            'doc_resume' => 'Resume-CV',
            'doc_passport_photo' => 'Passport-Photo',
            'doc_aadhaar' => 'Aadhaar-Card',
            'doc_pan' => 'PAN-Card',
            'doc_bank' => 'Bank-Passbook',
            'doc_education' => 'Education-Certificates',
            'doc_experience' => 'Experience-Letters',
            'doc_salary' => 'Salary-Slips',
            'doc_relieving' => 'Relieving-Letter',
            'doc_passport' => 'Passport',
            'doc_driving_license' => 'Driving-License'
        ];

        $uploaded_documents = [];

        // Process each document field
        foreach ($document_fields as $field_name => $document_label) {
            if (!empty($_FILES[$field_name]['name'])) {
                $file = $_FILES[$field_name];
                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];

                // Get file extension
                $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

                // Validate file
                if ($file_error === UPLOAD_ERR_OK && $file_size > 0 && $file_size <= $max_file_size && in_array($file_ext, $allowed_extensions)) {
                    // Generate unique file name
                    $new_file_name = $document_label . '-' . time() . '-' . rand(1000, 9999) . '.' . $file_ext;
                    $upload_path = $upload_base_dir . '/' . $new_file_name;

                    // Move uploaded file
                    if (move_uploaded_file($file_tmp, $upload_path)) {
                        $uploaded_documents[$field_name] = 'uploads/onboarding/' . $onboarding_id . '/' . $new_file_name;
                    }
                }
            }
        }

        // Store uploaded documents as JSON
        if (!empty($uploaded_documents)) {
            $documents_json = json_encode($uploaded_documents);
            $this->hr_onboarding_model->update_onboarding($onboarding_id, ['documents' => $documents_json]);
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
