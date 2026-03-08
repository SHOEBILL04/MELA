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
        allowNull: false
    },
    Organizer_ID: {
        type: DataTypes.INTEGER,
        allowNull: false
    },
    Daily_Ticket_Limit: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 1000
    },
    Max_Stalls: {
        type: DataTypes.INTEGER,
        allowNull: false,
        defaultValue: 50
    }
}, {
    tableName: 'Fairs',
    timestamps: false,
    validate: {
        checkEndDate() {
            if (this.End_Date < this.Start_Date) {
                throw new Error('End Date must be greater than or equal to Start Date');
            }
        }
    }
});

module.exports = Fair;
