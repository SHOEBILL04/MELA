const axios = require('axios');

const BASE_URL = 'http://localhost:5000/api';

async function testAPI() {
    try {
        console.log('--- Testing MELA API ---');

        // 1. Register a FairOwner
        console.log('\n1. Registering FairOwner...');
        const regRes = await axios.post(`${BASE_URL}/auth/register`, {
            Email: 'admin6@mela.com',
            Password: 'password123',
            Role: 'FairOwner',
            Name: 'Mela Organizer'
        });
        console.log('Success:', regRes.data);

        // 2. Login
        console.log('\n2. Logging in...');
        const loginRes = await axios.post(`${BASE_URL}/auth/login`, {
            Email: 'admin6@mela.com',
            Password: 'password123'
        });
        const token = loginRes.data.token;
        const config = { headers: { Authorization: `Bearer ${token}` } };
        console.log('Login Success! Token received.');

        // 3. Create a Fair
        console.log('\n3. Creating a Fair...');
        const fairRes = await axios.post(`${BASE_URL}/fairs`, {
            Fair_Name: 'Dhaka Trade Fair',
            Location: 'Purbachal',
            Start_Date: '2026-03-10',
            End_Date: '2026-04-10',
            Organizer_ID: loginRes.data.userId,
            Daily_Ticket_Limit: 5000
        }, config);
        console.log('Success:', fairRes.data);

        // 4. Get All Fairs (GET)
        console.log('\n4. Fetching all Fairs...');
        const allFairs = await axios.get(`${BASE_URL}/fairs`);
        console.log('Fairs found:', allFairs.data.length);
        const fairId = allFairs.data[0].Fair_ID;

        // 5. Create a Visitor
        console.log('\n5. Adding a Visitor...');
        const visitorRes = await axios.post(`${BASE_URL}/visitors`, {
            Visitor_Name: 'Rahim Uddin',
            Age: 25,
            Gender: 'Male',
            Contact_Number: '01711223344',
            User_ID: loginRes.data.userId // Using same user for demo
        });
        const visitorId = visitorRes.data.Visitor_ID;
        console.log('Visitor created with ID:', visitorId);

        // 6. Buy a Ticket (POST)
        console.log('\n6. Buying a Ticket...');
        const ticketRes = await axios.post(`${BASE_URL}/visitors/ticket`, {
            Ticket_Type: 'Adult',
            Price: 100,
            Visit_Date: '2026-03-15',
            Visitor_ID: visitorId,
            Fair_ID: fairId
        });
        const ticketId = ticketRes.data.Ticket.Ticket_ID;
        console.log('Ticket purchased! ID:', ticketId);

        // 7. Cancel Ticket (PUT)
        console.log('\n7. Cancelling Ticket (PUT Test)...');
        const cancelRes = await axios.put(`${BASE_URL}/visitors/ticket/${ticketId}/cancel`);
        console.log('Cancel Status:', cancelRes.data);

        console.log('\n--- API TEST COMPLETED ---');

    } catch (err) {
        console.error('Test Failed:', err.response ? err.response.data : err.message);
    }
}

testAPI();
