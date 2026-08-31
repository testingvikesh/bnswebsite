-- Expand contact_inquiries for full admission enquiry form
-- Run once if table already exists from contact_pages.sql

ALTER TABLE `contact_inquiries`
  ADD COLUMN `registration_number` varchar(255) DEFAULT NULL AFTER `id`,
  ADD COLUMN `date_of_birth` date DEFAULT NULL AFTER `email`,
  ADD COLUMN `gender` varchar(20) DEFAULT NULL AFTER `date_of_birth`,
  ADD COLUMN `address` text DEFAULT NULL AFTER `gender`,
  ADD COLUMN `pin_code` varchar(20) DEFAULT NULL AFTER `state`,
  ADD COLUMN `country` varchar(100) DEFAULT 'India' AFTER `pin_code`,
  ADD COLUMN `interested_program` varchar(255) DEFAULT NULL AFTER `country`,
  ADD COLUMN `educational_qualification` varchar(255) DEFAULT NULL AFTER `category`,
  ADD COLUMN `occupation` varchar(255) DEFAULT NULL AFTER `educational_qualification`,
  ADD COLUMN `organization_name` varchar(255) DEFAULT NULL AFTER `occupation`,
  ADD COLUMN `preferred_centre` varchar(255) DEFAULT NULL AFTER `organization_name`,
  ADD COLUMN `preferred_batch` varchar(50) DEFAULT NULL AFTER `preferred_centre`,
  ADD COLUMN `preferred_language` varchar(50) DEFAULT NULL AFTER `preferred_batch`,
  ADD COLUMN `hear_about` varchar(255) DEFAULT NULL AFTER `preferred_language`,
  ADD COLUMN `purpose_of_joining` json DEFAULT NULL AFTER `hear_about`,
  ADD COLUMN `expectations` text DEFAULT NULL AFTER `purpose_of_joining`,
  ADD COLUMN `documents` json DEFAULT NULL AFTER `message`,
  ADD COLUMN `agreed_info_correct` tinyint(1) NOT NULL DEFAULT 0 AFTER `agreed_to_contact`,
  ADD COLUMN `agreed_privacy` tinyint(1) NOT NULL DEFAULT 0 AFTER `agreed_info_correct`,
  MODIFY COLUMN `subject` varchar(255) DEFAULT NULL,
  MODIFY COLUMN `message` text DEFAULT NULL;

ALTER TABLE `contact_inquiries`
  ADD UNIQUE KEY `contact_inquiries_registration_number_unique` (`registration_number`);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (SELECT '2026_06_29_000018_expand_contact_inquiries_table' AS migration) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);
