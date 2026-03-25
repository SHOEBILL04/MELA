-- 
CREATE PROCEDURE usp_CreateFair
    @admin_id INT,
    @name NVARCHAR(150),
    @location NVARCHAR(200),
    @start_date DATE,
    @end_date DATE,
    @total_stalls INT,
    @default_visitor_limit INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRANSACTION;

    BEGIN TRY
        INSERT INTO fairs (admin_id, name, location, start_date, end_date, total_stalls, status)
        VALUES (@admin_id, @name, @location, @start_date, @end_date, @total_stalls, 'upcoming');

        DECLARE @fair_id INT = SCOPE_IDENTITY();
        DECLARE @current_date DATE = @start_date;

        
        WHILE @current_date <= @end_date
        BEGIN
            INSERT INTO fair_days (fair_id, day_date, max_visitors, visitors_count)
            VALUES (@fair_id, @current_date, @default_visitor_limit, 0);

            SET @current_date = DATEADD(day, 1, @current_date);
        END

        COMMIT TRANSACTION;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW; -- 
    END CATCH
END