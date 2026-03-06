const reportRepository = require('../repositories/reportRepository');

exports.getSummary = async (req, res) => {
    try {
        const summary = await reportRepository.getFairSummary();
        res.status(200).json(summary);
    } catch (error) {
        res.status(500).json({ message: error.message });
    }
};
