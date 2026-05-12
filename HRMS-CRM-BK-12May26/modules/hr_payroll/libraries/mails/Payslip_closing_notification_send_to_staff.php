<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Payslip_closing_notification_send_to_staff extends App_mail_template
{
    protected $for = 'staff';

    protected $payslip_detail;
    
    protected $staff;

    public $slug = 'payslip-closing-notification-send-to-staff';

    public function __construct($payslip_detail, $staff, $cc = '')
    {
        parent::__construct();

        $this->payslip_detail = $payslip_detail;
        $this->staff = $staff;
        $this->cc      = $cc;

        // For SMS and merge fields for email
        $this->set_merge_fields('hr_payroll_merge_fields', $this->payslip_detail);
        if($this->staff){
            $this->set_merge_fields('staff_merge_fields', $this->staff->staffid);
        }
    }

    public function build()
    {
        $this->to($this->staff->email);
    }
}
