CREATE TABLE IF NOT EXISTS `sponsor_members` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `designation` VARCHAR(255) NULL,
    `profile` TEXT NULL,
    `photo_path` VARCHAR(500) NULL,
    `default_photo` VARCHAR(500) NULL,
    `sort_order` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sponsor_members` (`name`, `designation`, `profile`, `default_photo`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('CA Sanjay Doshi', 'President', 'Associated with Stock markets, Mutual Funds & Insurance industry since last 30 years.', 'assets/images/team/sponsors/ca-sanjay-doshi.png', 1, 1, NOW(), NOW()),
('CA Pankaj Bavishi', 'Vice President', 'Associated with Audit, Income Tax and Specialises in Charitable trust audits.', 'assets/images/team/sponsors/ca-pankaj-bavishi.png', 2, 1, NOW(), NOW());

-- If already imported with old text, run:
-- UPDATE `sponsor_members` SET `designation` = 'President' WHERE `designation` = 'President in Santacruz';
-- UPDATE `sponsor_members` SET `designation` = 'Vice President' WHERE `designation` = 'Vice President in Santacruz';
