const express = require('express');
const router = express.Router();
const eventController = require('../controllers/eventController');

// Postman theke ei route gulote amra hit korbo
router.post('/add', eventController.addEvent);
router.get('/', eventController.getAllEvents);

module.exports = router;