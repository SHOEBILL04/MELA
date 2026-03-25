CREATE OR ALTER PROCEDURE usp_CreateFair
    @admin_id INT,
    @name NVARCHAR(150),
    @location NVARCHAR(200),
    @start_date DATE,
    @end_date DATE,
    @total_stalls INT,
    @default_visitor_limit INT,
    @default_stall_price DECIMAL(10,2),
    @default_ticket_price DECIMAL(10,2)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRANSACTION;

    BEGIN TRY
        -- Insert Fair
        INSERT INTO fairs (admin_id, name, location, start_date, end_date, total_stalls, status, default_ticket_price)
        VALUES (@admin_id, @name, @location, @start_date, @end_date, @total_stalls, 'upcoming', @default_ticket_price);

        DECLARE @fair_id INT = SCOPE_IDENTITY();
        
        -- Generate Fair Days
        DECLARE @current_date DATE = @start_date;
        WHILE @current_date <= @end_date
        BEGIN
            INSERT INTO fair_days (fair_id, day_date, max_visitors, visitors_count)
            VALUES (@fair_id, @current_date, @default_visitor_limit, 0);

            SET @current_date = DATEADD(day, 1, @current_date);
        END

        -- Generate Stalls
        DECLARE @stall_counter INT = 1;
        DECLARE @stall_prefix NVARCHAR(10) = 'ST-' + CAST(@fair_id AS NVARCHAR(10)) + '-';
        
        WHILE @stall_counter <= @total_stalls
        BEGIN
            INSERT INTO stalls (fair_id, stall_number, max_employees, price, status, created_at, updated_at)
            VALUES (@fair_id, @stall_prefix + CAST(@stall_counter AS NVARCHAR(10)), 4, @default_stall_price, 'available', GETDATE(), GETDATE());
            
            SET @stall_counter = @stall_counter + 1;
        END

        COMMIT TRANSACTION;
        -- Return the newly created fair ID using the expected column name
        SELECT @fair_id AS NewFairID;
    END TRY
    BEGIN CATCH
        ROLLBACK TRANSACTION;
        THROW;
    END CATCH
END