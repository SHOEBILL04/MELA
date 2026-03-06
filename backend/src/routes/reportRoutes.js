const express = require('express');
const router = express.Router();
const reportController = require('../controllers/reportController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.get('/summary', verifyToken, checkRole(['Admin', 'FairOwner']), reportController.getSummary);

module.exports = router;
