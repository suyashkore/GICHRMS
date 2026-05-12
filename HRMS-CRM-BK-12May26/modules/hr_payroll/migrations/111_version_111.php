<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_111 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		// Accounting mapping
		if ($CI->db->table_exists(db_prefix() . 'hrp_payslips')) {
			if (!$CI->db->field_exists('acc_mapping' ,db_prefix() . 'hrp_payslips')) {
				$CI->db->query("ALTER TABLE `" . db_prefix() . "hrp_payslips`
					ADD COLUMN `acc_mapping` tinyint(1) NOT NULL DEFAULT '0'
					");
			}
		}
	}

}

