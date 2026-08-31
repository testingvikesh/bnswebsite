-- About Us page content (admin: /controlpanel/about-page)

CREATE TABLE IF NOT EXISTS `about_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tagline` varchar(255) NOT NULL DEFAULT 'About Us',
  `heading` varchar(255) NOT NULL,
  `intro_text` text NOT NULL,
  `focus_heading` varchar(255) DEFAULT NULL,
  `focus_points` json NOT NULL,
  `quote_text` varchar(500) DEFAULT NULL,
  `video_url` varchar(500) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `mission_title` varchar(255) DEFAULT NULL,
  `mission_text` text DEFAULT NULL,
  `vision_title` varchar(255) DEFAULT NULL,
  `vision_text` text DEFAULT NULL,
  `values` json DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000012_create_about_pages_table', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000012_create_about_pages_table');
