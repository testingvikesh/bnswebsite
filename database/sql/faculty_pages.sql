-- Visiting Expert Faculty page (admin: /controlpanel/faculty-page)

CREATE TABLE IF NOT EXISTS `faculty_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `excellence_label` varchar(255) NOT NULL DEFAULT 'Commitment',
  `excellence_title` varchar(255) NOT NULL DEFAULT 'Faculty Excellence',
  `excellence_paragraphs` json DEFAULT NULL,
  `tagline_brand` varchar(255) DEFAULT NULL,
  `tagline_text` varchar(255) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT '2026_06_29_000015_create_faculty_pages_table', IFNULL(MAX(batch), 0) + 1 FROM `migrations`
WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_06_29_000015_create_faculty_pages_table');
