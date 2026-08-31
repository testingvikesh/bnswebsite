-- Admission ecosystem tables (admin: /controlpanel/admission-hub, /controlpanel/admission-pages)

CREATE TABLE IF NOT EXISTS `admission_hub` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `page_intro_2` text DEFAULT NULL,
  `menu_items` json DEFAULT NULL,
  `trust_title` varchar(255) DEFAULT NULL,
  `trust_items` json DEFAULT NULL,
  `after_admission_title` varchar(255) DEFAULT NULL,
  `after_admission_items` json DEFAULT NULL,
  `dashboard_title` varchar(255) DEFAULT NULL,
  `dashboard_items` json DEFAULT NULL,
  `office_counselor` varchar(255) DEFAULT NULL,
  `office_phone` varchar(30) DEFAULT NULL,
  `office_whatsapp` varchar(30) DEFAULT NULL,
  `office_email` varchar(255) DEFAULT NULL,
  `office_address` varchar(255) DEFAULT NULL,
  `maps_embed_url` text DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `admission_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slug` varchar(255) NOT NULL,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text DEFAULT NULL,
  `content_items` json DEFAULT NULL,
  `download_url` varchar(500) DEFAULT NULL,
  `hero_image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `admission_applications` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `application_number` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `program` varchar(255) DEFAULT NULL,
  `year_level` varchar(255) DEFAULT NULL,
  `batch` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `centre` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `parent_details` json DEFAULT NULL,
  `education_qualification` varchar(255) DEFAULT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `experience` varchar(255) DEFAULT NULL,
  `linkedin` varchar(500) DEFAULT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `fee_breakdown` json DEFAULT NULL,
  `payment_status` varchar(30) NOT NULL DEFAULT 'pending',
  `status` varchar(30) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admission_applications_application_number_unique` (`application_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (SELECT '2026_06_29_000021_create_admission_tables' AS migration) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (SELECT '2026_06_29_000022_create_admission_hub_table' AS migration) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);

-- Sync Eligibility Criteria page header (safe to re-run)
UPDATE `admission_pages` SET
  `page_subtitle` = 'Find the Right Program for Your Journey',
  `page_intro` = 'Business Navachar School (BNS) offers specialized Weekly Business School Programs for learners at different stages of life. Each program is designed to provide practical business education, entrepreneurial thinking, leadership, and financial literacy.',
  `content_items` = '[]'
WHERE `slug` = 'eligibility-criteria';

-- Sync Admission Process page header (safe to re-run)
UPDATE `admission_pages` SET
  `page_subtitle` = 'Your Journey Begins Here',
  `page_intro` = 'Joining Business Navachar School (BNS) is simple, transparent, and student-friendly. Our admission process is designed to help every learner choose the right program and begin their journey towards entrepreneurship, leadership, and prosperity.',
  `content_items` = '[]'
WHERE `slug` = 'admission-process';

-- Sync FAQs page header (safe to re-run)
UPDATE `admission_pages` SET
  `page_title` = 'Frequently Asked Questions (FAQs)',
  `page_subtitle` = 'Business Navachar School (BNS)',
  `page_intro` = 'Find clear answers about BNS programs, admissions, learning model, certificates, fees, and how to get started.',
  `content_items` = '[]'
WHERE `slug` = 'faqs';
