CREATE PROCEDURE usp_ApplyForPosition
    @position_id INT,
    @employee_id INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRANSACTION;

    BEGIN TRY
        -- Check if the employee has already applied for this position
        IF EXISTS (SELECT 1 FROM applications WHERE position_id = @position_id AND employee_id = @employee_id)
        BEGIN
            RAISERROR('You have already applied for this position.', 16, 1);
            ROLLBACK TRANSACTION;
            RETURN;
        END

        -- Insert the application record
        INSERT INTO applications (employee_id, position_id, applied_at, status, created_at, updated_at)
        VALUES (@employee_id, @position_id, CURRENT_TIMESTAMP, 'pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP);

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END
