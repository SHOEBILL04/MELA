const fairRepository = require('../repositories/fairRepository');

class FairService {
    async fetchAllFairs() {
        return await fairRepository.getAllFairs();
    }
    async createNewFair(fairData) {
        if (new Date(fairData.End_Date) < new Date(fairData.Start_Date)) {
            throw new Error('End_Date cannot be earlier than Start_Date');
        }
        return await fairRepository.createFair(fairData);
    }
}
module.exports = new FairService();