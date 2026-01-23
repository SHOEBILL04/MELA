const {sql, poolPromis} = require('../config/db');
class FairRepository{
    async getAll()
    {
        const pool = await poolPromis();
        const result = await pool.request().query('SELECT * FROM FAIR ORDER BY START_DATE DESC');
        return result.recordset;
    }
    async create(data){
        const pool = await poolPromis;
        const result = await pool.request()
        .input('Fair_Name', sql.NVarChar, data.Fair_Name)
        .input('Location', sql.NVarChar, data.Location)
        .input('Start_Date', sql.Date, data.Start_Date)
        .input('End_Date', sql.Date, data.End_Date)
        .query('INSERT INTO FAIR (Fair_Name, Location, Start_Date, End_Date) VALUES (@Fair_Name, @Location, @Start_Date, @End_Date)');
        return result;
    }
}
module.exports = new FairRepository();