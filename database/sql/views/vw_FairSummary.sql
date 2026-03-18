CREATE VIEW vw_FairSummary AS
WITH StallStats AS (
    -- Calculate revenue and count per fair from the stalls table
    SELECT 
        fair_id,
        COUNT(stall_id) AS total_stalls_sold,
        SUM(price) AS total_stall_revenue
    FROM stalls
    WHERE status = 'sold'
    GROUP BY fair_id
),
FairTicketStats AS (
    -- Calculate revenue from fair entry tickets
    SELECT 
        fair_id,
        COUNT(ticket_id) AS total_visitors,
        SUM(ticket_price) AS total_entry_revenue
    FROM fair_tickets
    GROUP BY fair_id
),
EventTicketStats AS (
    -- Calculate revenue from event tickets (joined via the events table)
    SELECT 
        e.fair_id,
        SUM(et.ticket_price) AS total_event_revenue
    FROM event_tickets et
    JOIN events e ON et.event_id = e.event_id
    GROUP BY e.fair_id
)
SELECT 
    f.fair_id,
    f.name AS fair_name,
    f.status AS fair_status,
    ISNULL(ss.total_stalls_sold, 0) AS stalls_sold,
    ISNULL(ss.total_stall_revenue, 0) AS stall_revenue,
    ISNULL(fts.total_visitors, 0) AS total_visitors,
    ISNULL(fts.total_entry_revenue, 0) AS entry_revenue,
    ISNULL(ets.total_event_revenue, 0) AS event_revenue,
    
    -- Calculate Total Fair Revenue
    (ISNULL(ss.total_stall_revenue, 0) + 
     ISNULL(fts.total_entry_revenue, 0) + 
     ISNULL(ets.total_event_revenue, 0)) AS total_fair_revenue,

    -- Window Function: Rank fairs by total revenue
    RANK() OVER (ORDER BY (ISNULL(ss.total_stall_revenue, 0) + ISNULL(fts.total_entry_revenue, 0) + ISNULL(ets.total_event_revenue, 0)) DESC) AS revenue_rank,

    -- Window Function: Running total of revenue across all fairs (Global Performance)
    SUM(ISNULL(ss.total_stall_revenue, 0) + ISNULL(fts.total_entry_revenue, 0) + ISNULL(ets.total_event_revenue, 0)) OVER() AS global_system_revenue

FROM fairs f
LEFT JOIN StallStats ss ON f.fair_id = ss.fair_id
LEFT JOIN FairTicketStats fts ON f.fair_id = fts.fair_id
LEFT JOIN EventTicketStats ets ON f.fair_id = ets.fair_id;
