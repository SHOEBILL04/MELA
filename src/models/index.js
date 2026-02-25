const { sequelize } = require('../config/db');

// Import models
const Fair = require('./Fair');
const Stall = require('./Stall');
const Vendor = require('./Vendor');
const Visitor = require('./Visitor');
const Ticket = require('./Ticket');
const Employee = require('./Employee');
const Event = require('./Event');

// Define Relationships

// Fair and Stall (1:N)
Fair.hasMany(Stall, { foreignKey: 'Fair_ID' });
Stall.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// Stall and Vendor (1:1)
Stall.hasOne(Vendor, { foreignKey: 'Stall_ID' });
Vendor.belongsTo(Stall, { foreignKey: 'Stall_ID' });

// Visitor and Ticket (1:N)
Visitor.hasMany(Ticket, { foreignKey: 'Visitor_ID' });
Ticket.belongsTo(Visitor, { foreignKey: 'Visitor_ID' });

// Fair and Employee (1:N)
Fair.hasMany(Employee, { foreignKey: 'Fair_ID' });
Employee.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// Fair and Event (1:N)
Fair.hasMany(Event, { foreignKey: 'Fair_ID' });
Event.belongsTo(Fair, { foreignKey: 'Fair_ID' });

module.exports = {
    sequelize,
    Fair,
    Stall,
    Vendor,
    Visitor,
    Ticket,
    Employee,
    Event
};
