const { Stall } = require('../models');

class StallService {
    async createStall(stallData) {
        return await Stall.create(stallData);
    }

    async getStallsForFair(fairId) {
        return await Stall.findAll({ where: { Fair_ID: fairId } });
    }

    async purchaseStall(stallId, vendorId) {
        const stall = await Stall.findByPk(stallId);
        
        if (!stall) throw new Error('Stall not found');
        if (stall.Vendor_ID) throw new Error('Stall is already booked');

        stall.Vendor_ID = vendorId;
        await stall.save();

        return stall;
    }
}

module.exports = new StallService();
