const db = require('../config/db');

const getAllVendors = async () => {
    const [rows] = await db.execute('SELECT * FROM Vendor');
    return rows;
};

module.exports = { getAllVendors };