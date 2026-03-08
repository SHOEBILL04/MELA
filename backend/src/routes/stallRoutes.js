const express = require('express');
const router = express.Router();
const stallController = require('../controllers/stallController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

// Route for Admin to create a stall
router.post('/', verifyToken, checkRole(['Admin']), stallController.createStall);

// Route for getting stalls for a fair (Public or Vendor/Admin)
router.get('/fair/:fair_id', verifyToken, stallController.getStallsForFair);

// Route for Vendor to purchase a stall
router.post('/:id/purchase', verifyToken, checkRole(['Vendor']), stallController.purchaseStall);

module.exports = router;
