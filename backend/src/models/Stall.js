const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const Fair = require('./Fair');

const Stall = sequelize.define('Stall', {
    Stall_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Stall_Name: {
        type: DataTypes.STRING(100),
        allowNull: false
    },
    Stall_Type: {
        type: DataTypes.STRING(50),
        allowNull: false
    },
    Rent_Amount: {
        type: DataTypes.DECIMAL(10, 2),
        allowNull: false
    },
    Fair_ID: {
        type: DataTypes.INTEGER,
        allowNull: false,
        references: {
            model: Fair,
            key: 'Fair_ID'
        }
    },
    Vendor_ID: {
        type: DataTypes.INTEGER,
        allowNull: true
    }
}, {
    tableName: 'Stalls',
    timestamps: false
});

module.exports = Stall;
