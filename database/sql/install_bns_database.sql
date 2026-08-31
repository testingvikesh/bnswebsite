-- =============================================================================
-- BNS School — complete database schema (required tables only)
-- Database: u567045244_db_bns
-- Run in phpMyAdmin. Skip "already exists" / "duplicate column" errors.
--
-- BEFORE running: if you have legacy SOP tables, run drop_unnecessary_tables.sql
-- AFTER users table: run fix_users_table.sql if hosting DB uses full_name/password_hash
-- =============================================================================

-- -----------------------------------------------------------------------------
-- Laravel core
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Admin panel — website content
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `advisory_board_members` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `expertise` text NOT NULL,
  `profile` text NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `linkedin_url` varchar(255) DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `visiting_expert_faculty` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_prefix` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `designation` varchar(255) NOT NULL DEFAULT 'Visiting Expert Faculty',
  `recognition` varchar(255) DEFAULT NULL,
  `expertise` text NOT NULL,
  `professional_experience` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `specialization` varchar(255) DEFAULT NULL,
  `faculty_since` smallint UNSIGNED DEFAULT NULL,
  `sessions_conducted` varchar(255) DEFAULT NULL,
  `learners_mentored` varchar(255) DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `about` text DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(500) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `home_page_images` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(80) NOT NULL,
  `section` varchar(100) NOT NULL,
  `label` varchar(255) NOT NULL,
  `default_path` varchar(255) NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `sort_order` smallint UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `home_page_images_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

CREATE TABLE IF NOT EXISTS `contact_pages` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `page_title` varchar(255) NOT NULL,
  `page_subtitle` varchar(255) DEFAULT NULL,
  `page_intro` text NOT NULL,
  `page_intro_2` text DEFAULT NULL,
  `office_title` varchar(255) DEFAULT NULL,
  `office_brand` varchar(255) DEFAULT NULL,
  `office_tagline` varchar(255) DEFAULT NULL,
  `office_head_label` varchar(255) DEFAULT NULL,
  `address_line` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `phone_helpline` varchar(30) DEFAULT NULL,
  `phone_whatsapp` varchar(30) DEFAULT NULL,
  `phone_office` varchar(30) DEFAULT NULL,
  `email_admissions` varchar(255) DEFAULT NULL,
  `email_general` varchar(255) DEFAULT NULL,
  `email_media` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `office_hours` json DEFAULT NULL,
  `admission_support_title` varchar(255) DEFAULT NULL,
  `admission_support_intro` text DEFAULT NULL,
  `admission_support_items` json DEFAULT NULL,
  `partnership_title` varchar(255) DEFAULT NULL,
  `partnership_intro` text DEFAULT NULL,
  `partnership_items` json DEFAULT NULL,
  `faculty_cta_title` varchar(255) DEFAULT NULL,
  `faculty_cta_text` text DEFAULT NULL,
  `faculty_cta_url` varchar(500) DEFAULT NULL,
  `media_title` varchar(255) DEFAULT NULL,
  `media_text` text DEFAULT NULL,
  `social_links` json DEFAULT NULL,
  `maps_embed_url` text DEFAULT NULL,
  `form_categories` json DEFAULT NULL,
  `immediate_title` varchar(255) DEFAULT NULL,
  `immediate_call` varchar(30) DEFAULT NULL,
  `immediate_whatsapp` varchar(30) DEFAULT NULL,
  `immediate_intro_url` varchar(500) DEFAULT NULL,
  `immediate_apply_url` varchar(500) DEFAULT NULL,
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

