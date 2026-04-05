CREATE TRIGGER trg_PreventDuplicateApplication
ON applications
INSTEAD OF INSERT
AS
BEGIN
    SET NOCOUNT ON;

    -- Check if the employee already applied for the specific position
    IF EXISTS (
        SELECT 1 
        FROM applications a
        INNER JOIN inserted i ON a.employee_id = i.employee_id AND a.position_id = i.position_id
    )
    BEGIN
        RAISERROR ('You have already applied for this position.', 16, 1);
        ROLLBACK TRANSACTION;
        RETURN;
    END

    -- If no duplicate, proceed with insertion
    INSERT INTO applications (
        employee_id,
        position_id,
        applicant_name,
        applicant_age,
        applicant_gender,
        home_location,
        education_status,
        status,
        applied_at,
        created_at,
        updated_at
    )
    SELECT
        employee_id,
        position_id,
        applicant_name,
        applicant_age,
        applicant_gender,
        home_location,
        education_status,
        ISNULL(status, 'pending'),
        ISNULL(applied_at, CURRENT_TIMESTAMP),
        created_at,
        updated_at
    FROM inserted;
END;
