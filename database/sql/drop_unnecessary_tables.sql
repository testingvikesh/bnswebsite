-- BNS — remove legacy / unused tables
-- Run in phpMyAdmin on production AFTER backing up the database.

SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `sop_box_user`;
DROP TABLE IF EXISTS `sop_document_user`;
DROP TABLE IF EXISTS `sop_box_items`;
DROP TABLE IF EXISTS `sop_boxes`;
DROP TABLE IF EXISTS `sop_documents`;

SET FOREIGN_KEY_CHECKS = 1;

-- Remove old SOP migration records (optional)
DELETE FROM `migrations` WHERE `migration` LIKE '2026_06_01_%';
