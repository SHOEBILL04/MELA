const { Fair, Stall, Ticket, sequelize } = require('../models');

class FairService {
    async createFair(fairData) {
        // Start a transaction to ensure both fair and stalls are created safely
        const transaction = await sequelize.transaction();
        try {
            const fair = await Fair.create({
                Fair_Name: fairData.Fair_Name,
                Location: fairData.Location,
                Start_Date: fairData.Start_Date,
                End_Date: fairData.End_Date,
                Organizer_ID: fairData.Organizer_ID,
                Daily_Ticket_Limit: fairData.Daily_Ticket_Limit || 1000,
                Max_Stalls: fairData.Max_Stalls || 50
            }, { transaction });

            // Auto-generate stalls
            const stallsToCreate = [];
            for (let i = 1; i <= fair.Max_Stalls; i++) {
                stallsToCreate.push({
                    Stall_Name: `Stall - ${i}`,
                    Stall_Type: 'General', // Default type
                    Rent_Amount: 100.00, // Default rent 
                    Fair_ID: fair.Fair_ID,
                    Vendor_ID: null // Empty, ready to be booked
                });
            }

            if (stallsToCreate.length > 0) {
                await Stall.bulkCreate(stallsToCreate, { transaction });
            }

            await transaction.commit();
            return fair;
        } catch (error) {
            await transaction.rollback();
            throw error;
        }
    }

    async getFairSummary(fairId) {
        const fair = await Fair.findByPk(fairId);
        if (!fair) throw new Error('Fair not found');

        // Total stalls
        const totalStalls = await Stall.count({ where: { Fair_ID: fairId } });
        
        // Booked stalls (Vendor_ID is not null)
        const bookedStallsCount = await Stall.count({ 
            where: { 
                Fair_ID: fairId,
                Vendor_ID: { [sequelize.Sequelize.Op.ne]: null }
            } 
        });

        // Visitor stats via Tickets
        const totalTickets = await Ticket.count({ where: { Fair_ID: fairId } });
        
        const tickets = await Ticket.findAll({ where: { Fair_ID: fairId } });
        const summaryRevenue = tickets.reduce((sum, t) => sum + parseFloat(t.Price), 0);

        return {
            Fair_ID: fair.Fair_ID,
            Fair_Name: fair.Fair_Name,
            Total_Stalls: totalStalls,
            Booked_Stalls: bookedStallsCount,
            Total_Visitors: totalTickets,
            Total_Revenue: summaryRevenue
        };
    }
}

module.exports = new FairService();
