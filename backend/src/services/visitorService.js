const { Visitor, Ticket, Fair } = require('../models');

class VisitorService {
    async registerVisitor(userId, visitorData) {
        return await Visitor.create({ ...visitorData, User_ID: userId });
    }

    async getVisitorByUser(userId) {
        return await Visitor.findOne({ where: { User_ID: userId } });
    }

    async buyTicket(visitorId, ticketData) {
        const fair = await Fair.findByPk(ticketData.Fair_ID);
        if (!fair) throw new Error('Fair not found');

        // Check daily limit - simplistic check assuming all tickets are for today or Visit_Date
        const bookedCount = await Ticket.count({
            where: {
                Fair_ID: fair.Fair_ID,
                Visit_Date: ticketData.Visit_Date,
                Status: 'Booked'
            }
        });

        if (bookedCount >= fair.Daily_Ticket_Limit) {
            throw new Error(`Cannot book ticket. Daily limit of ${fair.Daily_Ticket_Limit} reached for ${ticketData.Visit_Date}.`);
        }

        return await Ticket.create({
            Ticket_Type: ticketData.Ticket_Type || 'General',
            Price: ticketData.Price || 50.00,
            Visit_Date: ticketData.Visit_Date,
            Visitor_ID: visitorId,
            Fair_ID: fair.Fair_ID,
            Status: 'Booked'
        });
    }
}

module.exports = new VisitorService();
