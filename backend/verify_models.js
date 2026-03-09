const { sequelize } = require('./src/config/db');
const db = require('./src/models');
const EventService = require('./src/services/eventService');
const TicketService = require('./src/services/ticketService');
const EmployeeService = require('./src/services/employeeService');

async function testBackend() {
    try {
        console.log('Connecting to database...');
        await sequelize.authenticate();
        console.log('Database connection successful.');
        
        console.log('Syncing models...');
        // Only force sync in local dev tests if needed, here we just sync to create new columns/tables safely
        await sequelize.sync({ alter: true }); 
        console.log('Models synchronized successfully!');

        // If we reach here without errors, model associations (1:N between Event and Ticket) are correct and db is set.
        
        const countEvents = await db.Event.count();
        const countTickets = await db.Ticket.count();
        const countEmployees = await db.Employee.count();
        
        console.log(`Current stats: ${countEvents} Events, ${countTickets} Tickets, ${countEmployees} Employees in DB.`);
        
        console.log('All model structures verified successfully.');
        process.exit(0);
    } catch (err) {
        console.error('Test failed:', err);
        process.exit(1);
    }
}

testBackend();
