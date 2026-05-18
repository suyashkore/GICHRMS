<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_onboarding_model extends App_Model
{
    private $table = 'hrm_onboarding';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all onboarding records
     */
    public function get_all_onboarding()
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

    /**
     * Get onboarding record by ID
     */
    public function get_onboarding_by_id($id)
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

    /**
     * Get onboarding records by candidate name
     */
    public function get_onboarding_by_candidate($candidate_name)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $result = $this->db
            ->select('*')
            ->from($this->table)
            ->where('candidate_name', $candidate_name)
            ->order_by('id', 'DESC')
            ->get()
            ->result();

        $this->db->dbprefix = $oldPrefix;

        return $result;
    }

    /**
     * Get onboarding records by status
     */
    public function get_onboarding_by_status($status)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $result = $this->db
            ->select('*')
            ->from($this->table)
            ->where('status', $status)
            ->order_by('id', 'DESC')
            ->get()
            ->result();

        $this->db->dbprefix = $oldPrefix;

        return $result;
    }

    /**
     * Get onboarding records by department
     */
    public function get_onboarding_by_department($department)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $result = $this->db
            ->select('*')
            ->from($this->table)
            ->where('department', $department)
            ->order_by('id', 'DESC')
            ->get()
            ->result();

        $this->db->dbprefix = $oldPrefix;

        return $result;
    }

    /**
     * Add new onboarding record
     */
    public function add_onboarding($data)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        $this->db->dbprefix = $oldPrefix;

        return $insert_id;
    }

    /**
     * Update onboarding record
     */
    public function update_onboarding($id, $data)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db
            ->where('id', $id)
            ->update($this->table, $data);

        $affected_rows = $this->db->affected_rows();

        $this->db->dbprefix = $oldPrefix;

        return $affected_rows > 0 ? true : false;
    }

    /**
     * Delete onboarding record
     */
    public function delete_onboarding($id)
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $this->db
            ->where('id', $id)
            ->delete($this->table);

        $affected_rows = $this->db->affected_rows();

        $this->db->dbprefix = $oldPrefix;

        return $affected_rows > 0 ? true : false;
    }

    /**
     * Count pending onboarding requests
     */
    public function count_pending_onboarding()
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $count = $this->db
            ->select('COUNT(id) as count')
            ->from($this->table)
            ->where('status', 'pending')
            ->get()
            ->row();

        $this->db->dbprefix = $oldPrefix;

        return $count ? $count->count : 0;
    }

    /**
     * Count approved onboarding requests
     */
    public function count_approved_onboarding()
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $count = $this->db
            ->select('COUNT(id) as count')
            ->from($this->table)
            ->where('status', 'approved')
            ->get()
            ->row();

        $this->db->dbprefix = $oldPrefix;

        return $count ? $count->count : 0;
    }

    /**
     * Count rejected onboarding requests
     */
    public function count_rejected_onboarding()
    {
        $oldPrefix          = $this->db->dbprefix;
        $this->db->dbprefix = '';

        $count = $this->db
            ->select('COUNT(id) as count')
            ->from($this->table)
            ->where('status', 'rejected')
            ->get()
            ->row();

        $this->db->dbprefix = $oldPrefix;

        return $count ? $count->count : 0;
    }

    /**
     * Get onboarding statistics
     */
    public function get_onboarding_statistics()
    {
        return [
            'pending'   => $this->count_pending_onboarding(),
            'approved'  => $this->count_approved_onboarding(),
            'rejected'  => $this->count_rejected_onboarding(),
        ];
    }
}
