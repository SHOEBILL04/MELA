CREATE PROCEDURE usp_GetFairReport
    @fair_id INT
AS
BEGIN
    SET NOCOUNT ON;
    
    WITH RevenueCTE AS (
        SELECT 
            'Stall Sales' AS revenue_source,
            SUM(price) AS amount
        FROM stalls
        WHERE fair_id = @fair_id AND status = 'sold'
        
        UNION ALL
        
        SELECT 
            'Entry Tickets' AS revenue_source,
            SUM(ticket_price) AS amount
        FROM fair_tickets
        WHERE fair_id = @fair_id
        
        UNION ALL
        
        SELECT 
            'Event Tickets' AS revenue_source,
            SUM(et.ticket_price) AS amount
        FROM event_tickets et
        JOIN events e ON et.event_id = e.event_id
        WHERE e.fair_id = @fair_id
    )
    SELECT 
        revenue_source,
        ISNULL(amount, 0) AS amount
    FROM RevenueCTE;
END
