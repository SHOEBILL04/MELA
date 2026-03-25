CREATE TRIGGER trg_GenerateTicketQR_Fair
ON fair_tickets
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    UPDATE ft
    SET ft.qr_code = CAST(NEWID() AS VARCHAR(100))
    FROM fair_tickets ft
    INNER JOIN inserted i ON ft.ticket_id = i.ticket_id;
END;
