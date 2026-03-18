CREATE TRIGGER trg_PreventEventOversell
ON event_tickets
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;

    DECLARE @event_id INT;
    DECLARE @tickets_sold INT;
    DECLARE @max_capacity INT;

    -- Get the event_id from the newly inserted ticket(s)
    SELECT @event_id = event_id FROM inserted;

    -- Get current stats from the events table
    SELECT @tickets_sold = tickets_sold, @max_capacity = max_capacity
    FROM events
    WHERE event_id = @event_id;

    -- Check if we are over capacity
    -- Note: This trigger assumes tickets_sold is updated AFTER this check or via another mechanism.
    -- To be safe, we check if current sold >= capacity BEFORE allowing this insert to finalize.
    IF (@tickets_sold >= @max_capacity)
    BEGIN
        RAISERROR('Cannot purchase ticket: This event is already at maximum capacity.', 16, 1);
        ROLLBACK TRANSACTION;
    END
    ELSE
    BEGIN
        -- Automatically increment the tickets_sold count in the events table
        UPDATE events
        SET tickets_sold = tickets_sold + 1
        WHERE event_id = @event_id;
    END
END;
