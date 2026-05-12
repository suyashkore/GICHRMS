<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Migration_Version_123 extends App_module_migration
{
	public function up()
	{
		$CI = &get_instance();

		add_option('kteco_integration', 0, 1);
		add_option('kteco_host_name', '', 1);
		add_option('kteco_port_number', '', 1);
		add_option('kteco_username', '', 1);
		add_option('kteco_password', '', 1);
		add_option('kteco_create_employee', 0, 1);


		if (!$CI->db->table_exists(db_prefix() . 'timesheets_kteco_attendance_logs')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . "timesheets_kteco_attendance_logs` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`emp_code` VARCHAR(50) NULL,
		`first_name` TEXT NULL,
		`last_name` TEXT NULL,
		`department` TEXT NULL,
		`position` TEXT NULL,
		`punch_time` datetime NULL,
		`punch_state` INT(11) NOT NULL DEFAULT '0',
		`punch_state_display` TEXT NULL,
		`verify_type` TEXT NULL ,
		`verify_type_display` TEXT NULL ,
		`work_code` TEXT NULL ,
		`gps_location` TEXT NULL ,
		`area_alias` TEXT NULL ,
		`terminal_sn` VARCHAR(50) NULL ,
		`temperature` DECIMAL(15,1) NULL DEFAULT '0.0',
		`is_mask` TEXT NULL ,
		`terminal_alias` TEXT NULL ,
		`upload_time` DATETIME NULL ,
		`longitude` TEXT NULL ,
		`latitude` TEXT NULL ,
		`raw_id` INT(11) NULL,
		`created_at` datetime NULL,
		PRIMARY KEY (`id`),
		UNIQUE KEY `no_duplicate` (`emp_code`,`punch_time`,`terminal_sn`)

	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
		}

		if (!$CI->db->table_exists(db_prefix() . 'timesheets_kteco_sync_states')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . "timesheets_kteco_sync_states` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`name` TEXT NULL,
		`punch_time` datetime NULL,
		PRIMARY KEY (`id`)
	) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
		}

		if (!$CI->db->field_exists('zkbio_time', db_prefix() . 'timesheets_timesheet')) {
			$CI->db->query('ALTER TABLE `' . db_prefix() . "timesheets_timesheet`
		ADD COLUMN `zkbio_time` INT(11) NULL  DEFAULT '0';");
		}

		if (!$CI->db->table_exists(db_prefix() . 'timesheets_kteco_employees')) {
			$CI->db->query('CREATE TABLE `' . db_prefix() . "timesheets_kteco_employees` (
		`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
		`zkbio_emp_code` TEXT NULL,
		`zkbio_first_name` TEXT NULL,
		`zkbio_last_name` TEXT NULL,
		`perfex_staff_id` INT(11) NULL,

		PRIMARY KEY (`id`)
	   ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');
		}

			
	if (!$CI->db->field_exists('synch_to_attendance', db_prefix() . 'timesheets_kteco_attendance_logs')) {
		$CI->db->query('ALTER TABLE `' . db_prefix() . "timesheets_kteco_attendance_logs`
			ADD COLUMN `synch_to_attendance` INT(11) NULL  DEFAULT '0';");
	}
	}
}
