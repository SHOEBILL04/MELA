require('dotenv').config();
const { sequelize } = require('./src/config/db');

async function drop() {
    try {
        // Build dynamic SQL to drop all foreign keys then tables
        await sequelize.query(`
            DECLARE @Sql NVARCHAR(MAX) = '';

            -- Drop all foreign key constraints
            SELECT @Sql += 'ALTER TABLE ' + QUOTENAME(schema_name(schema_id)) + '.' + QUOTENAME(OBJECT_NAME(parent_object_id)) 
                + ' DROP CONSTRAINT ' + QUOTENAME(name) + ';'
            FROM sys.foreign_keys;

            EXEC sp_executesql @Sql;

            -- Drop all tables
            SET @Sql = '';
            SELECT @Sql += 'DROP TABLE ' + QUOTENAME(schema_name(schema_id)) + '.' + QUOTENAME(name) + ';'
            FROM sys.tables;

            EXEC sp_executesql @Sql;
        `);
        console.log('Tables dropped successfully.');
    } catch (err) {
        console.error('Drop error:', err);
    } finally {
        process.exit();
    }
}
drop();
