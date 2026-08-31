-- Keep 1 row per unique mobile (last 10 digits). Delete all other duplicate rows.
-- Preview first (safe):

SELECT
    RIGHT(REGEXP_REPLACE(mobile, '[^0-9]', ''), 10) AS unique_mobile,
    COUNT(*) AS total_rows,
    GROUP_CONCAT(id ORDER BY id) AS ids
FROM contact_inquiries
WHERE mobile IS NOT NULL
  AND TRIM(mobile) <> ''
GROUP BY RIGHT(REGEXP_REPLACE(mobile, '[^0-9]', ''), 10)
HAVING COUNT(*) > 1
ORDER BY total_rows DESC;

-- Delete duplicates (keeps the LOWEST id = first registration for each unique mobile):

DELETE c
FROM contact_inquiries c
INNER JOIN contact_inquiries d
  ON RIGHT(REGEXP_REPLACE(c.mobile, '[^0-9]', ''), 10)
   = RIGHT(REGEXP_REPLACE(d.mobile, '[^0-9]', ''), 10)
 AND c.id > d.id
WHERE c.mobile IS NOT NULL
  AND TRIM(c.mobile) <> ''
  AND d.mobile IS NOT NULL
  AND TRIM(d.mobile) <> '';

-- Optional: only Intro Session duplicates — add this to both JOINs/WHERE if needed:
-- AND c.form_source = 'intro-session-modal'
-- AND d.form_source = 'intro-session-modal'
