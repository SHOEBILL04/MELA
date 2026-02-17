const { sql, poolPromise } = require('../config/db');

class VisitorRepository {
    async createVisitor(data) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Name', sql.NVarChar, data.Visitor_Name)
            .input('Age', sql.Int, data.Age)
            .input('Gender', sql.NVarChar, data.Gender)
            .input('Contact', sql.NVarChar, data.Contact_Number)
            .execute('sp_AddVisitor');

        return result.recordset[0];
    }

    async buyTicket(data) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Visitor_ID', sql.Int, data.Visitor_ID)
            .input('Ticket_Type', sql.NVarChar, data.Ticket_Type)
            .input('Price', sql.Decimal(10, 2), data.Price)
            .input('Visit_Date', sql.Date, data.Visit_Date)
            .execute('sp_BuyTicket');

        return result.recordset[0];
    }

    async getAllVisitors() {
        const pool = await poolPromise;
        const result = await pool.request().query('SELECT * FROM Visitors');
        return result.recordset;
    }

    async getTicketsByVisitor(visitor_id) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Visitor_ID', sql.Int, visitor_id)
            .query('SELECT * FROM Tickets WHERE Visitor_ID = @Visitor_ID');
        return result.recordset;
    }
}

module.exports = new VisitorRepository();
