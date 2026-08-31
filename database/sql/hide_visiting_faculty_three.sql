-- Hide these 3 from the public visiting faculty page.
UPDATE `visiting_expert_faculty`
SET `is_active` = 0
WHERE `full_name` LIKE '%Rishi Gangoly%'
   OR `full_name` LIKE '%Madhav Agrawall%'
   OR `full_name` LIKE '%Arunagiri Mudaliar%';
