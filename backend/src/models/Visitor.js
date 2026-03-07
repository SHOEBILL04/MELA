const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Visitor = sequelize.define('Visitor', {
    Visitor_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Visitor_Name: {
        type: DataTypes.STRING(100),
        allowNull: false
    },
    Age: {
        type: DataTypes.INTEGER,
        allowNull: true
    },
    Gender: {
        type: DataTypes.STRING(10),
        allowNull: true
    },
    Contact_Number: {
        type: DataTypes.STRING(20),
        allowNull: true
    },
    User_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    }
}, {
    tableName: 'Visitors',
    timestamps: false
});

module.exports = Visitor;
