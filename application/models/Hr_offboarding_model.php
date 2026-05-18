<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_offboarding_model extends App_Model
{
    private $table = 'hrm_offboarding';

    public function __construct()
    {
        parent::__construct();
    }

    public function get_all_offboarding()
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $result = $this->db
            ->select('*')
            ->from($this->table)
            ->order_by('id', 'DESC')
            ->get()
            ->result();

        $this->db->dbprefix = $oldPrefix;

        return $result;
    }

    public function get_offboarding_by_id($id)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $result = $this->db
            ->select('*')
            ->from($this->table)
            ->where('id', $id)
            ->get()
            ->row();

        $this->db->dbprefix = $oldPrefix;

        return $result;
    }

    public function add_offboarding($data)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        $this->db->dbprefix = $oldPrefix;

        return $insert_id;
    }

    public function update_offboarding($id, $data)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db->where('id', $id)->update($this->table, $data);
        $affected_rows = $this->db->affected_rows();

        $this->db->dbprefix = $oldPrefix;

        return $affected_rows > 0;
    }

    public function delete_offboarding($id)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db->where('id', $id)->delete($this->table);
        $affected_rows = $this->db->affected_rows();

        $this->db->dbprefix = $oldPrefix;

        return $affected_rows > 0;
    }
}
