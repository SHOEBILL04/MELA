require('dotenv').config();
const db = require('./src/models');
const fs = require('fs');
const path = require('path');

async function reset() {
  try {
    await db.sequelize.authenticate();
    console.log('Connection established.');
    
    // Read and execute schema definition to ensure dropped legacy tables
    const schemaSql = fs.readFileSync(path.join(__dirname, '../database/01_schema.sql'), 'utf-8');
    await db.sequelize.query(schemaSql);
    
    await db.sequelize.sync({ force: true });
    console.log('Database reset successfully with force sync.');
  } catch (err) {
    console.error('Error resetting db:', err);
  } finally {
    process.exit(0);
  }
}
reset();
