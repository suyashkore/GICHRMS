<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_323 extends CI_Migration
{
    public function up()
    {
        // Create hrm_offboarding table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `hrm_offboarding` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `employee_name` VARCHAR(255) NOT NULL,
                `employee_id` VARCHAR(100) NOT NULL,
                `department` VARCHAR(100) NOT NULL,
                `designation` VARCHAR(255) NOT NULL,
                `resignation_date` DATE NOT NULL,
                `last_working_date` DATE NOT NULL,
                `reason` VARCHAR(255) NOT NULL,
                `comments` LONGTEXT NULL,
                `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                `created_by` INT NULL,
                `created_date` DATETIME DEFAULT CURRENT_TIMESTAMP,
                `updated_by` INT NULL,
                `updated_date` DATETIME NULL ON UPDATE CURRENT_TIMESTAMP,
                `approved_by` INT NULL,
                `approved_date` DATETIME NULL,
                `rejected_by` INT NULL,
                `rejected_date` DATETIME NULL,
                INDEX `idx_status` (`status`),
                INDEX `idx_created_date` (`created_date`),
                INDEX `idx_resignation_date` (`resignation_date`),
                INDEX `idx_employee_id` (`employee_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down()
    {
        // Drop hrm_offboarding table
        $this->db->query("DROP TABLE IF EXISTS `hrm_offboarding`;");
    }
}
