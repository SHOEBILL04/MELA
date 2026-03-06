require('dotenv').config();
const { sequelize } = require('./src/config/db');

async function checkSchema() {
    try {
        await sequelize.authenticate();
        console.log('Connection established.');

        const [columns] = await sequelize.query(`
            SELECT COLUMN_NAME, IS_NULLABLE, DATA_TYPE 
            FROM INFORMATION_SCHEMA.COLUMNS 
            WHERE TABLE_NAME = 'Vendors'
        `);
        console.log('Vendors Columns:', columns);

        const [foreignKeys] = await sequelize.query(`
            SELECT 
                fk.name AS ForeignKey,
                tp.name AS ParentTable,
                cp.name AS ParentColumn,
                tr.name AS RefTable,
                cr.name AS RefColumn
            FROM 
                sys.foreign_keys fk
            INNER JOIN 
                sys.tables tp ON fk.parent_object_id = tp.object_id
            INNER JOIN 
                sys.tables tr ON fk.referenced_object_id = tr.object_id
            INNER JOIN 
                sys.foreign_key_columns fkc ON fkc.constraint_object_id = fk.object_id
            INNER JOIN 
                sys.columns cp ON fkc.parent_column_id = cp.column_id AND fkc.parent_object_id = cp.object_id
            INNER JOIN 
                sys.columns cr ON fkc.referenced_column_id = cr.column_id AND fkc.referenced_object_id = cr.object_id
            WHERE tp.name = 'Vendors'
        `);
        console.log('Vendors Foreign Keys:', foreignKeys);

    } catch (err) {
        console.error('Error:', err);
    } finally {
        await sequelize.close();
    }
}

checkSchema();
