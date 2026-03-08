const { Stall, Vendor } = require('../models');

// Admin creates a stall
exports.createStall = async (req, res) => {
    try {
        const { Stall_Name, Stall_Type, Rent_Amount, Fair_ID } = req.body;

        // Basic validation
        if (!Stall_Name || !Stall_Type || !Rent_Amount || !Fair_ID) {
            return res.status(400).json({ 
                success: false, 
                message: 'Please provide all required fields (Stall_Name, Stall_Type, Rent_Amount, Fair_ID)' 
            });
        }

        const newStall = await Stall.create({
            Stall_Name,
            Stall_Type,
            Rent_Amount,
            Fair_ID,
            Vendor_ID: null // Explicitly stating it's unpurchased initially
        });

        res.status(201).json({ 
            success: true, 
            message: 'Stall created successfully', 
            data: newStall 
        });
    } catch (error) {
        console.error('Error creating stall:', error);
        res.status(500).json({ 
            success: false, 
            message: 'Failed to create stall', 
            error: error.message 
        });
    }
};

// Vendor purchases a stall
exports.purchaseStall = async (req, res) => {
    try {
        const stallId = req.params.id;
        const userId = req.user.id; 

        // 1. Find the stall
        const stall = await Stall.findByPk(stallId);
        if (!stall) {
            return res.status(404).json({ success: false, message: 'Stall not found' });
        }

        // 2. Check if already purchased
        if (stall.Vendor_ID !== null) {
            return res.status(400).json({ success: false, message: 'Stall is already purchased by another vendor' });
        }

        // 3. Find the Vendor record associated with this User
        const vendor = await Vendor.findOne({ where: { User_ID: userId } });
        if (!vendor) {
            return res.status(404).json({ success: false, message: 'Vendor profile not found for this user' });
        }

        // 4. Update the stall
        stall.Vendor_ID = vendor.Vendor_ID;
        await stall.save();

        res.status(200).json({ 
            success: true, 
            message: 'Stall purchased successfully', 
            data: stall 
        });
    } catch (error) {
        console.error('Error purchasing stall:', error);
        res.status(500).json({ 
            success: false, 
            message: 'Failed to purchase stall', 
            error: error.message 
        });
    }
};
