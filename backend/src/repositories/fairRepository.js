const { Fair, Stall } = require('../models');

const fairRepository = {
    getAllFairs: async () => {
        return await Fair.findAll();
    },

    getFairById: async (id) => {
        return await Fair.findByPk(id);
    },

    createFair: async (fairData) => {
        console.log('Inserting fairData:', fairData);
        return await Fair.create({
            Fair_Name: fairData.fair_name,
            Location: fairData.location,
            Start_Date: fairData.start_date,
            End_Date: fairData.end_date,
            Organizer_Name: fairData.organizer_name
        });
    },

    getFairStalls: async (id) => {
        return await Stall.findAll({ where: { Fair_ID: id } });
    }
};

module.exports = fairRepository;