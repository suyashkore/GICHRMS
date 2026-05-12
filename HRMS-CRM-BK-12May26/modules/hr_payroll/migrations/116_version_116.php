<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_116 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();

		$i = count($CI->db->query('Select * from '.db_prefix().'hrp_payroll_columns where function_name = "hourly_rate"')->result_array());
		if($i == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Hourly Rate", "system", "hourly_rate", "", "true", "Hourly Rate", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}

		$i = count($CI->db->query('Select * from '.db_prefix().'hrp_payroll_columns where function_name = "basic_salary"')->result_array());
		if($i == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Total Hourly Pay", "system", "basic_salary", "", "true", "Total Hourly Pay", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');

		}

	}
}