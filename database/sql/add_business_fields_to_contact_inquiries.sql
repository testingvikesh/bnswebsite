ALTER TABLE `contact_inquiries`
    ADD COLUMN `business_profession_category` VARCHAR(255) NULL AFTER `organization_name`,
    ADD COLUMN `business_category` VARCHAR(255) NULL AFTER `business_profession_category`,
    ADD COLUMN `products_services` TEXT NULL AFTER `business_category`;
