const { Visitor, Ticket } = require('../models');

class VisitorRepository {
    async createVisitor(data) {
        return await Visitor.create({
            Visitor_Name: data.Visitor_Name,
            Age: data.Age,
            Gender: data.Gender,
            Contact_Number: data.Contact_Number
        });
    }

    async buyTicket(data) {
        return await Ticket.create({
            Ticket_Type: data.Ticket_Type,
            Price: data.Price,
            Visit_Date: data.Visit_Date,
            Visitor_ID: data.Visitor_ID
        });
    }

    async getAllVisitors() {
        return await Visitor.findAll();
    }

    async getTicketsByVisitor(visitor_id) {
        return await Ticket.findAll({ where: { Visitor_ID: visitor_id } });
    }
}

module.exports = new VisitorRepository();
