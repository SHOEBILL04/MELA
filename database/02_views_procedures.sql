-- 02_views_procedures.sql

-- View: FairSummary
-- Shows total stalls, employees, and events for each fair
IF OBJECT_ID('dbo.vw_FairSummary', 'V') IS NOT NULL DROP VIEW dbo.vw_FairSummary;
GO

CREATE VIEW dbo.vw_FairSummary
AS
SELECT 
    f.Fair_Name,
    f.Location,
    (SELECT COUNT(*) FROM dbo.Stalls s WHERE s.Fair_ID = f.Fair_ID) AS Total_Stalls,
    (SELECT COUNT(*) FROM dbo.Employees e WHERE e.Fair_ID = f.Fair_ID) AS Total_Employees,
    (SELECT COUNT(*) FROM dbo.Events ev WHERE ev.Fair_ID = f.Fair_ID) AS Total_Events
FROM dbo.Fairs f;
GO

-- Procedure: AddVisitor
-- Helper to register a visitor
IF OBJECT_ID('dbo.sp_AddVisitor', 'P') IS NOT NULL DROP PROCEDURE dbo.sp_AddVisitor;
GO

CREATE PROCEDURE dbo.sp_AddVisitor
    @Name NVARCHAR(100),
    @Age INT,
    @Gender NVARCHAR(10),
    @Contact NVARCHAR(20)
AS
BEGIN
    SET NOCOUNT ON;
    INSERT INTO dbo.Visitors (Visitor_Name, Age, Gender, Contact_Number)
    VALUES (@Name, @Age, @Gender, @Contact);
    
    SELECT SCOPE_IDENTITY() AS Visitor_ID;
END;
GO

-- Procedure: BuyTicket
-- Transactional ticket purchase for a visitor
IF OBJECT_ID('dbo.sp_BuyTicket', 'P') IS NOT NULL DROP PROCEDURE dbo.sp_BuyTicket;
GO

CREATE PROCEDURE dbo.sp_BuyTicket
    @Visitor_ID INT,
    @Ticket_Type NVARCHAR(50),
    @Price DECIMAL(10, 2),
    @Visit_Date DATE
AS
BEGIN
    SET NOCOUNT ON;

    -- Optional: Check if visitor exists
    IF NOT EXISTS (SELECT 1 FROM dbo.Visitors WHERE Visitor_ID = @Visitor_ID)
    BEGIN
        SELECT 0 AS Success, 'Visitor not found' AS Message;
        RETURN;
    END


    INSERT INTO dbo.Tickets (Visitor_ID, Ticket_Type, Price, Visit_Date)
    VALUES (@Visitor_ID, @Ticket_Type, @Price, @Visit_Date);

    SELECT 1 AS Success, SCOPE_IDENTITY() AS Ticket_ID;
END;
GO
