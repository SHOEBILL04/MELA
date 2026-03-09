const ticketService = require('../services/ticketService');

class TicketController {
    static async releaseTicket(req, res) {
        try {
            const { Ticket_Type, Price, Visit_Date, Event_ID, Fair_ID } = req.body;
            const Organizer_ID = req.user.User_ID;
            const Role = req.user.Role;

            if (!Ticket_Type || !Price || !Visit_Date || !Event_ID) {
                return res.status(400).json({ success: false, message: 'Missing required fields' });
            }

            const ticket = await ticketService.releaseTickets({
                Ticket_Type,
                Price,
                Visit_Date,
                Event_ID,
                Fair_ID,
                Organizer_ID,
                Role
            });

            res.status(201).json({
                success: true,
                message: 'Ticket released successfully',
                data: ticket
            });
        } catch (error) {
            console.error('Release Ticket Error:', error);
            const status = error.message.includes('Unauthorized') || error.message.includes('not found') ? 403 : 500;
            res.status(status).json({ success: false, message: error.message || 'Server Error' });
        }
    }

    static async getEventTickets(req, res) {
        try {
            const { eventId } = req.params;
            const tickets = await ticketService.getEventTickets(eventId);

            res.status(200).json({
                success: true,
                data: tickets
            });
        } catch (error) {
            console.error('Get Event Tickets Error:', error);
            res.status(500).json({ success: false, message: 'Server Error' });
        }
    }
}

module.exports = TicketController;
