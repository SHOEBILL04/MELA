const { sql, poolPromise } = require('../config/db');

class FairRepository {
    async getAll() {
        const pool = await poolPromise;
        const result = await pool.request().query('SELECT * FROM Fairs ORDER BY Start_Date DESC');
        return result.recordset;
    }

    async create(data) {
        const pool = await poolPromise;
        const result = await pool.request()
            .input('Fair_Name', sql.NVarChar, data.Fair_Name)
            .input('Location', sql.NVarChar, data.Location)
            .input('Start_Date', sql.Date, data.Start_Date)
            .input('End_Date', sql.Date, data.End_Date)
            .input('Organizer_Name', sql.NVarChar, data.Organizer_Name) 
            .query(`INSERT INTO Fair (Fair_Name, Location, Start_Date, End_Date, Organizer_Name) 
                    OUTPUT inserted.Fair_ID
                    VALUES (@Fair_Name, @Location, @Start_Date, @End_Date, @Organizer_Name)`);
        
        return result.recordset[0];
    }
}

module.exports = new FairRepository();