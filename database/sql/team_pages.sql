-- Team page (admin: /controlpanel/team-page, /controlpanel/team-members)

CREATE TABLE IF NOT EXISTS `team_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `leadership_title` varchar(255) NOT NULL DEFAULT 'Leadership Team',
  `academic_title` varchar(255) NOT NULL DEFAULT 'Academic Team',
  `advisory_title` varchar(255) NOT NULL DEFAULT 'Advisory Board',
  `collab_badge` varchar(255) DEFAULT NULL,
  `collab_title` varchar(255) DEFAULT NULL,
  `collab_description` text DEFAULT NULL,
  `operations_title` varchar(255) NOT NULL DEFAULT 'Operations Team',
  `operations_teams` json DEFAULT NULL,
  `values_title` varchar(255) NOT NULL DEFAULT 'Our Team Values',
  `values_items` json DEFAULT NULL,
  `join_title` varchar(255) NOT NULL DEFAULT 'Join Our Team',
  `join_intro` text DEFAULT NULL,
  `join_looking_label` varchar(255) DEFAULT NULL,
  `join_roles` json DEFAULT NULL,
  `join_cta_title` varchar(255) DEFAULT NULL,
  `join_cta_text` text DEFAULT NULL,
  `join_contact_email` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `team_members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `role` text DEFAULT NULL,
  `expertise` json DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_members_category_is_active_sort_order_index` (`category`,`is_active`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (
  SELECT '2026_06_29_000013_create_team_pages_table' AS migration UNION ALL
  SELECT '2026_06_29_000014_create_team_members_table'
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);
