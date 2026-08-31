-- WhatsApp Support page (admin: /controlpanel/whatsapp-page)

CREATE TABLE IF NOT EXISTS `whatsapp_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `page_intro_2` text DEFAULT NULL,
  `help_title` varchar(255) DEFAULT NULL,
  `help_intro` text DEFAULT NULL,
  `help_items` json DEFAULT NULL,
  `chat_title` varchar(255) DEFAULT NULL,
  `whatsapp_number` varchar(30) DEFAULT NULL,
  `availability_label` varchar(255) DEFAULT NULL,
  `availability_hours` json DEFAULT NULL,
  `quick_options` json DEFAULT NULL,
  `before_chat_title` varchar(255) DEFAULT NULL,
  `before_chat_intro` text DEFAULT NULL,
  `before_chat_items` json DEFAULT NULL,
  `one_tap_actions` json DEFAULT NULL,
  `immediate_title` varchar(255) DEFAULT NULL,
  `immediate_phone` varchar(30) DEFAULT NULL,
  `immediate_email` varchar(255) DEFAULT NULL,
  `immediate_website` varchar(255) DEFAULT NULL,
  `immediate_centre_url` varchar(500) DEFAULT NULL,
  `brochure_url` varchar(500) DEFAULT NULL,
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
FROM (SELECT '2026_06_29_000019_create_whatsapp_pages_table' AS migration) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);
