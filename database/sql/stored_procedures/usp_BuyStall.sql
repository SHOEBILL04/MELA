CREATE OR ALTER PROCEDURE usp_BuyStall
    @vendor_id INT,
    @stall_id INT
AS
BEGIN
    SET NOCOUNT ON;

    BEGIN TRY
        -- Transaction shuru korchi jate sob operation ekshathe hoy
        BEGIN TRANSACTION;

        DECLARE @current_status NVARCHAR(20);

        -- UPDLOCK diye check korchi jate eksathe 2 jon read korte na pare
        SELECT @current_status = status
        FROM stalls WITH (UPDLOCK)
        WHERE stall_id = @stall_id;

        -- Stall available na thakle transaction batil (ROLLBACK)
        IF @current_status != 'available'
        BEGIN
            ROLLBACK TRANSACTION;
            RAISERROR('Error: Stall is already sold or reserved.', 16, 1);
            RETURN;
        END

        -- Available thakle vendor_id bosiye status 'sold' kore dibo
        UPDATE stalls
        SET vendor_id = @vendor_id,
            status = 'sold'
        WHERE stall_id = @stall_id;

        -- Sob thik thakle database e change save korar jonno transaction commit korchi
        COMMIT TRANSACTION;
        PRINT 'Success: Stall purchased successfully.';
        
    END TRY
    BEGIN CATCH
        -- Error khele sob change ager obosthay niye jabe
        IF @@TRANCOUNT > 0
            ROLLBACK TRANSACTION;

        DECLARE @ErrorMessage NVARCHAR(4000) = ERROR_MESSAGE();
        RAISERROR(@ErrorMessage, 16, 1);
    END CATCH
END;