const app = require('./app');
require('dotenv').config();

const { connectDB } = require('./config/db');
const db = require('./models');

const PORT = process.env.PORT || 5000;

// Connect and Sync Code-First DB then start server
connectDB().then(async () => {
    try {
        await db.sequelize.sync(); // Removed { alter: true } as MSSQL fails creating UNIQUE constraints with ALTER COLUMN
        console.log('Database synchronized based on Code-First models.');

        app.listen(PORT, () => {
            console.log(`Server running on port ${PORT}`);
        });
    } catch (err) {
        console.error('Failed to sync database:', err);
    }
});