<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Business_data extends AdminController
{
    private $not_importable_fields = ['id','status','updated_data','created_date'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('business_data_model');
        
    }

    /* List all available items */
    public function index()
    {
        if (!is_admin()) {
            access_denied('Invoice Items');
        }
        
        
        
        $data['business_data'] = $this->business_data_model->get();
        
        /*echo "<pre>";
        print($data['route']);
        die;*/

        $data['title'] = "Business Data";
        $this->load->view('admin/business_data/manage', $data);
    }
    
    public function table()
    {
        if (!is_admin()) {
            ajax_access_denied();
        }
        $this->app->get_table_data('business_data_table');
    }
    
    /* Edit or update items / ajax request /*/
    public function manage()
    {
        if (is_admin()) {
            if ($this->input->post()) {
                $data = $this->input->post();
                if ($data['itemid'] == '') {
                    if (!is_admin()) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $id      = $this->business_data_model->add($data);
                    $success = false;
                    $message = '';
                    if ($id) {
                        $success = true;
                        $message = _l('added_successfully', "Business Data");
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                        'Data'    => $this->business_data_model->get($id),
                    ]);
                } else {
                    if (!is_admin()) {
                        header('HTTP/1.0 400 Bad error');
                        echo _l('access_denied');
                        die;
                    }
                    $success = $this->business_data_model->edit($data);
                    $message = '';
                    if ($success) {
                        $message = _l('updated_successfully', "Business Data");
                    }
                    echo json_encode([
                        'success' => $success,
                        'message' => $message,
                    ]);
                }
            }
        }
    }
    
    /* Get item by id / ajax */
    public function get_data_by_id($id)
    {
        if ($this->input->is_ajax_request()) {
            $hsn                    = $this->business_data_model->get($id);
            

            echo json_encode($hsn);
        }
    }
    
    /* Delete item*/
    public function delete($id)
    {
        if (!is_admin()) {
            access_denied('Invoice Items');
        }

       /* if (!$id) {
            redirect(admin_url('vehicles'));
        }*/

        $response = $this->business_data_model->delete($id);
        if (is_array($response) && isset($response['referenced'])) {
            set_alert('warning', _l('is_referenced', _l('invoice_item_lowercase')));
        } elseif ($response == true) {
            set_alert('success', 'Business Data Delected Successfully..');
        } else {
            set_alert('warning', _l('problem_deleting', _l('invoice_item_lowercase')));
        }
        redirect(admin_url('business_data'));
    }
    
    public function import()
    {
        if (!is_admin()) {
            access_denied('Items Import');
        }

        $this->load->library('import/import_business_data', [], 'import');

        $this->import->setDatabaseFields($this->db->list_fields(db_prefix().'business_data'))
                     ->setCustomFields(get_custom_fields('business_data'));

        if ($this->input->post('download_sample') === 'true') {
            $this->import->downloadSample();
        }

        if ($this->input->post()
            && isset($_FILES['file_csv']['name']) && $_FILES['file_csv']['name'] != '') {
                //$states = $this->input->post('states');
                //$distributor_id = $this->input->post('distributor_id');
                //$effective_date = $this->input->post('effective_date');
            $this->import->setSimulation($this->input->post('simulate'))
                          ->setTemporaryFileLocation($_FILES['file_csv']['tmp_name'])
                          ->setFilename($_FILES['file_csv']['name'])
                          ->perform();

            $data['total_rows_post'] = $this->import->totalRows();

            if (!$this->import->isSimulation()) {
                set_alert('success', _l('import_total_imported', $this->import->totalImported()));
                redirect('admin/business_data');
            }
        }
         //$data['items_groups'] = $this->rate_master_model->get_groups();
        //$data['states'] = $this->rate_master_model->get_state();
        //$data['groups'] = $this->clients_model->get_groups();
        //$data['items_main_groups'] = $this->rate_master_model->get_main_groups();
        //$data['items_sub_groups'] = $this->rate_master_model->get_sub_groups();
        $data['title'] = "Import Business Data";
        $this->load->view('admin/business_data/import', $data);
    }
    
}