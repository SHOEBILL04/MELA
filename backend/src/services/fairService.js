const fairRepository = require('../repositories/fairRepository');

class FairService {
    async fetchAllFairs() {
        return await fairRepository.getAllFairs();
    }

    async getFairById(id) {
        return await fairRepository.getFairById(id);
    }

    async createNewFair(fairData) {
        if (new Date(fairData.End_Date) < new Date(fairData.Start_Date)) {
            throw new Error('End_Date cannot be earlier than Start_Date');
        }
        return await fairRepository.createFair(fairData);
    }

    async updateFair(id, fairData) {
        if (fairData.Start_Date && fairData.End_Date) {
            if (new Date(fairData.End_Date) < new Date(fairData.Start_Date)) {
                throw new Error('End_Date cannot be earlier than Start_Date');
            }
        }
        return await fairRepository.updateFair(id, fairData);
    }

    async deleteFair(id) {
        return await fairRepository.deleteFair(id);
    }

    async getStallsByFair(id) {
        return await fairRepository.getFairStalls(id);
    }
}
module.exports = new FairService();