const request = require('supertest');
const app = require('./src/app');
const { sequelize } = require('./src/config/db');
const { User, Vendor, Stall, Fair } = require('./src/models');

async function runTests() {
    let adminToken, vendorToken, adminUser, vendorUser, testFair, testVendor, createdStallId;

    try {
        console.log('Connecting to database...');
        await sequelize.authenticate();
        
        // 1. Setup Test Data (Admin, Vendor, Fair)
        adminUser = await User.create({
            Email: 'admin_test_stall@example.com',
            Password: 'password123',
            Role: 'Admin',
            Name: 'Admin Test'
        });
        
        vendorUser = await User.create({
            Email: 'vendor_test_stall@example.com',
            Password: 'password123',
            Role: 'Vendor',
            Name: 'Vendor Test'
        });

        testVendor = await Vendor.create({
            Vendor_Name: 'Test Vendor Co',
            User_ID: vendorUser.User_ID
        });

        testFair = await Fair.create({
            Fair_Name: 'Test Fair',
            Location: 'Test Location',
            Start_Date: '2025-01-01',
            End_Date: '2025-01-10',
            Organizer_ID: adminUser.User_ID,
            Budget: 10000,
            Status: 'Planned'
        });

        // Tokens are base64 of "User_ID:Role" per authMiddleware
        adminToken = 'Bearer ' + Buffer.from(`${adminUser.User_ID}:${adminUser.Role}`).toString('base64');
        vendorToken = 'Bearer ' + Buffer.from(`${vendorUser.User_ID}:${vendorUser.Role}`).toString('base64');

        console.log('Setup complete. Running tests...\n');

        // Test 1: Admin creates a stall
        console.log('--- Test 1: Admin creates a stall ---');
        let res = await request(app)
            .post('/api/stalls')
            .set('Authorization', adminToken)
            .send({
                Stall_Name: 'Test Stall A',
                Stall_Type: 'Food',
                Rent_Amount: 500,
                Fair_ID: testFair.Fair_ID
            });
        
        if (res.status === 201 && res.body.success) {
            console.log('PASS: Admin successfully created stall.');
            createdStallId = res.body.data.Stall_ID;
        } else {
            console.error('FAIL: Admin could not create stall.', res.body);
            throw new Error('Test 1 Failed');
        }

        // Test 2: Vendor purchases the stall
        console.log('\n--- Test 2: Vendor purchases the stall ---');
        res = await request(app)
            .post(`/api/stalls/${createdStallId}/purchase`)
            .set('Authorization', vendorToken);
        
        if (res.status === 200 && res.body.success) {
            console.log('PASS: Vendor successfully purchased stall.');
        } else {
            console.error('FAIL: Vendor could not purchase stall.', res.body);
            throw new Error('Test 2 Failed');
        }

        // Test 3: Vendor tries to purchase an already purchased stall
        console.log('\n--- Test 3: Double purchase restriction ---');
        res = await request(app)
            .post(`/api/stalls/${createdStallId}/purchase`)
            .set('Authorization', vendorToken);
        
        if (res.status === 400 && !res.body.success) {
            console.log('PASS: Double purchase blocked as expected.');
        } else {
            console.error('FAIL: Double purchase was allowed!', res.body);
            throw new Error('Test 3 Failed');
        }

        // Test 4: Verify in Database
        console.log('\n--- Test 4: Verify DB consistency ---');
        const stallInDb = await Stall.findByPk(createdStallId);
        if (stallInDb && stallInDb.Vendor_ID === testVendor.Vendor_ID) {
            console.log('PASS: Stall Vendor_ID matches the purchasing Vendor.');
        } else {
            console.error('FAIL: Stall Vendor_ID mismatch.');
            throw new Error('Test 4 Failed');
        }

        console.log('\nAll tests passed successfully!');
    } catch (err) {
        console.error('Testing Error:', err.message);
    } finally {
        // Cleanup Test Data
        console.log('\nCleaning up test data...');
        if (createdStallId) await Stall.destroy({ where: { Stall_ID: createdStallId }});
        if (testVendor) await Vendor.destroy({ where: { Vendor_ID: testVendor.Vendor_ID }});
        if (testFair) await Fair.destroy({ where: { Fair_ID: testFair.Fair_ID }});
        if (vendorUser) await User.destroy({ where: { User_ID: vendorUser.User_ID }});
        if (adminUser) await User.destroy({ where: { User_ID: adminUser.User_ID }});
        
        await sequelize.close();
    }
}

runTests();
