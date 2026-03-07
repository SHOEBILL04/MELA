require('dotenv').config();
const { sequelize } = require('./src/config/db');

async function fixDb() {
    try {
        await sequelize.authenticate();
        console.log('Successfully Connected to Database.');

        // 1. Add missing Name column to Users table
        const [userCols] = await sequelize.query(`
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'Users' AND COLUMN_NAME = 'Name'
        `);

        if (userCols.length === 0) {
            console.log('Column "Name" does not exist in Users. Adding it.');
            await sequelize.query(`ALTER TABLE Users ADD Name NVARCHAR(100);`);
            console.log('Column "Name" added to Users table.');
        } else {
            console.log('Column "Name" already exists in Users table.');
        }

        // 2. Add missing Daily_Ticket_Limit to Fairs table (if not already there)
        const [fairCols] = await sequelize.query(`
            SELECT COLUMN_NAME 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'Fairs' AND COLUMN_NAME = 'Daily_Ticket_Limit'
        `);

        if (fairCols.length === 0) {
            console.log('Column "Daily_Ticket_Limit" does not exist in Fairs. Adding it.');
            await sequelize.query(`ALTER TABLE Fairs ADD Daily_Ticket_Limit INT NOT NULL DEFAULT 1000;`);
            console.log('Column "Daily_Ticket_Limit" added to Fairs table.');
        } else {
            console.log('Column "Daily_Ticket_Limit" already exists in Fairs table.');
        }

        console.log('Database fix complete.');

    } catch (e) {
        console.error('Error during database fix:', e.message);
    } finally {
        await sequelize.close();
        process.exit(0);
    }
}
fixDb();
