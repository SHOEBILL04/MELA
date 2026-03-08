const stallService = require('../services/stallService');

exports.createStall = async (req, res) => {
    try {
        const newStall = await stallService.createStall(req.body);
        res.status(201).json({ message: 'Stall registered successfully', stall: newStall });
    } catch (error) {
        res.status(500).json({ status: "fail", message: error.message });
    }
};

exports.getStallsForFair = async (req, res) => {
    try {
        const stalls = await stallService.getStallsForFair(req.params.fair_id);
        res.status(200).json(stalls);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};

exports.purchaseStall = async (req, res) => {
    try {
        let vendorId = req.body.Vendor_ID;
        const userId = req.user?.id || req.user?.userId;

        if (!vendorId && req.user && req.user.role === 'Vendor') {
            const { Vendor } = require('../models');
            const vendor = await Vendor.findOne({ where: { User_ID: userId } });
            if (!vendor) return res.status(404).json({ message: 'Vendor profile not found' });
            vendorId = vendor.Vendor_ID;
        }

        if (!vendorId) {
            return res.status(400).json({ message: 'Vendor_ID is required via token or body' });
        }
        await stallService.purchaseStall(req.params.id, vendorId);
        res.status(200).json({ message: 'Stall booked successfully' });
    } catch (error) {
        res.status(400).json({ message: error.message });
    }
};
