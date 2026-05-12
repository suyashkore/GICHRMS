<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_112 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		if (!$CI->db->field_exists('overtime_hours' ,db_prefix() . 'hrp_employees_timesheets')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_employees_timesheets`
				ADD COLUMN `overtime_hours` DECIMAL(15,2) NULL DEFAULT '0.00'
				;");
		}

		if (hr_payroll_payroll_column_exist('"overtime_hours"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Overtime Hours", "system", "overtime_hours", "", "true", "Overtime Hours", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "11", "no");
				');
		}

		if (hr_payroll_payroll_column_exist('"13th_month_salary"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("13th Month Salary", "system", "13th_month_salary", "", "true", "13th Month Salary", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "24", "no");
				');
		}
		if (!$CI->db->field_exists('overtime_hours' ,db_prefix() . 'hrp_payslip_details')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_payslip_details`
				ADD COLUMN `overtime_hours` DECIMAL(15,2) NULL DEFAULT '0.00'
				;");
		}

		if (!$CI->db->field_exists('overtime_hours' ,db_prefix() . 'hrp_employees_timeshee_leaves')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_employees_timeshee_leaves`
				ADD COLUMN `overtime_hours` DECIMAL(15,2) NULL DEFAULT '0.00'
				;");
		}	
	}

}

