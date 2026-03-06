const express = require('express');
const cors = require('cors');
const morgan = require('morgan');
const fairRoutes = require('./routes/fairRoutes');
const stallRoutes = require('./routes/stallRoutes');
const visitorRoutes = require('./routes/visitorRoutes');
const reportRoutes = require('./routes/reportRoutes');
require('dotenv').config();

const { connectDB } = require('./config/db');
const db = require('./models');

const app = express();

//Middleware
app.use(cors());
app.use(morgan('dev'));
app.use(express.json());
app.use('/api', fairRoutes);
app.use('/api/stalls', stallRoutes);
app.use('/api/visitors', visitorRoutes);
app.use('/api/reports', reportRoutes);

app.get('/', (req, res) => {
    res.send('API is running...');
});
const PORT = process.env.PORT || 5000;

// Connect and Sync Code-First DB then start server
connectDB().then(async () => {
    try {
        await db.sequelize.sync({ alter: true }); // Sync models to DB (alter true to auto-create missing tables/columns)
        console.log('Database synchronized based on Code-First models.');

        app.listen(PORT, () => {
            console.log(`Server running on port ${PORT}`);
        });
    } catch (err) {
        console.error('Failed to sync database:', err);
    }
});