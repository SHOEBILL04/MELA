const visitorService = require('../services/visitorService');

exports.addVisitor = async (req, res) => {
    try {
        const result = await visitorService.registerVisitor(req.body);
        res.status(201).json(result);
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
        res.status(201).json(result); // Return the full result { Success: 1, Ticket: {...} }
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.cancelTicket = async (req, res) => {
    try {
        const result = await visitorService.cancelTicket(req.params.ticket_id);
        if (result && result.Success === 0) {
            return res.status(400).json({ message: result.Message });
        }
        res.status(200).json({ message: 'Ticket cancelled successfully' });
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
