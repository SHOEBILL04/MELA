const express = require('express');
const router = express.Router();
const fairController = require('../controllers/fairController');

router.get('/fairs', fairController.getFairs);
router.post('/fairs', fairController.addFair);

module.exports = router;