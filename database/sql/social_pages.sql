-- Follow Us / Social Media page (admin: /controlpanel/social-page)

CREATE TABLE IF NOT EXISTS `social_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `page_intro_2` text DEFAULT NULL,
  `platforms_title` varchar(255) DEFAULT NULL,
  `platforms` json DEFAULT NULL,
  `benefits_title` varchar(255) DEFAULT NULL,
  `benefits_items` json DEFAULT NULL,
  `movement_title` varchar(255) DEFAULT NULL,
  `movement_text` text DEFAULT NULL,
  `movement_text_2` text DEFAULT NULL,
  `quick_connect_title` varchar(255) DEFAULT NULL,
  `tagline_brand` varchar(255) DEFAULT NULL,
  `tagline_text` varchar(255) DEFAULT NULL,
  `tagline_subtext` varchar(255) DEFAULT NULL,
  `tagline_hindi` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (SELECT '2026_06_29_000020_create_social_pages_table' AS migration) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);
