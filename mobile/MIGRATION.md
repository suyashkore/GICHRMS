# 23 Apr 2026 (done)
ALTER TABLE `tblstaff` ADD `login_attempts` INT NOT NULL AFTER `visa_validity`;
ALTER TABLE `tblstaff` ADD `blocked_until` DATETIME NULL AFTER `login_attempts`;
ALTER TABLE `tblstaff` ADD `last_login_at` DATETIME NULL AFTER `blocked_until`;

# 24 Apr 2026
ALTER TABLE `tblstaff` ADD `forgot_otp` VARCHAR(6) NULL, ADD `forgot_otp_expires_at` DATETIME NULL, ADD `forgot_otp_verified` TINYINT(1) DEFAULT 0;
