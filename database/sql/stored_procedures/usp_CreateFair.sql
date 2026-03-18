CREATE PROCEDURE usp_CreateFair
    @admin_id INT,
    @name NVARCHAR(150),
    @location NVARCHAR(200),
    @start_date DATE,
    @end_date DATE,
    @total_stalls INT,
    @default_limit INT
AS
BEGIN
    SET NOCOUNT ON;

    BEGIN TRY
        BEGIN TRANSACTION;

        -- 1. Insert the Fair record
        DECLARE @fair_id INT;
        INSERT INTO fairs (admin_id, name, location, start_date, end_date, total_stalls, status, created_at, updated_at)
        VALUES (@admin_id, @name, @location, @start_date, @end_date, @total_stalls, 'upcoming', GETDATE(), GETDATE());

        SET @fair_id = SCOPE_IDENTITY();

        -- 2. Generate Fair Days using a WHILE loop (more efficient than a Cursor for this case)
        DECLARE @current_date DATE = @start_date;

        WHILE @current_date <= @end_date
        BEGIN
            INSERT INTO fair_days (fair_id, day_date, max_visitors, visitors_count, created_at, updated_at)
            VALUES (@fair_id, @current_date, @default_limit, 0, GETDATE(), GETDATE());

            SET @current_date = DATEADD(day, 1, @current_date);
        END

        COMMIT TRANSACTION;
        
        -- Return the created Fair ID for the application to use
        SELECT @fair_id AS NewFairID;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;

        -- Raise the error to the calling application
        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        DECLARE @ErrorSeverity INT = ERROR_SEVERITY();
        DECLARE @ErrorState INT = ERROR_STATE();

        RAISERROR(@ErrorMessage, @ErrorSeverity, @ErrorState);
    END CATCH
END;
