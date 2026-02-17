const express = require('express');
const router = express.Router();
const visitorController = require('../controllers/visitorController');

router.post('/', visitorController.addVisitor);
router.get('/', visitorController.getVisitors);
router.post('/ticket', visitorController.buyTicket);
router.get('/:visitor_id/tickets', visitorController.getTickets);

module.exports = router;
