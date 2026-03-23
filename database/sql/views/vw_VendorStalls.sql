-- Vendor-er dashboard-er jonno ekta VIEW banano hocche [cite: 91, 200]
CREATE VIEW vw_VendorStalls AS
SELECT 
    s.stall_id,
    s.stall_number,
    f.name AS fair_name,
    u.name AS vendor_name,
    s.category,
    s.price AS stall_price,
    -- Koyjon employee approved obosthay ache ta count kora hocche [cite: 106]
    (SELECT COUNT(*) FROM employee_positions ep 
     WHERE ep.stall_id = s.stall_id AND ep.status = 'filled') AS employee_count
FROM stalls s
JOIN fairs f ON s.fair_id = f.fair_id
LEFT JOIN users u ON s.vendor_id = u.user_id;
GO