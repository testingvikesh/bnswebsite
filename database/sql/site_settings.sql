-- Site branding & header settings — run in phpMyAdmin if migrations are not used
-- Values are stored as key/value pairs; defaults fall back to config/site.php

CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Optional header keys (insert/update from Admin → Site Settings):
-- header_email, header_phone, header_address, header_welcome_text, header_social_title
-- header_social_twitter, header_social_facebook, header_social_pinterest, header_social_instagram
-- site_logo, site_favicon, site_logo_alt
-- site_brochure_pdf, site_brochure_title, site_brochure_subtitle, site_brochure_intro
-- legal_effective_date, legal_last_updated
-- hero_intro_video_url, hero_intro_video_label