CREATE TABLE IF NOT EXISTS `contact_inquiries` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `mobile` varchar(30) NOT NULL,
  `whatsapp` varchar(30) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `state` varchar(255) DEFAULT NULL,
  `pin_code` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT 'India',
  `interested_program` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `educational_qualification` varchar(255) DEFAULT NULL,
  `occupation` varchar(255) DEFAULT NULL,
  `organization_name` varchar(255) DEFAULT NULL,
  `preferred_centre` varchar(255) DEFAULT NULL,
  `preferred_batch` varchar(50) DEFAULT NULL,
  `preferred_language` varchar(50) DEFAULT NULL,
  `hear_about` varchar(255) DEFAULT NULL,
  `purpose_of_joining` json DEFAULT NULL,
  `expectations` text DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `documents` json DEFAULT NULL,
  `agreed_to_contact` tinyint(1) NOT NULL DEFAULT 0,
  `agreed_info_correct` tinyint(1) NOT NULL DEFAULT 0,
  `agreed_privacy` tinyint(1) NOT NULL DEFAULT 0,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contact_inquiries_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `agreed_terms` tinyint(1) NOT NULL DEFAULT 1,
  `source` varchar(50) NOT NULL DEFAULT 'footer',
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_subscribers_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- -----------------------------------------------------------------------------
-- Public forms — school registrations
-- -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `youth_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'youth_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `youth_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `student_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'student_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `student_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `women_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'women_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `women_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `working_women_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'working_women_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `working_women_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_professional_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'job_professional_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `job_professional_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `business_growth_admissions` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `registration_number` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'business_growth_school',
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `business_growth_admissions_registration_number_unique` (`registration_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `venue_inspections` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `inspection_number` varchar(255) NOT NULL,
  `venue_name` varchar(255) NOT NULL,
  `institution_name` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `mobile` varchar(255) NOT NULL,
  `inspection_date` date NOT NULL,
  `inspector_name` varchar(255) NOT NULL,
  `final_decision` varchar(255) DEFAULT NULL,
  `form_data` json NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `venue_inspections_inspection_number_unique` (`inspection_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Mark Laravel migrations as run
-- -----------------------------------------------------------------------------

INSERT INTO `migrations` (`migration`, `batch`)
SELECT t.migration, IFNULL((SELECT MAX(batch) FROM `migrations` m), 0) + 1
FROM (
  SELECT '2014_10_12_000000_create_users_table' AS migration UNION ALL
  SELECT '2014_10_12_100000_create_password_reset_tokens_table' UNION ALL
  SELECT '2019_08_19_000000_create_failed_jobs_table' UNION ALL
  SELECT '2019_12_14_000001_create_personal_access_tokens_table' UNION ALL
  SELECT '2026_06_26_000001_create_youth_admissions_table' UNION ALL
  SELECT '2026_06_26_000002_create_student_admissions_table' UNION ALL
  SELECT '2026_06_26_000003_create_women_admissions_table' UNION ALL
  SELECT '2026_06_26_000004_create_working_women_admissions_table' UNION ALL
  SELECT '2026_06_26_000005_create_job_professional_admissions_table' UNION ALL
  SELECT '2026_06_26_000006_create_business_growth_admissions_table' UNION ALL
  SELECT '2026_06_26_000007_create_venue_inspections_table' UNION ALL
  SELECT '2026_06_29_000001_create_advisory_board_members_table' UNION ALL
  SELECT '2026_06_29_000002_create_visiting_expert_faculty_table' UNION ALL
  SELECT '2026_06_29_000003_create_testimonials_table' UNION ALL
  SELECT '2026_06_29_000004_sync_users_table_for_control_panel' UNION ALL
  SELECT '2026_06_29_000005_fix_users_table_columns' UNION ALL
  SELECT '2026_06_29_000006_sync_users_full_name_column' UNION ALL
  SELECT '2026_06_29_000007_sync_users_password_hash_column' UNION ALL
  SELECT '2026_06_29_000010_create_home_page_images_table' UNION ALL
  SELECT '2026_06_29_000011_drop_legacy_sop_tables' UNION ALL
  SELECT '2026_06_29_000012_create_about_pages_table' UNION ALL
  SELECT '2026_06_29_000013_create_team_pages_table' UNION ALL
  SELECT '2026_06_29_000014_create_team_members_table' UNION ALL
  SELECT '2026_06_29_000015_create_faculty_pages_table' UNION ALL
  SELECT '2026_06_29_000016_create_contact_pages_table' UNION ALL
  SELECT '2026_06_29_000017_create_contact_inquiries_table' UNION ALL
  SELECT '2026_06_29_000018_expand_contact_inquiries_table' UNION ALL
  SELECT '2026_06_29_000019_create_whatsapp_pages_table' UNION ALL
  SELECT '2026_06_29_000020_create_social_pages_table' UNION ALL
  SELECT '2026_06_29_000021_create_admission_tables' UNION ALL
  SELECT '2026_06_29_000022_create_admission_hub_table' UNION ALL
  SELECT '2026_07_10_000001_create_newsletter_subscribers_table'
) AS t
WHERE NOT EXISTS (SELECT 1 FROM `migrations` m WHERE m.migration = t.migration);
