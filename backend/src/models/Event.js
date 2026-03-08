const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Event = sequelize.define('Event', {
    Event_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Event_Name: {
        type: DataTypes.STRING(200),
        allowNull: false
    },
    Event_Type: {
        type: DataTypes.STRING(50)
    },
    Event_Date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    Start_Time: {
        type: DataTypes.TIME
    },
    End_Time: {
        type: DataTypes.TIME
    },
    Fair_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Organizer_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    }
}, {
    tableName: 'Events',
    timestamps: false
});

module.exports = Event;
