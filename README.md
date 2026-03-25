<div align="center">
    <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="300" alt="Laravel Logo">
    <h1>Mela Fair Management System</h1>
    <p>A comprehensive, database-first platform for managing large-scale cultural fairs, stall bookings, and ticketing operations.</p>
</div>

---

## 🚀 Overview

The **Mela Fair Management System** is a robust web application tailored to handle the complex logistical requirements of cultural festivals and fairs. Built on a stringent **Database-First Architecture**, it offloads critical concurrency checks and business logic down to the SQL Server layer via Stored Procedures and Triggers, ensuring maximum data integrity even during high-traffic checkout surges.

## 🎯 Key Features by Role

### 👑 Administrator
- **Fair Deployment Engine**: Create new interactive fairs. The system automatically provisions and maps structural database records (Available Stalls, Operating Fair Days, etc.) in the background.
- **Dynamic Pricing Controls**: Define global base prices for stall leases and visitor admission tickets simultaneously upon fair creation.
- **Deep Analytics**: Instantly monitor revenue and foot traffic across all operating fairs via highly optimized SQL Views.
- **Cascade Demolition**: Safely delete entire fair structures with secure cascading constraints.

### 🏪 Vendor
- **Live Inventory Market**: Discover active and upcoming Mela Fairs locally.
- **Interactive Stall Booking**: Choose your desired fair and view real-time stall capacity and lease prices.
- **High-Concurrency Bulk Checkout**: Seamlessly reserve massive blocks of stalls at once. Powered by `UPDLOCK` and `ROWLOCK`, guaranteeing accurate allocation without overselling.

### 🎟️ Visitor
- **Date-Specific Planning**: Browse all upcoming fairs, organized hierarchically by specific active operating dates.
- **Availability Matrix**: Check real-time MELA remaining admission capacities for any given day.
- **Bulk Ticketing**: Secure admission for entire groups in one seamless transaction.

### 👷 Employee
- **Job Recruitment Portal**: Browse local job openings at active fairs.
- **Direct Application**: Apply directly for positions via secure SQL stored procedures.

---

## 🛠️ Technology Stack

- **Backend Framework**: [Laravel 11](https://laravel.com/) (PHP)
- **Database Engine**: Microsoft SQL Server
- **Frontend Styling**: [Tailwind CSS v4](https://tailwindcss.com/)
- **Architecture**: Database-First (SPs, Triggers, Views)

## 🗄️ Database Architecture

At the heart of Mela is a strict Database-First philosophy. The Laravel backend primarily acts as a routing orchestrator, delegating heavy lifting to SQL Server:

- **Stored Procedures**: `usp_CreateFair`, `usp_BuyStall`, `usp_BuyFairTicket`, `usp_RecruitEmployee`
- **Preventative Triggers**: `trg_PreventEventOversell`, `trg_StallSoldCountUpdate`, `trg_PreventDuplicateApplication`
- **Analytical Views**: `vw_FairSummary`, `vw_DailyVisitorCount`, `vw_VisitorTickets`

All financial checkout procedures utilize **Transaction Scopes** and **Row-level locking (`UPDLOCK, ROWLOCK`)** to prevent race conditions during peak purchase bursts.

---

## 💻 Installation

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Copy `.env.example` to `.env` and configure your **SQL Server** database connection.
4. Generate the application key:
   ```bash
   php artisan key:generate
   ```
5. Run the migration engine (this deploys tables, constraints, triggers, and all SPs):
   ```bash
   php artisan migrate
   ```
6. Compile modern Tailwind CSS assets:
   ```bash
   npm run build
   ```
7. Boot the server!
   ```bash
   php artisan serve
   ```

---
*Built to power the next generation of seamless cultural festivals.*
