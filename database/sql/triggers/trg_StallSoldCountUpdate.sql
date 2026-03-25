
-- Prothome fairs table-e stalls_sold column add kore nichhi (jodi na thake)
IF NOT EXISTS (
    SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_NAME = 'fairs' AND COLUMN_NAME = 'stalls_sold'
)
BEGIN
    ALTER TABLE fairs ADD stalls_sold INT DEFAULT 0;
END
GO

-- Ebar Ashol Trigger
CREATE TRIGGER trg_StallSoldCountUpdate 
ON stalls 
AFTER UPDATE
AS
BEGIN
    -- Shudhu matro status column update holei trigger kaaj korbe
    IF UPDATE(status)
    BEGIN
        -- Check korchi notun status 'sold' kina ebang purano status onno kichu chilo kina
        IF EXISTS (
            SELECT 1 
            FROM inserted i
            INNER JOIN deleted d ON i.stall_id = d.stall_id
            WHERE i.status = 'sold' AND d.status <> 'sold'
        )
        BEGIN
            -- Fairs table-e stalls_sold er count 1 bariye dicchi
            UPDATE f
            SET f.stalls_sold = ISNULL(f.stalls_sold, 0) + 1
            FROM fairs f
            INNER JOIN inserted i ON f.fair_id = i.fair_id
            INNER JOIN deleted d ON i.stall_id = d.stall_id
            WHERE i.status = 'sold' AND d.status <> 'sold';
        END
    END
END;
GO