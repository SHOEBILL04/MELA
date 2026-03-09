const Ticket = require('../models/Ticket');
const Event = require('../models/Event');

class TicketService {
    static async releaseTickets(data) {
        // data should contain Ticket_Type, Price, Visit_Date, Event_ID, Fair_ID, Organizer_ID
        // We might want to verify that the Event belongs to the Organizer
        const event = await Event.findByPk(data.Event_ID);
        if (!event) {
            throw new Error('Event not found');
        }
        if (event.Organizer_ID !== data.Organizer_ID && data.Role !== 'Admin') {
            throw new Error('Unauthorized to release tickets for this event');
        }

        // Create the ticket definition / release a bulk of them if needed. 
        // For simplicity, we just create one ticket record here.
        return await Ticket.create({
            Ticket_Type: data.Ticket_Type,
            Price: data.Price,
            Visit_Date: data.Visit_Date,
            Event_ID: data.Event_ID,
            Fair_ID: data.Fair_ID || event.Fair_ID,
            Status: 'Available' // A new status to indicate it is not bought yet
        });
    }

    static async getEventTickets(eventId) {
        return await Ticket.findAll({
            where: { Event_ID: eventId }
        });
    }
}

module.exports = TicketService;
