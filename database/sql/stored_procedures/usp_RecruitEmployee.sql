CREATE PROCEDURE usp_RecruitEmployee
    @application_id INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRANSACTION;

    BEGIN TRY
        DECLARE @position_id INT;
        DECLARE @current_status NVARCHAR(20);

        SELECT @position_id = position_id, @current_status = status 
        FROM applications WITH (UPDLOCK)
        WHERE application_id = @application_id;

        IF @current_status != 'pending'
        BEGIN
            ROLLBACK TRANSACTION;
            RAISERROR('Application is not pending.', 16, 1);
            RETURN;
        END

        -- Approve the application
        -- This will automatically trigger `trg_ApplicationStatusChange` which marks the position as `filled`
        UPDATE applications
        SET status = 'approved'
        WHERE application_id = @application_id;

        -- Auto-reject all other pending applications for this position
        UPDATE applications
        SET status = 'rejected'
        WHERE position_id = @position_id 
          AND application_id != @application_id 
          AND status = 'pending';

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END
