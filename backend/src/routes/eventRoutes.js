const express = require('express');
const eventController = require('../controllers/eventController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

const router = express.Router();

// Only Vendors and Admins can create and manage events
router.use(verifyToken);
router.use(checkRole(['Vendor', 'Admin']));

router.post('/', eventController.createEvent);
router.get('/my-events', eventController.getVendorEvents);

module.exports = router;
