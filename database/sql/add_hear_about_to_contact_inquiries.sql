-- How did you hear about BNS? (intro session form)
-- Column stores the dropdown value, or free text when "Other" is chosen.
-- Safe to skip if the column already exists (error: Duplicate column name).

ALTER TABLE `contact_inquiries`
  ADD COLUMN `hear_about` VARCHAR(255) NULL AFTER `preferred_language`;
