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
            Fair_Name: fairData.Fair_Name,
            Location: fairData.Location,
            Start_Date: fairData.Start_Date,
            End_Date: fairData.End_Date,
            Organizer_ID: fairData.Organizer_ID,
            Daily_Ticket_Limit: fairData.Daily_Ticket_Limit || 1000
        });
    },

    getFairStalls: async (id) => {
        return await Stall.findAll({ where: { Fair_ID: id } });
    }
};

module.exports = fairRepository;