const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const Fair = require('./Fair');

const Employee = sequelize.define('Employee', {
    Employee_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Employee_Name: {
        type: DataTypes.STRING(100),
        allowNull: false
    },
    Role: {
        type: DataTypes.STRING(50),
        allowNull: false
    },
    Phone_Number: {
        type: DataTypes.STRING(20),
        allowNull: true
    },
    Salary: {
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
    User_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    }
}, {
    tableName: 'Employees',
    timestamps: false
});

module.exports = Employee;
