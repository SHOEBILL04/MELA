const { sequelize } = require('../config/db');

// Import models
const User = require('./User');
const Fair = require('./Fair');
const Stall = require('./Stall');
const Vendor = require('./Vendor');
const Visitor = require('./Visitor');
const Ticket = require('./Ticket');
const Employee = require('./Employee');
const Event = require('./Event');

// Define Relationships

User.hasMany(Fair, { foreignKey: 'Organizer_ID', onDelete: 'NO ACTION' });
Fair.belongsTo(User, { foreignKey: 'Organizer_ID', onDelete: 'NO ACTION' });

User.hasMany(Vendor, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });
Vendor.belongsTo(User, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });

User.hasMany(Visitor, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });
Visitor.belongsTo(User, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });

User.hasMany(Employee, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });
Employee.belongsTo(User, { foreignKey: 'User_ID', onDelete: 'NO ACTION' });

// Fair and Stall (1:N)
Fair.hasMany(Stall, { foreignKey: 'Fair_ID' });
Stall.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// Vendor and Stall (1:N)
Vendor.hasMany(Stall, { foreignKey: 'Vendor_ID' });
Stall.belongsTo(Vendor, { foreignKey: 'Vendor_ID' });

// Visitor and Ticket (1:N)
Visitor.hasMany(Ticket, { foreignKey: 'Visitor_ID' });
Ticket.belongsTo(Visitor, { foreignKey: 'Visitor_ID' });

// Fair and Ticket (1:N)
Fair.hasMany(Ticket, { foreignKey: 'Fair_ID' });
Ticket.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// Fair and Employee (1:N)
Fair.hasMany(Employee, { foreignKey: 'Fair_ID' });
Employee.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// Fair and Event (1:N)
Fair.hasMany(Event, { foreignKey: 'Fair_ID' });
Event.belongsTo(Fair, { foreignKey: 'Fair_ID' });

// User (Admin/Vendor) and Event (1:N)
//User.hasMany(Event, { foreignKey: 'Organizer_ID' });
//Event.belongsTo(User, { foreignKey: 'Organizer_ID' });

module.exports = {
    sequelize,
    User,
    Fair,
    Stall,
    Vendor,
    Visitor,
    Ticket,
    Employee,
    Event
};
