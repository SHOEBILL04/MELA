const fairService = require('../services/fairService');

exports.createFair = async (req, res) => {
    try {
        const organizerId = req.user?.userId || req.user?.id || req.body.Organizer_ID;
        if (!organizerId) {
            return res.status(400).json({ message: 'Organizer_ID is required' });
        }

        const fairData = { ...req.body, Organizer_ID: organizerId };
        const newFair = await fairService.createFair(fairData);
        
        res.status(201).json({ 
            message: 'Fair created successfully with auto-generated stalls', 
            fair: newFair 
        });
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.getFairSummary = async (req, res) => {
    try {
        const summary = await fairService.getFairSummary(req.params.id);
        res.status(200).json(summary);
    } catch (error) {
        res.status(404).json({ message: error.message });
    }
};
