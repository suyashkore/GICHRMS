<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Migration_Version_322 extends CI_Migration
{
    public function up()
    {
        // Create hrm_onboarding table with comprehensive fields
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `hrm_onboarding` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                
                -- Section 1: Personal Details
                `full_name` VARCHAR(255) NOT NULL,
                `parent_name` VARCHAR(255) NULL,
                `dob` DATE NULL,
                `gender` VARCHAR(50) NULL,
                `marital_status` VARCHAR(50) NULL,
                `blood_group` VARCHAR(10) NULL,
                `nationality` VARCHAR(100) NULL,
                `mobile_number` VARCHAR(20) NULL,
                `personal_email` VARCHAR(255) NULL,
                `current_address` LONGTEXT NULL,
                `permanent_address` LONGTEXT NULL,
                
                -- Section 2: Identity & KYC Details
                `aadhaar_number` VARCHAR(20) NULL,
                `pan_number` VARCHAR(20) NULL,
                `passport_number` VARCHAR(50) NULL,
                `dl_number` VARCHAR(50) NULL,
                `uan_number` VARCHAR(20) NULL,
                `esic_number` VARCHAR(20) NULL,
                
                -- Section 3: Employment Details
                `employee_id` VARCHAR(50) NOT NULL,
                `designation` VARCHAR(255) NOT NULL,
                `department` VARCHAR(100) NOT NULL,
                `joining_date` DATE NOT NULL,
                `reporting_manager` VARCHAR(255) NULL,
                `employment_type` VARCHAR(50) NULL,
                `work_location` VARCHAR(255) NULL,
                
                -- Section 4: Educational Qualification
                `edu_10th_institute` VARCHAR(255) NULL,
                `edu_10th_year` INT NULL,
                `edu_10th_percentage` VARCHAR(10) NULL,
                `edu_12th_institute` VARCHAR(255) NULL,
                `edu_12th_year` INT NULL,
                `edu_12th_percentage` VARCHAR(10) NULL,
                `edu_graduation_institute` VARCHAR(255) NULL,
                `edu_graduation_year` INT NULL,
                `edu_graduation_percentage` VARCHAR(10) NULL,
                `edu_postgraduation_institute` VARCHAR(255) NULL,
                `edu_postgraduation_year` INT NULL,
                `edu_postgraduation_percentage` VARCHAR(10) NULL,
                `education_details` JSON NULL,
                `employment_history` JSON NULL,
                
                -- Section 5: Previous Employment Details
                `total_experience` DECIMAL(5, 2) NULL,
                `last_company` VARCHAR(255) NULL,
                `last_designation` VARCHAR(255) NULL,
                `last_ctc` DECIMAL(12, 2) NULL,
                `notice_period` VARCHAR(100) NULL,
                
                -- Section 6: Bank Details
                `bank_name` VARCHAR(255) NULL,
                `account_holder` VARCHAR(255) NULL,
                `account_number` VARCHAR(50) NULL,
                `ifsc_code` VARCHAR(20) NULL,
                `branch_name` VARCHAR(255) NULL,
                
                -- Section 7: Emergency Contact Details
                `emergency_contact_name` VARCHAR(255) NULL,
                `emergency_relationship` VARCHAR(100) NULL,
                `emergency_mobile` VARCHAR(20) NULL,
                
                -- Section 8: Statutory Information
                `pf_applicable` VARCHAR(10) NULL,
                `pf_uan` VARCHAR(20) NULL,
                `esic_applicable` VARCHAR(10) NULL,
                `nominee_name` VARCHAR(255) NULL,
                `nominee_relationship` VARCHAR(100) NULL,
                
                -- Section 9: Documents Submitted
                `documents` JSON NULL,
                
                -- Section 10: Declaration
                `employee_signature` LONGTEXT NULL,
                `declaration_date` DATE NULL,
                `declaration_place` VARCHAR(255) NULL,
                
                -- System fields (for legacy CTC approval)
                `candidate_name` VARCHAR(255) NULL,
                `proposed_ctc` DECIMAL(10, 2) NULL,
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
                INDEX `idx_joining_date` (`joining_date`),
                INDEX `idx_employee_id` (`employee_id`),
                INDEX `idx_department` (`department`),
                INDEX `idx_full_name` (`full_name`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ");
    }

    public function down()
    {
        // Drop hrm_onboarding table
        $this->db->query("DROP TABLE IF EXISTS `hrm_onboarding`;");
    }
}
