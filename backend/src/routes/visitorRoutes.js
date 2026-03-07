const express = require('express');
const router = express.Router();
const visitorController = require('../controllers/visitorController');

const { verifyToken, checkRole } = require('../middleware/authMiddleware');

router.post('/', visitorController.addVisitor);
router.get('/', verifyToken, checkRole(['Admin', 'FairOwner']), visitorController.getVisitors);
router.post('/ticket', visitorController.buyTicket);
router.put('/ticket/:ticket_id/cancel', visitorController.cancelTicket);
router.get('/:visitor_id/tickets', visitorController.getTickets);

// Admin/Owner routes
router.get('/tickets/all', verifyToken, checkRole(['Admin', 'FairOwner']), visitorController.getAllTickets);
router.delete('/ticket/:ticket_id', verifyToken, checkRole(['Admin', 'FairOwner']), visitorController.deleteTicket);

module.exports = router;
