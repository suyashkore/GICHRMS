<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_322 extends CI_Migration
{
    public function up()
    {
        // Create hrm_onboarding table
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `hrm_onboarding` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `candidate_name` VARCHAR(255) NOT NULL,
                `proposed_ctc` DECIMAL(10, 2) NOT NULL,
                `joining_date` DATE NOT NULL,
                `department` VARCHAR(100) NOT NULL,
                `approval_notes` LONGTEXT NULL,
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
                INDEX `idx_joining_date` (`joining_date`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down()
    {
        // Drop hrm_onboarding table
        $this->db->query("DROP TABLE IF EXISTS `hrm_onboarding`;");
    }
}
