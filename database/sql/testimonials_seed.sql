-- BNS Testimonials seed (optional — config/testimonials.php works without DB)
-- Run via phpMyAdmin after testimonials table exists

CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELETE FROM `testimonials`;

INSERT INTO `testimonials` (`full_name`, `designation`, `message`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
('Student, Grade 9', 'School Student', 'Business Navachar School completely changed the way I think. I now understand entrepreneurship, financial literacy, leadership, and business planning. The practical learning approach made every session exciting. Receiving a Participation Certificate from the Faculties of IIM was a proud moment for me.', 1, 1, NOW(), NOW()),
('College Student', 'College Youth', 'BNS taught me skills that my college curriculum never covered. Business models, marketing, communication, and startup thinking have given me confidence to build my own venture. The certificate from the Faculties of IIM has added great value to my profile.', 2, 1, NOW(), NOW()),
('Woman Entrepreneur', 'Women Entrepreneur Program', 'I joined BNS to learn how to start a small business. Today, I understand digital marketing, branding, customer management, and business growth. This program has transformed my confidence.', 3, 1, NOW(), NOW()),
('Working Professional', 'Job Professional Growth Program', 'The Weekly Business School helped me improve leadership, financial planning, communication, and decision-making. The practical learning methodology is truly different from traditional training.', 4, 1, NOW(), NOW()),
('Business Owner', 'Business Owner Growth Program', 'BNS provided practical strategies for business growth, systems, branding, and scaling. The networking opportunities with entrepreneurs and mentors were extremely valuable.', 5, 1, NOW(), NOW()),
('Startup Founder', 'Entrepreneur', 'Every session focused on solving real business challenges. Learning directly from experienced mentors and receiving recognition through the Faculties of IIM certificate made this journey even more meaningful.', 6, 1, NOW(), NOW()),
('Industry Professional', 'Industry Expert', 'Business Navachar School is creating exactly what India needs—future entrepreneurs with practical business knowledge, ethical leadership, and innovative thinking. This initiative has the potential to create long-term national impact.', 7, 1, NOW(), NOW()),
('Parent', 'BNS Parent', 'My child has become more confident, responsible, and financially aware after joining BNS. The focus on leadership, communication, and entrepreneurship is preparing students for the future.', 8, 1, NOW(), NOW()),
('Business Mentor', 'Expert Mentor', 'The Weekly Business School model combines practical learning, research, case studies, presentations, and mentorship in a structured manner. It is one of the most promising business education initiatives.', 9, 1, NOW(), NOW()),
('Business Leader', 'Business Leader', 'Business Navachar School is not just teaching business—it is building entrepreneurial citizens. The collaboration with the Faculties of IIM, practical learning approach, and focus on prosperity make BNS a unique institution.', 10, 1, NOW(), NOW());
