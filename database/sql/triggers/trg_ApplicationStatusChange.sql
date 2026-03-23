CREATE TRIGGER trg_ApplicationStatusChange
ON applications
AFTER UPDATE
AS
BEGIN
    SET NOCOUNT ON;

    -- Sudhu jodi application-er 'status' change hoy
    IF UPDATE(status)
    BEGIN
        -- employee_positions table-er status 'filled' hobe
        UPDATE ep
        SET ep.status = 'filled'
        FROM employee_positions ep
        INNER JOIN inserted i ON ep.position_id = i.position_id
        INNER JOIN deleted d ON i.application_id = d.application_id
        -- Jokhon notun status 'approved' hobe kintu puronota 'approved' chilo na
        WHERE i.status = 'approved' AND d.status != 'approved';
    END
END;
GO