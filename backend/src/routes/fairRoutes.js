const express = require('express');
const router = express.Router();
const fairController = require('../controllers/fairController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.get('/', fairController.getFairs);
router.post('/', verifyToken, checkRole(['Admin', 'FairOwner']), fairController.addFair);

module.exports = router;