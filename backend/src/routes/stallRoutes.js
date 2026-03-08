const express = require('express');
const router = express.Router();
const stallController = require('../controllers/StallController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

// Route for Admin to create a stall
router.post('/', verifyToken, checkRole(['Admin']), stallController.createStall);

// Route for Vendor to purchase a stall
router.post('/:id/purchase', verifyToken, checkRole(['Vendor']), stallController.purchaseStall);

module.exports = router;
