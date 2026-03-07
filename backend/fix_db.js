require('dotenv').config();
const { sequelize } = require('./src/config/db');

async function fixDb() {
    try {
        await sequelize.authenticate();
        console.log('Connected.');

        // Check if column exists
        const [results] = await sequelize.query(`
      SELECT COLUMN_NAME, IS_NULLABLE, COLUMN_DEFAULT 
      FROM INFORMATION_SCHEMA.COLUMNS 
      WHERE TABLE_NAME = 'Fairs' AND COLUMN_NAME = 'Daily_Ticket_Limit'
    `);

        if (results.length === 0) {
            console.log('Column does not exist. Adding it.');
            await sequelize.query(`ALTER TABLE Fairs ADD Daily_Ticket_Limit INT NOT NULL DEFAULT 1000;`);
            console.log('Column added.');
        } else {
            console.log('Column exists:', results[0]);
            // If it exists but is nullable or has no default, we need to fix it.
            // Easiest is to drop and recreate, but we might have data.
            // Let's add the default constraint explicitly if missing.
            await sequelize.query(`
        IF NOT EXISTS (
            SELECT * FROM sys.default_constraints 
            WHERE parent_object_id = OBJECT_ID('Fairs') 
            AND col_name(parent_object_id, parent_column_id) = 'Daily_Ticket_Limit'
        )
        BEGIN
            ALTER TABLE Fairs ADD CONSTRAINT DF_Fairs_Daily_Ticket_Limit DEFAULT 1000 FOR Daily_Ticket_Limit;
        END
      `);

            // And make it NOT NULL
            if (results[0].IS_NULLABLE === 'YES') {
                await sequelize.query(`UPDATE Fairs SET Daily_Ticket_Limit = 1000 WHERE Daily_Ticket_Limit IS NULL;`);
                await sequelize.query(`ALTER TABLE Fairs ALTER COLUMN Daily_Ticket_Limit INT NOT NULL;`);
            }
            console.log('Column altered.');
        }
    } catch (e) {
        console.error('Error:', e);
    } finally {
        await sequelize.close();
    }
}
fixDb();
