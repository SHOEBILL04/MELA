CREATE PROCEDURE usp_BuyEventTicket
    @visitor_id BIGINT,
    @event_id BIGINT,
    @ticket_price DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;

        DECLARE @fair_id BIGINT;
        DECLARE @fair_ticket_id BIGINT;
        DECLARE @max_capacity INT;
        DECLARE @tickets_sold INT;

        -- 1. Grab lock to prevent race condition over capacity
        SELECT 
            @fair_id = fair_id,
            @max_capacity = max_capacity,
            @tickets_sold = tickets_sold
        FROM events WITH (UPDLOCK, ROWLOCK)
        WHERE event_id = @event_id;

        IF @max_capacity IS NULL
        BEGIN
            RAISERROR('Invalid event selected.', 16, 1);
        END

        IF @tickets_sold >= @max_capacity
        BEGIN
            RAISERROR('This event is sold out.', 16, 1);
        END

        -- 2. Check fair ticket using CTE
        ;WITH ValidFairTickets AS (
            SELECT ticket_id
            FROM fair_tickets
            WHERE visitor_id = @visitor_id AND fair_id = @fair_id
        )
        SELECT TOP 1 @fair_ticket_id = ticket_id FROM ValidFairTickets;

        IF @fair_ticket_id IS NULL
        BEGIN
            RAISERROR('You must purchase a fair ticket before buying an event ticket.', 16, 1);
        END

        -- 3. Insert event ticket
        INSERT INTO event_tickets (visitor_id, event_id, fair_ticket_id, ticket_price, purchase_date, created_at, updated_at)
        VALUES (@visitor_id, @event_id, @fair_ticket_id, @ticket_price, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

        -- 4. Update count
        UPDATE events
        SET tickets_sold = tickets_sold + 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE event_id = @event_id;

        COMMIT TRAN;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRAN;
            
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;
