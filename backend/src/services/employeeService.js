const Employee = require('../models/Employee');
const Fair = require('../models/Fair');

class EmployeeService {
    static async applyForJob(data) {
        // data has User_ID, Employee_Name, Role, Phone_Number, Salary (expected), Fair_ID, Stall_ID (optional)
        
        // Verify fair exists
        const fair = await Fair.findByPk(data.Fair_ID);
        if (!fair) {
            throw new Error('Fair not found');
        }

        return await Employee.create({
            Employee_Name: data.Employee_Name,
            Role: data.Role,
            Phone_Number: data.Phone_Number,
            Salary: data.Salary || 0, // Default to 0, admins will negotiate/set later maybe
            User_ID: data.User_ID,
            Fair_ID: data.Fair_ID,
            Stall_ID: data.Stall_ID || null,
            Status: 'Pending'
        });
    }

    static async hireEmployee(employeeId) {
        const employee = await Employee.findByPk(employeeId);
        if (!employee) {
            throw new Error('Employee application not found');
        }

        employee.Status = 'Hired';
        await employee.save();
        return employee;
    }

    static async assignToStall(employeeId, stallId) {
        const employee = await Employee.findByPk(employeeId);
        if (!employee) {
            throw new Error('Employee not found');
        }
        
        // Assuming stallId exists, in a real app check stall validity here
        employee.Stall_ID = stallId;
        await employee.save();
        return employee;
    }

    static async getFairEmployees(fairId) {
        return await Employee.findAll({
            where: { Fair_ID: fairId }
        });
    }
}

module.exports = EmployeeService;
