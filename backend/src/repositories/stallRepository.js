const { Stall, Fair } = require('../models');

class StallRepository {
    async create(data) {
        return await Stall.create({
            Fair_ID: data.Fair_ID,
            Stall_Name: data.Stall_Name,
            Stall_Type: data.Stall_Type,
            Rent_Amount: data.Rent_Amount
        });
    }

    async getAllByFair(Fair_ID) {
        return await Stall.findAll({ where: { Fair_ID } });
    }

    async getAllWithFairDetails() {
        return await Stall.findAll({
            include: [{
                model: Fair,
                attributes: ['Fair_Name', 'Location']
            }]
        });
    }

    async update(id, data) {
        const stall = await Stall.findByPk(id);
        if (!stall) return null;
        return await stall.update(data);
    }

    async delete(id) {
        const stall = await Stall.findByPk(id);
        if (!stall) return null;
        await stall.destroy();
        return true;
    }
}

module.exports = new StallRepository();