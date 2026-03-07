const ticketRepository = require('../repositories/ticketRepository');

const buyTicket = async (ticketData) => {
   
    return await ticketRepository.create(ticketData);
};

module.exports = { buyTicket };