-- Add leadership profile column and update Dr. Mehul Rupani (sort order 1)
-- Run via phpMyAdmin on live database

ALTER TABLE `team_members`
  ADD COLUMN IF NOT EXISTS `profile` LONGTEXT NULL AFTER `role`;

UPDATE `team_members`
SET
  `sort_order` = 1,
  `designation` = 'Founder & Chief Visionary',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`full_name` LIKE '%Mehul Rupani%' OR `designation` LIKE '%Founder%');

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_07_09_000001_add_profile_to_team_members_table', IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
WHERE NOT EXISTS (
  SELECT 1 FROM `migrations` m WHERE m.migration = '2026_07_09_000001_add_profile_to_team_members_table'
);

UPDATE `team_members`
SET
  `sort_order` = 2,
  `designation` = 'Chief Executive Officer (CEO)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `email` = NULL,
  `linkedin_url` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`designation` LIKE '%Chief Executive Officer%' OR `designation` LIKE '%CEO%')
  AND `full_name` NOT LIKE '%Mehul Rupani%';

UPDATE `team_members`
SET
  `sort_order` = 3,
  `designation` = 'Director – Business Navachar School (BNS)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`sort_order` = 3 OR `designation` LIKE '%Chief Academic Officer%' OR `designation` LIKE '%CAO%' OR `designation` LIKE '%Director%BNS%')
  AND `full_name` NOT LIKE '%Mehul Rupani%';

UPDATE `team_members`
SET
  `sort_order` = 4,
  `designation` = 'Director – Digital & Technology (BNS)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `email` = NULL,
  `linkedin_url` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`sort_order` = 4 OR `designation` LIKE '%Digital & Technology%');

INSERT INTO `team_members` (`category`, `full_name`, `designation`, `expertise`, `sort_order`, `is_featured`, `is_active`, `created_at`, `updated_at`)
SELECT 'leadership', '[Name]', 'Director – Digital & Technology (BNS)', JSON_ARRAY(), 4, 0, 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `team_members` WHERE `category` = 'leadership' AND `sort_order` = 4
);

UPDATE `team_members`
SET
  `sort_order` = 5,
  `designation` = 'Head – Social Media Marketing & Digital Operations (BNS)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `email` = NULL,
  `linkedin_url` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`sort_order` = 5 OR `designation` LIKE '%Social Media Marketing%');

INSERT INTO `team_members` (`category`, `full_name`, `designation`, `expertise`, `sort_order`, `is_featured`, `is_active`, `created_at`, `updated_at`)
SELECT 'leadership', '[Name]', 'Head – Social Media Marketing & Digital Operations (BNS)', JSON_ARRAY(), 5, 0, 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `team_members` WHERE `category` = 'leadership' AND `sort_order` = 5
);

UPDATE `team_members`
SET
  `sort_order` = 6,
  `designation` = 'Head of Marketing (BNS)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `email` = NULL,
  `linkedin_url` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`sort_order` = 6 OR `designation` LIKE '%Head of Marketing%');

INSERT INTO `team_members` (`category`, `full_name`, `designation`, `expertise`, `sort_order`, `is_featured`, `is_active`, `created_at`, `updated_at`)
SELECT 'leadership', '[Name]', 'Head of Marketing (BNS)', JSON_ARRAY(), 6, 0, 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `team_members` WHERE `category` = 'leadership' AND `sort_order` = 6
);

UPDATE `team_members`
SET
  `sort_order` = 7,
  `designation` = 'Marketing Manager (BNS)',
  `expertise` = JSON_ARRAY(),
  `profile` = NULL,
  `email` = NULL,
  `linkedin_url` = NULL,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`sort_order` = 7 OR `designation` LIKE '%Marketing Manager%');

INSERT INTO `team_members` (`category`, `full_name`, `designation`, `expertise`, `sort_order`, `is_featured`, `is_active`, `created_at`, `updated_at`)
SELECT 'leadership', '[Name]', 'Marketing Manager (BNS)', JSON_ARRAY(), 7, 0, 1, NOW(), NOW()
WHERE NOT EXISTS (
  SELECT 1 FROM `team_members` WHERE `category` = 'leadership' AND `sort_order` = 7
);

-- Note: profile NULL keeps the built-in profile layouts on the website.
