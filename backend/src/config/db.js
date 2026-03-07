const { Sequelize } = require('sequelize');
require('dotenv').config();

const sequelize = new Sequelize(
    process.env.DB_DATABASE, // DB_NAME er poriborte DB_DATABASE hobe
    process.env.DB_USER,
    process.env.DB_PASSWORD,
    {
        host: process.env.DB_SERVER,
        dialect: 'mssql',
        port: parseInt(process.env.DB_PORT) || 1433, // Port add kora bhalo
        dialectOptions: {
            options: {
                encrypt: true,
                trustServerCertificate: true
            }
        },
        // ... baki shob thik ache
    }
);

const connectDB = async () => {
    try {
        await sequelize.authenticate();
        // Ekhaneo DB_DATABASE hobe
        console.log('Successfully Connected to MSSQL Database:', process.env.DB_DATABASE);
    } catch (error) {
        // ...
    }
};

module.exports = { sequelize, connectDB };