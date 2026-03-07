const { DataTypes } = require('sequelize');
const { sequelize } = require('../config/db');
const bcrypt = require('bcrypt');

const User = sequelize.define('User', {
    User_ID: {
        type: DataTypes.INTEGER,
        primaryKey: true,
        autoIncrement: true
    },
    Email: {
        type: DataTypes.STRING(255),
        allowNull: false,
        unique: true
    },
    Name: {
        type: DataTypes.STRING(100),
        allowNull: true
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
        type: DataTypes.DATE,
        defaultValue: sequelize.literal('GETDATE()')
    }
}, {
    tableName: 'Users',
    timestamps: false,
    hooks: {
        beforeCreate: async (user) => {
            if (user.Password) {
                const salt = await bcrypt.genSalt(10);
                user.Password = await bcrypt.hash(user.Password, salt);
            }
        },
        beforeUpdate: async (user) => {
            if (user.changed('Password')) {
                const salt = await bcrypt.genSalt(10);
                user.Password = await bcrypt.hash(user.Password, salt);
            }
        },
        beforeValidate: (user, options) => {
            if (user.Created_At === undefined || user.Created_At === null) {
                user.Created_At = new Date();
            }
        }
    }
});

User.prototype.comparePassword = async function (candidatePassword) {
    return await bcrypt.compare(candidatePassword, this.Password);
};

module.exports = User;
