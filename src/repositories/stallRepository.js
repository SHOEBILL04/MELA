const { sql, poolPromise } = require('../config/db');

class StallRepository {
    async create(data) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Fair_ID', sql.Int, data.Fair_ID)
            .input('Stall_Name', sql.NVarChar, data.Stall_Name)
            .input('Stall_Type', sql.NVarChar, data.Stall_Type)
            .input('Rent_Amount', sql.Decimal(10, 2), data.Rent_Amount)
            .query(`INSERT INTO Stalls (Fair_ID, Stall_Name, Stall_Type, Rent_Amount) 
                    OUTPUT inserted.Stall_ID
                    VALUES (@Fair_ID, @Stall_Name, @Stall_Type, @Rent_Amount)`);

        return result.recordset[0];
    }

    async getAllByFair(Fair_ID) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Fair_ID', sql.Int, Fair_ID)
            .query('SELECT * FROM Stalls WHERE Fair_ID = @Fair_ID');
        return result.recordset;
    }

    async getAllWithFairDetails() {
        const pool = await poolPromise;
        const result = await pool.request()
            .query(`
                SELECT 
                    s.Stall_ID, 
                    s.Stall_Name, 
                    s.Stall_Type, 
                    s.Rent_Amount,
                    f.Fair_Name, 
                    f.Location as Fair_Location
                FROM Stalls s
                INNER JOIN Fairs f ON s.Fair_ID = f.Fair_ID
            `);
        return result.recordset;
    }
}

module.exports = new StallRepository();