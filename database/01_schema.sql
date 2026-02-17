-- 01_schema.sql
-- Database Schema for Fair Management System
-- Entities: Fair, Stall, Vendor, Visitor, Ticket, Employee, Event

-- Drop tables if they exist (Order matters due to FK constraints)
IF OBJECT_ID('dbo.Events', 'U') IS NOT NULL DROP TABLE dbo.Events;
IF OBJECT_ID('dbo.Employees', 'U') IS NOT NULL DROP TABLE dbo.Employees;
IF OBJECT_ID('dbo.Tickets', 'U') IS NOT NULL DROP TABLE dbo.Tickets;
IF OBJECT_ID('dbo.Visitors', 'U') IS NOT NULL DROP TABLE dbo.Visitors;
IF OBJECT_ID('dbo.Vendors', 'U') IS NOT NULL DROP TABLE dbo.Vendors;
IF OBJECT_ID('dbo.Stalls', 'U') IS NOT NULL DROP TABLE dbo.Stalls;
IF OBJECT_ID('dbo.Fairs', 'U') IS NOT NULL DROP TABLE dbo.Fairs;

-- 1. Fair Table
CREATE TABLE dbo.Fairs (
    Fair_ID INT IDENTITY(1,1) PRIMARY KEY,
    Fair_Name NVARCHAR(200) NOT NULL,
    Location NVARCHAR(200) NOT NULL,
    Start_Date DATE NOT NULL,
    End_Date DATE NOT NULL,
    Organizer_Name NVARCHAR(100),
    CHECK (End_Date >= Start_Date)
);

-- 2. Stall Table
CREATE TABLE dbo.Stalls (
    Stall_ID INT IDENTITY(1,1) PRIMARY KEY,
    Stall_Name NVARCHAR(100) NOT NULL,
    Stall_Type NVARCHAR(50) NOT NULL, -- 'Food', 'Games', 'Clothes', etc.
    Rent_Amount DECIMAL(10, 2) NOT NULL,
    Fair_ID INT NOT NULL,
    CONSTRAINT FK_Stalls_Fairs FOREIGN KEY (Fair_ID) REFERENCES dbo.Fairs(Fair_ID)
);

-- 3. Vendor Table
CREATE TABLE dbo.Vendors (
    Vendor_ID INT IDENTITY(1,1) PRIMARY KEY,
    Vendor_Name NVARCHAR(100) NOT NULL,
    Phone_Number NVARCHAR(20),
    Address NVARCHAR(255),
    Stall_ID INT UNIQUE NOT NULL, -- One Stall -> One Vendor (assuming simple 1:1 for this schema)
    CONSTRAINT FK_Vendors_Stalls FOREIGN KEY (Stall_ID) REFERENCES dbo.Stalls(Stall_ID)
);

-- 4. Visitor Table
CREATE TABLE dbo.Visitors (
    Visitor_ID INT IDENTITY(1,1) PRIMARY KEY,
    Visitor_Name NVARCHAR(100) NOT NULL,
    Age INT,
    Gender NVARCHAR(10), -- 'Male', 'Female', 'Other'
    Contact_Number NVARCHAR(20)
);

-- 5. Ticket Table
CREATE TABLE dbo.Tickets (
    Ticket_ID INT IDENTITY(1,1) PRIMARY KEY,
    Ticket_Type NVARCHAR(50) NOT NULL, -- 'Adult', 'Child', 'VIP'
    Price DECIMAL(10, 2) NOT NULL,
    Visit_Date DATE NOT NULL,
    Visitor_ID INT NOT NULL,
    CONSTRAINT FK_Tickets_Visitors FOREIGN KEY (Visitor_ID) REFERENCES dbo.Visitors(Visitor_ID)
);

-- 6. Employee Table
CREATE TABLE dbo.Employees (
    Employee_ID INT IDENTITY(1,1) PRIMARY KEY,
    Employee_Name NVARCHAR(100) NOT NULL,
    Role NVARCHAR(50) NOT NULL, -- 'Security', 'Cleaner', 'Manager'
    Phone_Number NVARCHAR(20),
    Salary DECIMAL(10, 2) NOT NULL,
    Fair_ID INT NOT NULL,
    CONSTRAINT FK_Employees_Fairs FOREIGN KEY (Fair_ID) REFERENCES dbo.Fairs(Fair_ID)
);

-- 7. Event Table
CREATE TABLE dbo.Events (
    Event_ID INT IDENTITY(1,1) PRIMARY KEY,
    Event_Name NVARCHAR(200) NOT NULL,
    Event_Type NVARCHAR(50), -- 'Concert', 'Show', 'Game'
    Event_Date DATE NOT NULL,
    Start_Time TIME,
    End_Time TIME,
    Fair_ID INT NOT NULL,
    CONSTRAINT FK_Events_Fairs FOREIGN KEY (Fair_ID) REFERENCES dbo.Fairs(Fair_ID)
);

-- Indexes for performance
CREATE INDEX IX_Stalls_FairID ON dbo.Stalls(Fair_ID);
CREATE INDEX IX_Tickets_VisitorID ON dbo.Tickets(Visitor_ID);
CREATE INDEX IX_Events_FairID ON dbo.Events(Fair_ID);
