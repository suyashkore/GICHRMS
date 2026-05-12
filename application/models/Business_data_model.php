<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Business_data_model extends App_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Get data item by ID
     * @param  mixed $id
     * @return mixed - array if not passed id, object if id passed
     */
    public function get($id = '')
    {
        
        $this->db->select('*');
        $this->db->from(db_prefix() . 'business_data');
        if (is_numeric($id)) {
            $this->db->where(db_prefix() . 'business_data.id', $id);

            return $this->db->get()->row();
        }
        return $this->db->get()->result_array();
    }
    
    /**
     * Add new invoice item
     * @param array $data Invoice item data
     * @return boolean
     */
    public function add($data)
    {
        unset($data["itemid"]);
        $data["created_date"] = date('Y-m-d');
        $data["updated_data"] = date('Y-m-d');
        $data["status"] = 1;
        $this->db->insert(db_prefix() . 'business_data', $data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            

           

            log_activity('New Data Added [ID:' . $insert_id . ', ' . $data['name'] . ']');

            return $insert_id;
        }

        return false;
    }
    
    /**
     * Update Business data
     * @param  array $data Invoice data to update
     * @return boolean
     */
    public function edit($data)
    {
        $itemid = $data['itemid'];
        unset($data['itemid']);

        $data["updated_data"] = date('Y-m-d');
        $this->db->where('id', $itemid);
        $this->db->update(db_prefix() . 'business_data', $data);
        if ($this->db->affected_rows() > 0) {
            log_activity('Business Data Updated [ID: ' . $itemid . ', ' . $data['name'] . ']');
            $affectedRows++;
        }

        

        return $affectedRows > 0 ? true : false;
    }
    
    /**
     * Delete Business Data
     * @param  mixed $id
     * @return boolean
     */
    
    
    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete(db_prefix() . 'business_data');
        if ($this->db->affected_rows() > 0) {
            

            log_activity('Business Data Deleted [ID: ' . $id . ']');

            

            return true;
        }

        return false;
    }

}