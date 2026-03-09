const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Ticket = sequelize.define('Ticket', {
    Ticket_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Ticket_Type: {
        type: DataTypes.STRING(50),
        allowNull: false
    },
    Price: {
        type: DataTypes.DECIMAL(10, 2),
        allowNull: false
    },
    Visit_Date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    Visitor_ID: {
        type: DataTypes.INTEGER,
        allowNull: true
    },
    Fair_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Event_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Status: {
        type: DataTypes.STRING(20),
        allowNull: false,
        defaultValue: 'Booked'
    }
}, {
    tableName: 'Tickets',
    timestamps: false
});

module.exports = Ticket;
