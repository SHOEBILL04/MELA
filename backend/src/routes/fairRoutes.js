const express = require('express');
const router = express.Router();
const fairController = require('../controllers/fairController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.post('/', verifyToken, checkRole(['Admin']), fairController.createFair);
router.get('/:id/summary', verifyToken, checkRole(['Admin']), fairController.getFairSummary);

module.exports = router;
