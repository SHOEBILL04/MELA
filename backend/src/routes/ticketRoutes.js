const express = require('express');
const ticketController = require('../controllers/ticketController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

const router = express.Router();

// Public route to view tickets
router.get('/event/:eventId', ticketController.getEventTickets);

// Only Vendors and Admins can release tickets
router.use(verifyToken);
router.use(checkRole(['Vendor', 'Admin']));

router.post('/release', ticketController.releaseTicket);

module.exports = router;
