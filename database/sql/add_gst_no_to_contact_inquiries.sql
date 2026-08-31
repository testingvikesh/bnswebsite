-- Add optional GST No. to contact_inquiries (run once on live DB if migration is not used)
ALTER TABLE `contact_inquiries`
    ADD COLUMN `gst_no` VARCHAR(20) NULL AFTER `email`;
