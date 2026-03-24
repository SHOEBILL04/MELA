CREATE TRIGGER trg_GenerateTicketQR_Event
ON event_tickets
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE et
    SET et.qr_code = CAST(NEWID() AS VARCHAR(100))
    FROM event_tickets et
    INNER JOIN inserted i ON et.event_ticket_id = i.event_ticket_id;
END;
