const { Visitor, Ticket } = require('../models');

class VisitorRepository {
    async createVisitor(data) {
        return await Visitor.create({
            Visitor_Name: data.Visitor_Name,
            Age: data.Age,
            Gender: data.Gender,
            Contact_Number: data.Contact_Number,
            User_ID: data.User_ID
        });
    }

    async buyTicket(data) {
        const { Fair } = require('../models');

        const fair = await Fair.findByPk(data.Fair_ID);
        if (!fair) {
            return { Success: 0, Message: 'Fair not found.' };
        }

        const bookedTicketsCount = await Ticket.count({
            where: {
                Fair_ID: data.Fair_ID,
                Visit_Date: data.Visit_Date,
                Status: 'Booked'
            }
        });

        if (bookedTicketsCount >= fair.Daily_Ticket_Limit) {
            return { Success: 0, Message: 'Daily ticket limit reached for this fair.' };
        }

        const newTicket = await Ticket.create({
            Ticket_Type: data.Ticket_Type,
            Price: data.Price,
            Visit_Date: data.Visit_Date,
            Visitor_ID: data.Visitor_ID,
            Fair_ID: data.Fair_ID,
            Status: 'Booked'
        });

        return { Success: 1, Ticket: newTicket };
    }

    async cancelTicket(ticket_id) {
        const ticket = await Ticket.findByPk(ticket_id);
        if (!ticket) {
            return { Success: 0, Message: 'Ticket not found.' };
        }
        if (ticket.Status === 'Cancelled') {
            return { Success: 0, Message: 'Ticket is already cancelled.' };
        }

        ticket.Status = 'Cancelled';
        await ticket.save();

        return { Success: 1 };
    }

    async getAllVisitors() {
        return await Visitor.findAll();
    }

    async getTicketsByVisitor(visitor_id) {
        return await Ticket.findAll({ where: { Visitor_ID: visitor_id } });
    }

    async deleteTicket(ticket_id) {
        const ticket = await Ticket.findByPk(ticket_id);
        if (!ticket) return null;
        await ticket.destroy();
        return true;
    }

    async getAllTickets() {
        return await Ticket.findAll();
    }
}

module.exports = new VisitorRepository();
