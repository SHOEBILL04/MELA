const express = require('express');
const router = express.Router();
const visitorController = require('../controllers/visitorController');

router.post('/', visitorController.addVisitor);
router.get('/', visitorController.getVisitors);
router.post('/ticket', visitorController.buyTicket);
router.put('/ticket/:ticket_id/cancel', visitorController.cancelTicket);
router.get('/:visitor_id/tickets', visitorController.getTickets);

module.exports = router;
