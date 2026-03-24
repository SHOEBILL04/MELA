CREATE PROCEDURE usp_BuyFairTicket
    @visitor_id BIGINT,
    @fair_id BIGINT,
    @day_id BIGINT,
    @ticket_price DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRAN;

        DECLARE @max_visitors INT;
        DECLARE @visitors_count INT;

        -- 1. Grab lock to prevent race condition over limit
        SELECT 
            @max_visitors = max_visitors, 
            @visitors_count = visitors_count
        FROM fair_days WITH (UPDLOCK, ROWLOCK)
        WHERE day_id = @day_id AND fair_id = @fair_id;

        IF @max_visitors IS NULL
        BEGIN
            RAISERROR('Invalid fair day selected.', 16, 1);
        END

        IF @visitors_count >= @max_visitors
        BEGIN
            RAISERROR('No remaining slots for this fair day.', 16, 1);
        END

        -- 2. Insert ticket
        INSERT INTO fair_tickets (visitor_id, fair_id, day_id, ticket_price, purchase_date, created_at, updated_at)
        VALUES (@visitor_id, @fair_id, @day_id, @ticket_price, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

        -- 3. Update count
        UPDATE fair_days
        SET visitors_count = visitors_count + 1,
            updated_at = CURRENT_TIMESTAMP
        WHERE day_id = @day_id;

        COMMIT TRAN;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRAN;
        
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;
