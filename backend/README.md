# Fair Management System (Mela) - Database Project

## Overview
This project contains the MSSQL database schema for a Fair Management System.
It manages Fairs, Stalls, Vendors, Visitors, Tickets, Employees, and Events.

## Database Structure
The database consists of 7 main tables:
1.  **Fairs**: Information about the fair events.
2.  **Stalls**: Shops allocated within a fair.
3.  **Vendors**: Owners renting the stalls.
4.  **Visitors**: People visiting the fair.
5.  **Tickets**: Entry passes purchased by visitors.
6.  **Employees**: Staff working at the fair.
7.  **Events**: Special programs hosted during the fair.

## How to Run / Test

### Prerequisites
- Microsoft SQL Server installed.
- SQL Server Management Studio (SSMS) OR Azure Data Studio OR `sqlcmd` command line tool.

### Steps to Initialize Database

1.  **Open your SQL Tool** (SSMS or Azure Data Studio).
2.  **Connect** to your SQL Server instance.
3.  **Create the Database** (if not already created):
    ```sql
    CREATE DATABASE MelaDB;
    GO
    USE MelaDB;
    GO
    ```
4.  **Run the Scripts** in the following order from the `database/` folder:

    *   **Step 1: Create Schema**
        Open and execute `database/01_schema.sql`.
        *Action*: This creates all the tables and relationships.

    *   **Step 2: Create Procedures & Views**
        Open and execute `database/02_views_procedures.sql`.
        *Action*: This creates helper procedures like `sp_BuyTicket` and views like `vw_FairSummary`.

    *   **Step 3: Load Sample Data**
        Open and execute `database/03_seed.sql`.
        *Action*: This populates the tables with dummy data for testing.

### Verification Queries

After running the scripts, you can run these queries to verify everything is working:

**1. View All Fairs**
```sql
SELECT * FROM dbo.Fairs;
```

**2. Check Fair Summary (View)**
```sql
SELECT * FROM dbo.vw_FairSummary;
```
*Expected*: Should show 'Dhaka International Trade Fair 2026' with counts for stalls, employees, etc.

**3. Test Purchasing a Ticket**

**Step A: Register a new Visitor**
Run this to add a user and see their new ID:
```sql
DECLARE @NewVisitorID INT;
EXEC @NewVisitorID = dbo.sp_AddVisitor 
    @Name = 'Test User', 
    @Age = 25, 
    @Gender = 'Male', 
    @Contact = '01700000000';
SELECT @NewVisitorID AS 'New_Visitor_ID';
```
*Take note of the ID returned (e.g., `2`).*

**Step B: Buy a Ticket**
Replace `2` below with the actual Visitor ID you got from Step A.
```sql
EXEC dbo.sp_BuyTicket 
    @Visitor_ID = 2,  -- CHANGE THIS to your Visitor ID
    @Ticket_Type = 'VIP', 
    @Price = 100.00, 
    @Visit_Date = '2026-01-15';
```

**Step C: Verify**
## API Endpoints (Node.js)

Ensure your server is running (`npm start` or `npm run dev`).

### 1. Fairs
- **GET** `/api/fairs`: List all fairs.
- **POST** `/api/fairs`: Create a fair.
  ```json
  {
    "Fair_Name": "Summer IT Fair",
    "Location": "Dhaka",
    "Start_Date": "2026-06-01",
    "End_Date": "2026-06-05",
    "Organizer_Name": "BCC"
  }
  ```

### 2. Stalls
- **GET** `/api/stalls/fair/:fair_id`: Get stalls for a specific fair.
- **GET** `/api/stalls`: Get all stalls with fair details.
- **POST** `/api/stalls`: Add a stall.
  ```json
  {
    "Fair_ID": 1,
    "Stall_Name": "Tech Shop",
    "Stall_Type": "Electronics",
    "Rent_Amount": 12000.00
  }
  ```

### 3. Visitors & Tickets
- **POST** `/api/visitors`: Register a visitor.
  ```json
  {
    "Visitor_Name": "John Doe",
    "Age": 30,
    "Gender": "Male",
    "Contact_Number": "01711223344"
  }
  ```
- **POST** `/api/visitors/ticket`: Buy a ticket.
  ```json
  {
    "Visitor_ID": 1,
    "Ticket_Type": "Adult",
    "Price": 50.00,
    "Visit_Date": "2026-06-02"
  }
  ```
- **GET** `/api/visitors/:visitor_id/tickets`: Get tickets for a visitor.

### 4. Reports
- **GET** `/api/reports/summary`: Get summary statistics for all fairs.

