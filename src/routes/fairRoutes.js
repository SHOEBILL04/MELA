const exporess = require('express');
const router = exporess.Router();
const fairController = require('../controllers/fairController');

router.get('/fairs', fairController.getFairs);
router.post('/fairs', fairController.addFair);

module.exports = router;