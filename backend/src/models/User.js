const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const User = sequelize.define('User', {
    User_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Email: {
        type: DataTypes.STRING(255),
        allowNull: false
    },
    Password: {
        type: DataTypes.STRING(255),
        allowNull: false
    },
    Role: {
        type: DataTypes.STRING(50),
        allowNull: false,
        validate: {
            isIn: [['Admin', 'FairOwner', 'Vendor', 'Visitor', 'Employee']]
        }
    },
    Created_At: {
        type: DataTypes.DATE
    }
}, {
    tableName: 'Users',
    timestamps: false,
    hooks: {
        beforeValidate: (user, options) => {
            if (user.Created_At === undefined || user.Created_At === null) {
                user.Created_At = new Date();
            }
        }
    }
});

module.exports = User;
