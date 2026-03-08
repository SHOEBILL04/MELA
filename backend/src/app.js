const express = require('express');
const cors = require('cors');
const morgan = require('morgan');
const authRoutes = require('./routes/authRoutes');
const stallRoutes = require('./routes/stallRoutes');
const fairRoutes = require('./routes/fairRoutes');
const visitorRoutes = require('./routes/visitorRoutes');

const app = express();

// Middleware
app.use(cors());
app.use(morgan('dev'));
app.use(express.json());

// Routes
app.use('/api/auth', authRoutes);
app.use('/api/stalls', stallRoutes);
app.use('/api/fairs', fairRoutes);
app.use('/api/visitors', visitorRoutes);

app.get('/', (req, res) => {
    res.send('MELA API is running...');
});

// Global Error Handler
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).json({
        success: false,
        message: err.message || 'Internal Server Error'
    });
});

module.exports = app;
