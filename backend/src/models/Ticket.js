const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const Visitor = require('./Visitor');

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
        allowNull: false,
        references: {
            model: Visitor,
            key: 'Visitor_ID'
        }
    },
    Fair_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Status: {
        type: DataTypes.STRING(20),
        allowNull: false
    }
}, {
    tableName: 'Tickets',
    timestamps: false,
    hooks: {
        beforeValidate: (ticket, options) => {
            if (ticket.Status === undefined || ticket.Status === null) {
                ticket.Status = 'Booked';
            }
        }
    }
});

module.exports = Ticket;
