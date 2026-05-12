<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_110 extends App_module_migration
{
	public function up()
	{
		
		$CI = &get_instance();
		if (!$CI->db->field_exists('passport_no' ,db_prefix() . 'staff')) { 
			$CI->db->query('ALTER TABLE `' . db_prefix() . "staff`
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

	}
}
