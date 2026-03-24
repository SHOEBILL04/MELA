CREATE VIEW vw_VisitorTickets
AS
WITH AllTickets AS (
    SELECT 
        ticket_id as id,
        visitor_id,
        'Fair' as ticket_type,
        purchase_date,
        ticket_price
    FROM fair_tickets
    
    UNION ALL
    
    SELECT 
        event_ticket_id as id,
        visitor_id,
        'Event' as ticket_type,
        purchase_date,
        ticket_price
    FROM event_tickets
)
SELECT 
    id,
    visitor_id,
    ticket_type,
    purchase_date,
    ticket_price,
    SUM(ticket_price) OVER (PARTITION BY visitor_id ORDER BY purchase_date ROWS UNBOUNDED PRECEDING) as cumulative_spend
FROM AllTickets;
