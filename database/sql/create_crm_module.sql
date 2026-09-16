-- BNS CRM module tables (run once on live DB if migrations are not used)

CREATE TABLE IF NOT EXISTS `crm_employees` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `username` VARCHAR(80) NOT NULL,
    `email` VARCHAR(255) NULL,
    `password` VARCHAR(255) NOT NULL,
    `mobile` VARCHAR(30) NULL,
    `facility` VARCHAR(255) NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `crm_employees_username_unique` (`username`),
    KEY `crm_employees_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_assignments` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `crm_employee_id` BIGINT UNSIGNED NOT NULL,
    `contact_inquiry_id` BIGINT UNSIGNED NOT NULL,
    `session_number` TINYINT UNSIGNED NOT NULL,
    `attendance_status` VARCHAR(20) NOT NULL DEFAULT 'present',
    `assigned_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `crm_assignments_inquiry_session_unique` (`contact_inquiry_id`, `session_number`),
    KEY `crm_assignments_session_number_index` (`session_number`),
    KEY `crm_assignments_attendance_status_index` (`attendance_status`),
    KEY `crm_assignments_employee_session_index` (`crm_employee_id`, `session_number`),
    CONSTRAINT `crm_assignments_crm_employee_id_foreign` FOREIGN KEY (`crm_employee_id`) REFERENCES `crm_employees` (`id`) ON DELETE CASCADE,
    CONSTRAINT `crm_assignments_contact_inquiry_id_foreign` FOREIGN KEY (`contact_inquiry_id`) REFERENCES `contact_inquiries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_followups` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `crm_assignment_id` BIGINT UNSIGNED NOT NULL,
    `followup_no` TINYINT UNSIGNED NOT NULL,
    `status` VARCHAR(40) NOT NULL DEFAULT 'pending',
    `note` TEXT NULL,
    `called_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `crm_followups_assignment_no_unique` (`crm_assignment_id`, `followup_no`),
    KEY `crm_followups_status_index` (`status`),
    CONSTRAINT `crm_followups_crm_assignment_id_foreign` FOREIGN KEY (`crm_assignment_id`) REFERENCES `crm_assignments` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `crm_spot_admissions` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `full_name` VARCHAR(255) NULL,
    `mobile` VARCHAR(30) NULL,
    `email` VARCHAR(255) NULL,
    `register_no` VARCHAR(40) NULL,
    `qr_data` TEXT NULL,
    `facility_name` VARCHAR(255) NULL,
    `reason` VARCHAR(255) NULL,
    `attended_at` TIMESTAMP NULL,
    `ip_address` VARCHAR(45) NULL,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    KEY `crm_spot_admissions_mobile_index` (`mobile`),
    KEY `crm_spot_admissions_register_no_index` (`register_no`),
    KEY `crm_spot_admissions_attended_at_index` (`attended_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Needed for CRM confirmed / payments list
SET @col_exists := (
    SELECT COUNT(*)
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'contact_inquiries'
      AND COLUMN_NAME = 'admission_confirmed_at'
);
SET @sql := IF(
    @col_exists = 0,
    'ALTER TABLE `contact_inquiries` ADD COLUMN `admission_confirmed_at` TIMESTAMP NULL DEFAULT NULL AFTER `status`, ADD INDEX `contact_inquiries_admission_confirmed_at_index` (`admission_confirmed_at`)',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;
