const { sql, poolPromise } = require('../config/db');

class StallRepository {
    async create(data) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('stall_id', sql.Int, data.stall_id) // Your FK
            .input('fair_id', sql.Int, data.fair_id)
            .input('stall_name', sql.NVarChar, data.stall_name)
            .input('stall_type', sql.NVarChar, data.stall_type)
            .input('rent_amount', sql.Decimal(10, 2), data.rent_amount)
            .input('location', sql.NVarChar, data.location)
            .input('stall_number', sql.NVarChar, data.stall_number)
            .query(`INSERT INTO stall (stall_id, fair_id, stall_name, stall_type, rent_amount, location, stall_number) 
                    VALUES (@stall_id, @fair_id, @stall_name, @stall_type, @rent_amount, @location, @stall_number)`);
        
        return result;
    }

    async getAllByFair(fair_id) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('fair_id', sql.Int, fair_id)
            .query('SELECT * FROM stall WHERE fair_id = @fair_id');
        return result.recordset;
    }

async getAllWithFairDetails() {
    const pool = await poolPromise;
    const result = await pool.request()
        .query(`
            SELECT 
                s.stall_id, 
                s.stall_name, 
                s.stall_type, 
                s.location, 
                s.stall_number,
                f.Fair_Name, 
                f.Location as Fair_Location
            FROM stall s
            INNER JOIN fair f ON s.fair_id = f.fair_id
        `);
    return result.recordset;
}
}

module.exports = new StallRepository();