const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const Stall = require('./Stall');

const Vendor = sequelize.define('Vendor', {
    Vendor_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Vendor_Name: {
        type: DataTypes.STRING(100),
        allowNull: false
    },
    Phone_Number: {
        type: DataTypes.STRING(20),
        allowNull: true
    },
    Address: {
        type: DataTypes.STRING(255),
        allowNull: true
    },
    Stall_ID: {
        type: DataTypes.INTEGER,
        allowNull: false,
        unique: true,
        references: {
            model: Stall,
            key: 'Stall_ID'
        }
    }
}, {
    tableName: 'Vendors',
    timestamps: false
});

module.exports = Vendor;
