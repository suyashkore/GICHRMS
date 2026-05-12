<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		// V1.1.0
		if (hr_payroll_payroll_column_exist('"passport_no"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Passport No", "system", "passport_no", "", "true", "Passport No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"passport_expiry"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Passport Expiry", "system", "passport_expiry", "", "true", "Passport Expiry", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"emirates_ID_No"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Emirates ID No", "system", "emirates_ID_No", "", "true", "Emirates ID No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"EID_expiry"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("EID Expiry", "system", "EID_expiry", "", "true", "EID Expiry", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"labour_card_No"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Labour Card No", "system", "labour_card_No", "", "true", "Labour Card No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}

		if (hr_payroll_payroll_column_exist('"phonenumber"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Mobile No", "system", "phonenumber", "", "true", "Mobile No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"contact_person_home_country"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Contact Person Home Country", "system", "contact_person_home_country", "", "true", "Contact Person Home Country", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"home_contact_No"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Home Contact No", "system", "home_contact_No", "", "true", "Home Contact No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"other_contact_person"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Other Contact Person", "system", "other_contact_person", "", "true", "Other Contact Person", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"other_contact_No"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Other Contact No", "system", "other_contact_No", "", "true", "Other Contact No", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"visa_validity"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Visa Validity", "system", "visa_validity", "", "true", "Visa Validity", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "17", "no");
				');
		}

		if (!$CI->db->field_exists('passport_no' ,db_prefix() . 'hrp_employees_value')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_employees_value`
				ADD COLUMN `passport_no` TEXT,
				ADD COLUMN `passport_expiry` TEXT,
				ADD COLUMN `emirates_ID_No` TEXT,
				ADD COLUMN `EID_expiry` TEXT,
				ADD COLUMN `labour_card_No` TEXT,
				ADD COLUMN `contact_person_home_country` TEXT,
				ADD COLUMN `home_contact_No` TEXT,
				ADD COLUMN `other_contact_person` TEXT,
				ADD COLUMN `other_contact_No` TEXT,
				ADD COLUMN `visa_validity` TEXT

				;");
		}
		if (!$CI->db->field_exists('phonenumber' ,db_prefix() . 'hrp_employees_value')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_employees_value`
				ADD COLUMN `phonenumber` TEXT
				
				;");
		}

		if (hr_payroll_payroll_column_exist('"designation"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Designation", "system", "designation", "", "true", "Designation", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"sex"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Gender", "system", "sex", "", "true", "Gender", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"birthday"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("DOB", "system", "birthday", "", "true", "DOB", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"datecreated"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Date of Joining", "system", "datecreated", "", "true", "Date of Joining", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}
		if (hr_payroll_payroll_column_exist('"nation"') == 0){
			$CI->db->query('INSERT INTO `' . db_prefix() . 'hrp_payroll_columns` (`column_key`, `taking_method`, `function_name`, `value_related_to`, `display_with_staff`, `description`, `date_created`, `staff_id_created`, `order_display`, `is_edit`) VALUES ("Nationality", "system", "nation", "", "true", "Nationality", "'.date("Y-m-d H:i:s").'", "'.get_staff_user_id().'", "16", "no");
				');
		}

		if (!$CI->db->field_exists('designation' ,db_prefix() . 'hrp_employees_value')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "hrp_employees_value`
				ADD COLUMN `designation` TEXT,
				ADD COLUMN `sex` TEXT,
				ADD COLUMN `birthday` TEXT,
				ADD COLUMN `datecreated` TEXT,
				ADD COLUMN `nation` TEXT
				;");
		}

	}

}

