const { sql, poolPromise } = require('../config/db');

class ReportRepository {
    async getFairSummary() {
        const pool = await poolPromise;
        const result = await pool.request().query('SELECT * FROM vw_FairSummary');
        return result.recordset;
    }
}

module.exports = new ReportRepository();
