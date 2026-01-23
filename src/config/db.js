const sql = require('mssql');
require('dotenv').config();

const dbConfig = {
    user: process.env.DB_USER,
    password: process.env.DB_PASSWORD,
    database: process.env.DB_NAME, 
    server: process.env.DB_SERVER,
    pool: {
        max: 10,
        min: 0,
        idleTimeoutMillis: 30000
    },
    options: {
        encrypt: true, 
        trustServerCertificate: true
    }
};

const poolPromise = new sql.ConnectionPool(dbConfig)
    .connect()
    .then(pool => {
        console.log('Successfully Connected to MSSQL Database:', process.env.DB_NAME);
        return pool;
    })
    .catch(err => {
        console.error('Database Connection Failed! Check your .env values.');
        console.error('Error Details:', err.message);
    });

module.exports = { sql, poolPromise };