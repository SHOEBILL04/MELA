const express = requir('express');
const cors = require('cors');
const morgan = require('morgan');
require('dotenv').config();

const app = express();

//Middleware
app.use(cors());
app.use(morgan('dev'));
app.use(express.json());

app.get('/', (req, res) =>{
    res.send('API is running...');  
});
const PORT = process.env.PORT || 5000;
app.listen(PORT, () => {
    console.log(`Server running on port ${PORT}`);
});