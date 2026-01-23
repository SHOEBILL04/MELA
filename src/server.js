const express = require('express');
const cors = require('cors');
const morgan = require('morgan');
const fairRoutes = require('./routes/fairRoutes');
const stallRoutes = require('./routes/stallRoutes');
require('dotenv').config();

const app = express();

//Middleware
app.use(cors());
app.use(morgan('dev'));
app.use(express.json());
app.use('/api', fairRoutes);
app.use('/api/stalls', stallRoutes);

app.get('/', (req, res) =>{
    res.send('API is running...');  
});
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});