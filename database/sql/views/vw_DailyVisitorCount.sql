CREATE VIEW vw_DailyVisitorCount AS
SELECT
    fd.day_id,
    fd.fair_id,
    f.name AS fair_name,
    fd.day_date,
    fd.max_visitors,
    fd.visitors_count,
    (fd.max_visitors - fd.visitors_count) AS remaining_capacity,
    CAST(ROUND((CAST(fd.visitors_count AS FLOAT) / CAST(NULLIF(fd.max_visitors, 0) AS FLOAT)) * 100, 2) AS DECIMAL(5,2)) AS occupancy_percentage
FROM fair_days fd
JOIN fairs f ON fd.fair_id = f.fair_id;
