-- Add Picasa / Google Photos album link fields
-- Run in phpMyAdmin if artisan migrate is not available
-- Skip if columns already exist

ALTER TABLE `event_galleries`
  ADD COLUMN `picasa_url` varchar(1000) DEFAULT NULL AFTER `cover_path`,
  ADD COLUMN `picasa_label` varchar(255) DEFAULT NULL AFTER `picasa_url`;
