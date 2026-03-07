-- 03_seed.sql
-- Sample data for Fair Management System

-- 0. Insert Users
INSERT INTO dbo.Users (Email, Password, Role)
VALUES 
('admin@mela.com', 'admin123', 'Admin'),
('owner@mela.com', 'owner123', 'FairOwner'),
('vendor1@mela.com', 'vendor123', 'Vendor'),
('vendor2@mela.com', 'vendor123', 'Vendor'),
('visitor@mela.com', 'visitor123', 'Visitor'),
('emp1@mela.com', 'emp123', 'Employee'),
('emp2@mela.com', 'emp123', 'Employee'),
('emp3@mela.com', 'emp123', 'Employee');

-- Store IDs (Assume they get IDs 1 to 8 sequentially)
DECLARE @OwnerUserID INT = 2;
DECLARE @Vendor1UserID INT = 3;
DECLARE @Vendor2UserID INT = 4;
DECLARE @VisitorUserID INT = 5;
DECLARE @Emp1UserID INT = 6;
DECLARE @Emp2UserID INT = 7;
DECLARE @Emp3UserID INT = 8;

-- 1. Insert Fair
INSERT INTO dbo.Fairs (Fair_Name, Location, Start_Date, End_Date, Organizer_ID)
VALUES ('Dhaka International Trade Fair 2026', 'Purbachal, Dhaka', '2026-01-01', '2026-01-31', @OwnerUserID);

DECLARE @FairID INT = SCOPE_IDENTITY();

-- 2. Insert Stalls
INSERT INTO dbo.Stalls (Stall_Name, Stall_Type, Rent_Amount, Fair_ID)
VALUES 
('Tasty Treats', 'Food', 50000.00, @FairID),
('Fashion World', 'Clothes', 75000.00, @FairID),
('Tech Gadgets', 'Electronics', 100000.00, @FairID);

-- Get Stall IDs for Vendors 
DECLARE @Stall1 INT = (SELECT TOP 1 Stall_ID FROM dbo.Stalls WHERE Stall_Name = 'Tasty Treats');
DECLARE @Stall2 INT = (SELECT TOP 1 Stall_ID FROM dbo.Stalls WHERE Stall_Name = 'Fashion World');

-- 3. Insert Vendors (Corrected: Stall_ID removed from here)
INSERT INTO dbo.Vendors (Vendor_Name, Phone_Number, Address, User_ID)
VALUES 
('Rahim Foods', '01711123456', 'Dhaka, Bangladesh', @Vendor1UserID),
('Sultana Fashion', '01811223344', 'Chittagong, Bangladesh', @Vendor2UserID);

DECLARE @Vendor1ID INT = (SELECT TOP 1 Vendor_ID FROM dbo.Vendors WHERE User_ID = @Vendor1UserID);
DECLARE @Vendor2ID INT = (SELECT TOP 1 Vendor_ID FROM dbo.Vendors WHERE User_ID = @Vendor2UserID);

-- Assign Vendors to Stalls (Corrected)
UPDATE dbo.Stalls SET Vendor_ID = @Vendor1ID WHERE Stall_ID = @Stall1;
UPDATE dbo.Stalls SET Vendor_ID = @Vendor2ID WHERE Stall_ID = @Stall2;

-- 4. Insert Employees
INSERT INTO dbo.Employees (Employee_Name, Role, Phone_Number, Salary, Fair_ID, User_ID)
VALUES 
('Abdul Kuddus', 'Security', '01999887766', 15000.00, @FairID, @Emp1UserID),
('Jorina Begum', 'Cleaner', '01666554433', 10000.00, @FairID, @Emp2UserID),
('Kamal Hossain', 'Manager', '01555443322', 40000.00, @FairID, @Emp3UserID);

-- 5. Insert Events (Corrected: Added Organizer_ID)
INSERT INTO dbo.Events (Event_Name, Event_Type, Event_Date, Start_Time, End_Time, Fair_ID, Organizer_ID)
VALUES 
('Opening Ceremony', 'Ceremony', '2026-01-01', '10:00', '12:00', @FairID, @OwnerUserID),
('Folk Music Concert', 'Concert', '2026-01-10', '18:00', '22:00', @FairID, @OwnerUserID);

-- 6. Insert Visitor
INSERT INTO dbo.Visitors (Visitor_Name, Age, Gender, Contact_Number, User_ID)
VALUES ('Karim Uddin', 30, 'Male', '01333221100', @VisitorUserID);

DECLARE @VisitorID INT = SCOPE_IDENTITY();

-- 7. Insert Ticket (Corrected: Added Fair_ID)
INSERT INTO dbo.Tickets (Ticket_Type, Price, Visit_Date, Visitor_ID, Fair_ID)
VALUES ('Adult', 50.00, '2026-01-05', @VisitorID, @FairID);
