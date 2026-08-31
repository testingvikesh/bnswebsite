-- Run in phpMyAdmin if artisan migrate is not available

CREATE TABLE IF NOT EXISTS `event_galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `cover_path` varchar(255) DEFAULT NULL,
  `picasa_url` varchar(1000) DEFAULT NULL,
  `picasa_label` varchar(255) DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `event_galleries_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_gallery_photos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_gallery_id` bigint unsigned NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `caption` varchar(255) DEFAULT NULL,
  `photo_path` varchar(255) NOT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_gallery_photos_event_gallery_id_foreign` (`event_gallery_id`),
  CONSTRAINT `event_gallery_photos_event_gallery_id_foreign`
    FOREIGN KEY (`event_gallery_id`) REFERENCES `event_galleries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `event_gallery_reels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `event_gallery_id` bigint unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `caption` text DEFAULT NULL,
  `youtube_url` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `sort_order` smallint unsigned NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_gallery_reels_event_gallery_id_foreign` (`event_gallery_id`),
  CONSTRAINT `event_gallery_reels_event_gallery_id_foreign`
    FOREIGN KEY (`event_gallery_id`) REFERENCES `event_galleries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
