const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const Fair = require('./Fair');

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
        type: DataTypes.STRING(50),
        allowNull: true
    },
    Event_Date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    Start_Time: {
        type: DataTypes.TIME,
        allowNull: true
    },
    End_Time: {
        type: DataTypes.TIME,
        allowNull: true
    },
    Fair_ID: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: Fair,
            key: 'Fair_ID'
        }
    }
}, {
    tableName: 'Events',
    timestamps: false
});

module.exports = Event;
