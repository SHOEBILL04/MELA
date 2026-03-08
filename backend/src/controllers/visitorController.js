const visitorService = require('../services/visitorService');

exports.registerProfile = async (req, res) => {
    try {
        const userId = req.user?.userId || req.user?.id;
        if (!userId) {
            return res.status(400).json({ message: 'User_ID is required via token' });
        }

        const visitor = await visitorService.registerVisitor(userId, req.body);
        res.status(201).json({ message: 'Visitor profile created', visitor });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.buyTicket = async (req, res) => {
    try {
        const userId = req.user?.id || req.user?.userId;
        let visitorId = req.body.Visitor_ID;

        if (!visitorId && req.user && req.user.role === 'Visitor') {
            const visitor = await visitorService.getVisitorByUser(userId);
            if (!visitor) return res.status(404).json({ message: 'Visitor profile not found' });
            visitorId = visitor.Visitor_ID;
        }

        if (!visitorId) {
            return res.status(400).json({ message: 'Visitor_ID is required' });
        }

        const ticket = await visitorService.buyTicket(visitorId, req.body);
        res.status(201).json({ message: 'Ticket purchased successfully', ticket });
    } catch (error) {
        res.status(400).json({ message: error.message });
    }
};
