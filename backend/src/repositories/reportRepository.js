const { sequelize } = require('../config/db');

class ReportRepository {
    async getFairSummary() {
        const [results] = await sequelize.query('SELECT * FROM vw_FairSummary');
        return results;
    }
}

module.exports = new ReportRepository();
