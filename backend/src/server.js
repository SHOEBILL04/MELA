const app = require('./app');
const { connectDB } = require('./config/db');
const db = require('./models');
require('dotenv').config();

const PORT = process.env.PORT || 5000;

// Connect and Sync Database then start server
connectDB().then(async () => {
    try {
        // Since we are using manual SQL scripts for schema (01_schema.sql), 
        // we disable 'alter: true' to avoid conflicts with existing constraints.
        await db.sequelize.sync({ alter: false });
        console.log('Database synchronized.');

        app.listen(PORT, () => {
            console.log(`Server running on port ${PORT}`);
        });
    } catch (err) {
        console.error('Failed to sync database:', err);
        process.exit(1);
    }
}).catch(err => {
    console.error('Failed to connect to database:', err);
    process.exit(1);
});
