const express = require('express');
const router = express.Router();
const stallController = require('../controllers/stallController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.post('/', verifyToken, checkRole(['Admin', 'FairOwner']), stallController.addStall);
router.get('/fair/:fair_id', stallController.getStalls);
router.get('/', stallController.getAllStalls);
router.put('/:id', verifyToken, checkRole(['Admin', 'FairOwner']), stallController.updateStall);
router.delete('/:id', verifyToken, checkRole(['Admin', 'FairOwner']), stallController.deleteStall);
module.exports = router;