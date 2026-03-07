require('dotenv').config();
const db = require('./src/models');

async function reset() {
  try {
    await db.sequelize.authenticate();
    console.log('Connection established.');
    await db.sequelize.sync({ force: true });
    console.log('Database reset successfully with force sync.');
  } catch (err) {
    console.error('Error resetting db:', err);
  } finally {
    process.exit(0);
  }
}
reset();
