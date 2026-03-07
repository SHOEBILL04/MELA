const app = require('./app');
const { connectDB } = require('./config/db');
const db = require('./models');
require('dotenv').config();

const PORT = process.env.PORT || 5000;

// Connect and Sync Database then start server
connectDB().then(async () => {
    try {
        // Use { alter: true } only in development. In production, use migrations.
        await db.sequelize.sync({ alter: true });
        console.log('Database synchronized based on Code-First models.');

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
