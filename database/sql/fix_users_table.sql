-- BNS Control Panel — fix `users` table for Laravel auth
-- Run in phpMyAdmin (one statement at a time; skip any "Duplicate column" errors)

-- 1) name
ALTER TABLE `users` ADD COLUMN `name` VARCHAR(255) NOT NULL DEFAULT '' AFTER `id`;

-- 2) email (skip if you already have email)
-- ALTER TABLE `users` ADD COLUMN `email` VARCHAR(255) NULL AFTER `name`;

-- 3) password (REQUIRED for login/register)
ALTER TABLE `users` ADD COLUMN `password` VARCHAR(255) NOT NULL DEFAULT '' AFTER `email`;

-- 3b) full_name — sync with name (legacy hosting DBs)
UPDATE `users` SET `full_name` = `name` WHERE (`full_name` IS NULL OR `full_name` = '') AND `name` IS NOT NULL AND `name` != '';
ALTER TABLE `users` MODIFY `full_name` VARCHAR(255) NOT NULL DEFAULT '';

-- 3c) password_hash — sync with password (legacy hosting DBs)
UPDATE `users` SET `password_hash` = `password` WHERE (`password_hash` IS NULL OR `password_hash` = '') AND `password` IS NOT NULL AND `password` != '';
ALTER TABLE `users` MODIFY `password_hash` VARCHAR(255) NOT NULL DEFAULT '';

-- 4) role (admin / user)
ALTER TABLE `users` ADD COLUMN `role` VARCHAR(20) NOT NULL DEFAULT 'user' AFTER `password`;

-- 5) remember token
ALTER TABLE `users` ADD COLUMN `remember_token` VARCHAR(100) NULL;

-- 6) timestamps (skip if already present)
-- ALTER TABLE `users` ADD COLUMN `created_at` TIMESTAMP NULL;
-- ALTER TABLE `users` ADD COLUMN `updated_at` TIMESTAMP NULL;

-- 7) Create admin (change email/password hash as needed)
-- Password below is: Admin@123
INSERT INTO `users` (`name`, `full_name`, `email`, `password`, `password_hash`, `role`, `created_at`, `updated_at`)
SELECT 'BNS Admin', 'BNS Admin', 'admin@bnsschool.com',
       '$2y$10$JjuIFvIdbL0FY2sqM6X44.p7MPtbl472KGRB6zZplUz1lkDveRAH6',
       '$2y$10$JjuIFvIdbL0FY2sqM6X44.p7MPtbl472KGRB6zZplUz1lkDveRAH6',
       'admin', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM `users` WHERE `email` = 'admin@bnsschool.com');

-- 8) Mark migrations as run (optional, if using Laravel migrate later)
INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000004_sync_users_table_for_control_panel', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000004_sync_users_table_for_control_panel');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000005_fix_users_table_columns', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000005_fix_users_table_columns');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000006_sync_users_full_name_column', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000006_sync_users_full_name_column');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000007_sync_users_password_hash_column', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000007_sync_users_password_hash_column');
