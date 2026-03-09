const employeeService = require('../services/employeeService');

class EmployeeController {
    static async applyForJob(req, res) {
        try {
            const { Employee_Name, Role, Phone_Number, Salary, Fair_ID, Stall_ID } = req.body;
            const User_ID = req.user.User_ID;

            if (!Employee_Name || !Role || !Fair_ID) {
                return res.status(400).json({ success: false, message: 'Missing required fields' });
            }

            const application = await employeeService.applyForJob({
                User_ID,
                Employee_Name,
                Role,
                Phone_Number,
                Salary,
                Fair_ID,
                Stall_ID
            });

            res.status(201).json({
                success: true,
                message: 'Application submitted successfully',
                data: application
            });
        } catch (error) {
            console.error('Apply for Job Error:', error);
            res.status(error.message.includes('not found') ? 404 : 500).json({ success: false, message: error.message || 'Server Error' });
        }
    }

    static async hireEmployee(req, res) {
        try {
            const { id } = req.params;
            const employee = await employeeService.hireEmployee(id);
            res.status(200).json({
                success: true,
                message: 'Employee hired successfully',
                data: employee
            });
        } catch (error) {
            console.error('Hire Employee Error:', error);
            res.status(error.message.includes('not found') ? 404 : 500).json({ success: false, message: error.message || 'Server Error' });
        }
    }

    static async assignToStall(req, res) {
        try {
            const { id } = req.params;
            const { Stall_ID } = req.body;

            if (!Stall_ID) {
                return res.status(400).json({ success: false, message: 'Missing Stall_ID' });
            }

            const employee = await employeeService.assignToStall(id, Stall_ID);
            res.status(200).json({
                success: true,
                message: 'Employee assigned to stall successfully',
                data: employee
            });
        } catch (error) {
            console.error('Assign Employee Error:', error);
            res.status(error.message.includes('not found') ? 404 : 500).json({ success: false, message: error.message || 'Server Error' });
        }
    }

    static async getFairEmployees(req, res) {
        try {
            const { fairId } = req.params;
            const employees = await employeeService.getFairEmployees(fairId);
            res.status(200).json({
                success: true,
                data: employees
            });
        } catch (error) {
            console.error('Get Employees Error:', error);
            res.status(500).json({ success: false, message: 'Server Error' });
        }
    }
}

module.exports = EmployeeController;
