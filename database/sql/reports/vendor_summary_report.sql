-- Vendor Summary Report: Ekjon vendor koto stall ar event kineche tar summary
SELECT 
    'Stall' AS Type,
    s.stall_number AS Name,
    f.name AS Fair_Name,
    s.price AS Cost,
    s.status AS Status
FROM stalls s
JOIN fairs f ON s.fair_id = f.fair_id
WHERE s.vendor_id = @vendor_id

UNION ALL

SELECT 
    'Event' AS Type,
    e.name AS Name,
    f.name AS Fair_Name,
    e.ticket_price AS Cost,
    'Purchased' AS Status
FROM events e
JOIN fairs f ON e.fair_id = f.fair_id
WHERE e.vendor_id = @vendor_id;