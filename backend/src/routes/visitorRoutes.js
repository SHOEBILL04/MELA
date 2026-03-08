const express = require('express');
const router = express.Router();
const visitorController = require('../controllers/visitorController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.post('/profile', verifyToken, checkRole(['Visitor']), visitorController.registerProfile);
router.post('/ticket', verifyToken, checkRole(['Visitor']), visitorController.buyTicket);

module.exports = router;
