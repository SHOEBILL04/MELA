const express = require('express');
const employeeController = require('../controllers/employeeController');
const { verifyToken, checkRole } = require('../middleware/authMiddleware');

const router = express.Router();

router.use(verifyToken);

// Anyone signed in (e.g. Visitor, User) can apply for a job
router.post('/apply', employeeController.applyForJob);

// View employees for a fair - Vendors/Admins
router.get('/fair/:fairId', checkRole(['Admin', 'Vendor']), employeeController.getFairEmployees);

// Hiring and assigning is restricted to Admins (or Organizers depending on business logic, but Admins based on requirements)
router.use(checkRole(['Admin']));
router.put('/:id/hire', employeeController.hireEmployee);
router.put('/:id/assign', employeeController.assignToStall);

module.exports = router;
