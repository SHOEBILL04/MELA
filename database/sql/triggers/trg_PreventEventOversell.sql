CREATE TRIGGER trg_PreventEventOversell
ON event_tickets
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @event_id INT;
    DECLARE @tickets_sold INT;
    DECLARE @max_capacity INT;

    SELECT @event_id = event_id FROM inserted;

    SELECT @tickets_sold = tickets_sold, @max_capacity = max_capacity
    FROM events
    WHERE event_id = @event_id;
    IF (@tickets_sold >= @max_capacity)
    BEGIN
        RAISERROR('Cannot purchase ticket: This event is already at maximum capacity.', 16, 1);
        ROLLBACK TRANSACTION;
    END
    ELSE
    BEGIN
        UPDATE events
        SET tickets_sold = tickets_sold + 1
        WHERE event_id = @event_id;
    END
END;
