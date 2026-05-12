<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_114 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		if (!$CI->db->table_exists(db_prefix() . 'hrp_project_costs')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . 'hrp_project_costs`
				(
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`project_id` INT(11) NULL DEFAULT 0,
				`is_bonus` TINYINT(1) NULL DEFAULT 0,
				`description` TEXT NULL DEFAULT NULL,
				`date_assign` DATE NULL,
				`hours` DECIMAL(15,2) NULL DEFAULT "0.00",
				`hourly_rate` DECIMAL(15,2) NULL DEFAULT "0.00",
				`bonus_amount` DECIMAL(15,2) NULL DEFAULT "0.00",
				`active` TINYINT(1) NULL DEFAULT 0,
				`date_created` DATETIME NULL,
				`createdby` INT(11) NULL DEFAULT 0,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
		}
		
		if (!$CI->db->table_exists(db_prefix() . 'hrp_project_for_staffs')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . 'hrp_project_for_staffs`
				(
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`project_cost_id` INT(11) NULL DEFAULT 0,
				`staff_id` INT(11) NULL DEFAULT 0,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
		}

		if (hr_payroll_payroll_column_exist('"project_salary"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Project Salary", "system", "project_salary", "", "true", "Project Salary", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}

		if (hr_payroll_payroll_column_exist('"project_bonus"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Project Bonus", "system", "project_bonus", "", "true", "Project Bonus", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}

		if (!$CI->db->field_exists('project_salary' ,db_prefix() . 'hrp_payslip_details')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_payslip_details`
				ADD COLUMN `project_salary`  DECIMAL(15,2)  DEFAULT '0',
				ADD COLUMN `project_bonus`  DECIMAL(15,2)  DEFAULT '0'

				;");
		}
	}

}