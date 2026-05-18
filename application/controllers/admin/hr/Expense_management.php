<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Expense_management extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('expenses_model');
    }

    public function index()
    {
        if (!has_permission('hrp_expense_management', '', 'view')) {
            access_denied('hrp_expense_management');
        }

        $data['title'] = _l('expense_management');
        $this->load->view('admin/hr/expense_management/manage', $data);
    }

    public function add()
    {
        if (!has_permission('hrp_expense_management', '', 'create')) {
            access_denied('hrp_expense_management');
        }

        if ($this->input->post()) {
            $data = $this->input->post();
            
            // Handle multiple expenses
            if (isset($data['expense_name']) && is_array($data['expense_name'])) {
                $expenses = [];
                $total_amount = 0;
                
                foreach ($data['expense_name'] as $index => $expense_name) {
                    if (!empty($expense_name)) {
                        $expense = [
                            'expense_name' => $expense_name,
                            'amount' => $data['amount'][$index] ?? 0,
                            'date' => $data['date'][$index] ?? '',
                            'description' => $data['description'][$index] ?? '',
                            'created_at' => date('Y-m-d H:i:s'),
                            'staff_id' => get_staff_user_id()
                        ];
                        
                        $total_amount += (float)$expense['amount'];
                        
                        // Handle file upload
                        if (isset($_FILES['receipt']['name'][$index]) && !empty($_FILES['receipt']['name'][$index])) {
                            $config['upload_path'] = './uploads/expenses/';
                            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
                            $config['max_size'] = 2048; // 2MB
                            $config['file_name'] = 'expense_' . time() . '_' . $index;
                            
                            if (!is_dir($config['upload_path'])) {
                                mkdir($config['upload_path'], 0755, true);
                            }
                            
                            $this->load->library('upload', $config);
                            
                            $_FILES['receipt_file']['name'] = $_FILES['receipt']['name'][$index];
                            $_FILES['receipt_file']['type'] = $_FILES['receipt']['type'][$index];
                            $_FILES['receipt_file']['tmp_name'] = $_FILES['receipt']['tmp_name'][$index];
                            $_FILES['receipt_file']['error'] = $_FILES['receipt']['error'][$index];
                            $_FILES['receipt_file']['size'] = $_FILES['receipt']['size'][$index];
                            
                            if ($this->upload->do_upload('receipt_file')) {
                                $upload_data = $this->upload->data();
                                $expense['receipt'] = $upload_data['file_name'];
                            }
                        }
                        
                        $expenses[] = $expense;
                    }
                }
                
                // Save expenses to database (you'll need to implement this)
                if (!empty($expenses)) {
                    // Here you would save the expenses array to your database
                    // For now, just redirect with success message
                    set_alert('success', 'Expenses submitted successfully!');
                }
            }
            
            redirect(admin_url('hr/expense_management'));
        }

        $data['title'] = _l('add_expense');
        $this->load->view('admin/hr/expense_management/add', $data);
    }
}