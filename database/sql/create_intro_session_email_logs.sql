-- intro_session_email_logs — XAMPP / MariaDB 10.4 compatible
-- Hostinger dumps use utf8mb4_uca1400_ai_ci, which this server does not have.
-- Use utf8mb4_unicode_ci instead.

CREATE TABLE IF NOT EXISTS `intro_session_email_logs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `contact_inquiry_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_number` tinyint(3) UNSIGNED NOT NULL,
  `template_key` varchar(120) DEFAULT NULL,
  `template_title` varchar(255) DEFAULT NULL,
  `registration_number` varchar(40) DEFAULT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'sent',
  `error_message` text DEFAULT NULL,
  `sent_by` bigint(20) UNSIGNED DEFAULT NULL,
  `batch_key` varchar(40) DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `intro_session_email_logs_session_number_index` (`session_number`),
  KEY `intro_session_email_logs_template_key_index` (`template_key`),
  KEY `intro_session_email_logs_registration_number_index` (`registration_number`),
  KEY `intro_session_email_logs_email_index` (`email`),
  KEY `intro_session_email_logs_status_index` (`status`),
  KEY `intro_session_email_logs_batch_key_index` (`batch_key`),
  KEY `intro_session_email_logs_sent_at_index` (`sent_at`),
  KEY `intro_session_email_logs_contact_inquiry_id_foreign` (`contact_inquiry_id`),
  KEY `intro_session_email_logs_sent_by_foreign` (`sent_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
