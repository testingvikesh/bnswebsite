-- Fix: Unknown column 'template_key' in intro_session_email_logs
-- Run this in phpMyAdmin if Send Mail still errors.

ALTER TABLE `intro_session_email_logs`
  ADD COLUMN `template_key` VARCHAR(120) NULL AFTER `session_number`;

ALTER TABLE `intro_session_email_logs`
  ADD COLUMN `template_title` VARCHAR(255) NULL AFTER `template_key`;

CREATE INDEX `intro_session_email_logs_template_key_index`
  ON `intro_session_email_logs` (`template_key`);
