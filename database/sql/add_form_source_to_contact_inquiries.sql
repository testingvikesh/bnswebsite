-- Add form_source to contact_inquiries (run once on live DB if migration is not used)
ALTER TABLE `contact_inquiries`
    ADD COLUMN `form_source` VARCHAR(50) NULL AFTER `registration_number`;
