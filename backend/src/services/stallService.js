const stallRepository = require('../repositories/stallRepository');
const fairRepository = require('../repositories/fairRepository');

class StallService {
    async registerStall(stallData) {
        return await stallRepository.create(stallData);
    }
    async getStallsForFair(fair_id) {
        return await stallRepository.getAllByFair(fair_id);
    }
    async getAllStalls() {
        return await stallRepository.getAllWithFairDetails();
    }
}
module.exports = new StallService();