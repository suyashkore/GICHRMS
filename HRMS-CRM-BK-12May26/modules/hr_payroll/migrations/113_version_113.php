<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_113 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		if (row_hr_payroll_options_exist('"notify_closing_payslip"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hr_payroll_option` (`option_name`,`option_val`, `auto`) VALUES ("notify_closing_payslip", "0", "1");
				');
		}
		create_email_template('Pay slip {month_created}','Dear {staff_firstname} {staff_lastname}, a new payslip is available for you<br /><br />Please find attached PDF.<br /><br />Kind Regards, <br />{email_signature}', 'hr_payroll', 'Payslip Closing Notification (Sent to Staff)', 'payslip-closing-notification-send-to-staff');
	}

}