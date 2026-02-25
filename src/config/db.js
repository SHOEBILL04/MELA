const { Sequelize } = require('sequelize');
require('dotenv').config();

const sequelize = new Sequelize(
    process.env.DB_NAME,
    process.env.DB_USER,
    process.env.DB_PASSWORD,
    {
        host: process.env.DB_SERVER,
        dialect: 'mssql',
        dialectOptions: {
            options: {
                encrypt: true,
                trustServerCertificate: true
            }
        },
        pool: {
            max: 10,
            min: 0,
            idle: 30000
        },
        logging: false // Set to true to see SQL queries in console
    }
);

const connectDB = async () => {
    try {
        await sequelize.authenticate();
        console.log('Successfully Connected to MSSQL Database using Sequelize:', process.env.DB_NAME);
    } catch (error) {
        console.error('Database Connection Failed! Check your .env values.');
        console.error('Error Details:', error.message);
    }
};

module.exports = { sequelize, connectDB };