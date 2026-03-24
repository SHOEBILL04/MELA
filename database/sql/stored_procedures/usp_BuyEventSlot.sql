CREATE PROCEDURE usp_BuyEventSlot
    @vendor_id INT,
    @event_id INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;

        -- Check korchi vendor-er oi fair-e kono stall ache kina
        IF NOT EXISTS (
            SELECT 1 FROM stalls s
            JOIN events e ON s.fair_id = e.fair_id
            WHERE s.vendor_id = @vendor_id AND e.event_id = @event_id
        )
        BEGIN
            ROLLBACK TRANSACTION;
            RAISERROR('Error: You must own a stall in this fair to buy an event slot.', 16, 1);
            RETURN;
        END

        -- Event-er ownership update kora
        UPDATE events
        SET vendor_id = @vendor_id
        WHERE event_id = @event_id AND vendor_id IS NULL;

        COMMIT TRANSACTION;
        PRINT 'Success: Event slot purchased successfully.';
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END;
GO