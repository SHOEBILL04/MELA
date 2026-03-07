const visitorRepository = require('../repositories/visitorRepository');

class VisitorService {
    async registerVisitor(data) {
        return await visitorRepository.createVisitor(data);
    }

    async purchaseTicket(data) {
        return await visitorRepository.buyTicket(data);
    }

    async cancelTicket(ticket_id) {
        return await visitorRepository.cancelTicket(ticket_id);
    }

    async getAllVisitors() {
        return await visitorRepository.getAllVisitors();
    }

    async getVisitorTickets(visitor_id) {
        return await visitorRepository.getTicketsByVisitor(visitor_id);
    }
}

module.exports = new VisitorService();
