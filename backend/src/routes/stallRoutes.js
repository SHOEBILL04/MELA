const express = require('express');
const router = express.Router();
const stallController = require('../controllers/stallController');

router.post('/', stallController.addStall);
router.get('/fair/:fair_id', stallController.getStalls);
router.get('/', stallController.getAllStalls);
module.exports = router;