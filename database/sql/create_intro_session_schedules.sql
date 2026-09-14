-- Introduction Session schedules (run once on live DB if migration is not used)
CREATE TABLE IF NOT EXISTS `intro_session_schedules` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `session_number` TINYINT UNSIGNED NOT NULL,
    `title` VARCHAR(255) NULL,
    `date_label` VARCHAR(255) NULL,
    `time_label` VARCHAR(255) NULL,
    `starts_at` DATETIME NULL,
    `ends_at` DATETIME NULL,
    `timezone` VARCHAR(64) NOT NULL DEFAULT 'Asia/Kolkata',
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL,
    `updated_at` TIMESTAMP NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `intro_session_schedules_session_number_unique` (`session_number`),
    KEY `intro_session_schedules_starts_at_index` (`starts_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
