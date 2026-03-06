require('dotenv').config();
const { sequelize } = require('./src/config/db');

async function checkDB() {
    try {
        await sequelize.authenticate();
        console.log('Connection established.');

        // Find users
        const [users] = await sequelize.query('SELECT User_ID FROM Users');
        const userIds = users.map(u => u.User_ID);
        console.log('Users:', userIds);

        // Find fairs
        const [fairs] = await sequelize.query('SELECT Fair_ID, Organizer_ID FROM Fairs');
        console.log('Fairs:', fairs);

        // Find orphaned fairs
        const orphanedFairs = fairs.filter(f => !userIds.includes(f.Organizer_ID));
        console.log('Orphaned Fairs:', orphanedFairs);

    } catch (err) {
        console.error('Error:', err);
    } finally {
        await sequelize.close();
    }
}

checkDB();
