<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_115 extends App_module_migration

{
	public function up()
	{      
		$CI = &get_instance();
		if (!$CI->db->table_exists(db_prefix() . 'hrp_export_jobs')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . 'hrp_export_jobs`
				(
				`id` INT(11) NOT NULL AUTO_INCREMENT,
				`payslip_id` INT(11) NULL DEFAULT 0,
				`status` TEXT NULL,
				`file_path` TEXT NULL,
				`date_created` DATETIME NULL,
				`finished_at` DATETIME NULL,

				PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=' . $CI->db->char_set . ';');
		}

	}

}