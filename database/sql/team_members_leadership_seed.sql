-- Leadership team expertise seed (run via phpMyAdmin on live DB)
-- Updates existing leadership members with detailed area-of-expertise points.
-- Safe to re-run: matches by full_name / designation.

UPDATE `team_members`
SET
  `role` = 'Overall Vision, Strategy, Academic Direction & National Expansion',
  `expertise` = JSON_ARRAY(
    'Visionary leader focused on building financially empowered individuals and entrepreneurs',
    'Founder of BNS with a mission to transform mindset from earning to wealth creation',
    'Expertise in business strategy, growth, and financial structuring',
    'Mentors students, professionals, and business owners to scale income and strengthen balance sheets',
    'Strong advocate of practical, result-oriented business education',
    'Focuses on innovation, business expansion, and sustainable wealth creation',
    'Driving BNS as a platform for Business Education + Networking + Opportunities'
  ),
  `sort_order` = 1,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`full_name` LIKE '%Mehul Rupani%' OR `designation` LIKE '%Founder%');

UPDATE `team_members`
SET
  `role` = 'Operations, Growth & Strategic Execution',
  `expertise` = JSON_ARRAY(
    'Leads day-to-day operations and strategic execution across the BNS network',
    'Focuses on organizational growth, team building, and scalable business systems',
    'Expertise in business operations, process excellence, and performance management',
    'Drives partnerships, expansion planning, and national outreach initiatives',
    'Committed to building a high-performance culture rooted in accountability and innovation',
    'Aligns operational goals with the BNS mission of entrepreneurship and employment creation',
    'Ensures smooth delivery of programs, events, and learner success outcomes'
  ),
  `sort_order` = 2,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`designation` LIKE '%Chief Executive Officer%' OR `designation` LIKE '%CEO%');

UPDATE `team_members`
SET
  `role` = 'Curriculum Development & Academic Excellence',
  `expertise` = JSON_ARRAY(
    'Leads curriculum design and academic quality for all BNS programs',
    'Focuses on practical, industry-relevant business learning frameworks',
    'Expertise in learning design, faculty development, and academic mentoring',
    'Develops structured pathways from idea to startup to scalable business growth',
    'Ensures integration of entrepreneurship, finance, leadership, and innovation modules',
    'Collaborates with industry experts and academic partners for program excellence',
    'Committed to outcome-driven education that builds confident business leaders'
  ),
  `sort_order` = 3,
  `updated_at` = NOW()
WHERE `category` = 'leadership'
  AND (`designation` LIKE '%Chief Academic Officer%' OR `designation` LIKE '%CAO%');
