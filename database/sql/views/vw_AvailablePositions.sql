CREATE VIEW vw_AvailablePositions AS
SELECT 
    ep.position_id,
    ep.title,
    ep.salary,
    ep.status AS position_status,
    s.stall_id,
    s.stall_number,
    s.category,
    s.status AS stall_status,
    f.fair_id,
    f.name AS fair_name,
    f.location,
    f.start_date,
    f.end_date,
    f.status AS fair_status
FROM employee_positions ep
INNER JOIN stalls s ON ep.stall_id = s.stall_id
INNER JOIN fairs f ON s.fair_id = f.fair_id
WHERE ep.status = 'open';
