const Event = require('../models/Event');
// Ticket ekhon dorkar nai jodi relationship error dey
// const Ticket = require('../models/Ticket'); 

class EventService {
    static async createEvent(data) {
        return await Event.create(data);
    }

    static async getVendorEvents(vendorId) {
        // Ekhonkar moto simple query rakho jate crash na kore
        return await Event.findAll({
            where: { Organizer_ID: vendorId }
        });
    }

    static async getEventById(eventId) {
        return await Event.findByPk(eventId);
    }
}

module.exports = EventService;