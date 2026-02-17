-- 03_seed.sql
-- Sample data for Fair Management System

-- 1. Insert Fair
INSERT INTO dbo.Fairs (Fair_Name, Location, Start_Date, End_Date, Organizer_Name)
VALUES ('Dhaka International Trade Fair 2026', 'Purbachal, Dhaka', '2026-01-01', '2026-01-31', 'Export Promotion Bureau');

DECLARE @FairID INT = SCOPE_IDENTITY();

-- 2. Insert Stalls
INSERT INTO dbo.Stalls (Stall_Name, Stall_Type, Rent_Amount, Fair_ID)
VALUES 
('Tasty Treats', 'Food', 50000.00, @FairID),
('Fashion World', 'Clothes', 75000.00, @FairID),
('Tech Gadgets', 'Electronics', 100000.00, @FairID);

-- Get Stall IDs for Vendors (assuming sequential insertion for simplicity in seed script)
DECLARE @Stall1 INT = (SELECT TOP 1 Stall_ID FROM dbo.Stalls WHERE Stall_Name = 'Tasty Treats');
DECLARE @Stall2 INT = (SELECT TOP 1 Stall_ID FROM dbo.Stalls WHERE Stall_Name = 'Fashion World');

-- 3. Insert Vendors
INSERT INTO dbo.Vendors (Vendor_Name, Phone_Number, Address, Stall_ID)
VALUES 
('Rahim Foods', '01711123456', 'Dhaka, Bangladesh', @Stall1),
('Sultana Fashion', '01811223344', 'Chittagong, Bangladesh', @Stall2);

-- 4. Insert Employees
INSERT INTO dbo.Employees (Employee_Name, Role, Phone_Number, Salary, Fair_ID)
VALUES 
('Abdul Kuddus', 'Security', '01999887766', 15000.00, @FairID),
('Jorina Begum', 'Cleaner', '01666554433', 10000.00, @FairID),
('Kamal Hossain', 'Manager', '01555443322', 40000.00, @FairID);

-- 5. Insert Events
INSERT INTO dbo.Events (Event_Name, Event_Type, Event_Date, Start_Time, End_Time, Fair_ID)
VALUES 
('Opening Ceremony', 'Ceremony', '2026-01-01', '10:00', '12:00', @FairID),
('Folk Music Concert', 'Concert', '2026-01-10', '18:00', '22:00', @FairID);

-- 6. Insert Visitor
INSERT INTO dbo.Visitors (Visitor_Name, Age, Gender, Contact_Number)
VALUES ('Karim Uddin', 30, 'Male', '01333221100');

DECLARE @VisitorID INT = SCOPE_IDENTITY();

-- 7. Insert Ticket
INSERT INTO dbo.Tickets (Ticket_Type, Price, Visit_Date, Visitor_ID)
VALUES ('Adult', 50.00, '2026-01-05', @VisitorID);
