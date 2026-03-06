const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');

const Fair = sequelize.define('Fair', {
    Fair_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Fair_Name: {
        type: DataTypes.STRING(200),
        allowNull: false
    },
    Location: {
        type: DataTypes.STRING(200),
        allowNull: false
    },
    Start_Date: {
        type: DataTypes.DATEONLY,
        allowNull: false
    },
    End_Date: {
        type: DataTypes.DATEONLY,
        allowNull: false,
        validate: {
            isValidDate(value) {
                if (value < this.Start_Date) {
                    throw new Error('End_Date must be greater than or equal to Start_Date');
                }
            }
        }
    },
    Organizer_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Daily_Ticket_Limit: {
        type: DataTypes.INTEGER,
        allowNull: false
    }
}, {
    tableName: 'Fairs',
    timestamps: false,
    hooks: {
        beforeValidate: (fair, options) => {
            if (fair.Daily_Ticket_Limit === undefined || fair.Daily_Ticket_Limit === null) {
                fair.Daily_Ticket_Limit = 1000;
            }
        }
    }
});

module.exports = Fair;
