const { sequelize } = require('./backend/src/config/db');

async function check() {
  try {
    const [results, metadata] = await sequelize.query("EXEC sp_columns @table_name = 'Fairs'");
    console.log(results.map(r => ({ name: r.COLUMN_NAME, type: r.TYPE_NAME, default: r.COLUMN_DEF })));
  } catch (e) {
    console.error(e);
  } finally {
    await sequelize.close();
  }
}
check();
