const visitorService = require('../services/visitorService');

exports.addVisitor = async (req, res) => {
    try {
        const result = await visitorService.registerVisitor(req.body);
        res.status(201).json({ message: 'Visitor registered', data: result });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.buyTicket = async (req, res) => {
    try {
        const result = await visitorService.purchaseTicket(req.body);
        if (result && result.Success === 0) {
            return res.status(400).json({ message: result.Message });
        }
        res.status(201).json({ message: 'Ticket purchased', data: result });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getVisitors = async (req, res) => {
    try {
        const visitors = await visitorService.getAllVisitors();
        res.status(200).json(visitors);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getTickets = async (req, res) => {
    try {
        const tickets = await visitorService.getVisitorTickets(req.params.visitor_id);
        res.status(200).json(tickets);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
