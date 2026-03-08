const { User, Vendor, Employee } = require('../models');

exports.register = async (req, res) => {
    const { Email, Password, Role, Name, Phone_Number, Address, Salary } = req.body;

    // Restrict roles to Admin, Vendor, Employee, Visitor
    if (!['Admin', 'Vendor', 'Employee', 'Visitor'].includes(Role)) {
        return res.status(400).json({ message: 'Invalid role specified' });
    }

    try {
        const existingUser = await User.findOne({ where: { Email } });
        if (existingUser) {
            return res.status(400).json({ message: 'User already exists with this email' });
        }

        // 2. Create User record 
        const newUser = await User.create({
            Email,
            Password: Password,
            Role,
            Name: Name || 'Anonymous'
        });

        // 3. Create associated profile based on role
        if (Role === 'Vendor') {
            await Vendor.create({
                User_ID: newUser.User_ID,
                Vendor_Name: Name || 'Anonymous Vendor',
                Phone_Number: Phone_Number || null,
                Address: Address || null
            });
        } else if (Role === 'Employee') {
            await Employee.create({
                User_ID: newUser.User_ID,
                Employee_Name: Name || 'Anonymous Employee',
                Role: 'General',
                Phone_Number: Phone_Number || null,
                Salary: Salary || 0,
                Status: 'Pending'
            });
        } else if (Role === 'Visitor') {
            const { Visitor } = require('../models');
            await Visitor.create({
                User_ID: newUser.User_ID,
                Visitor_Name: Name || 'Anonymous',
                Contact_Number: Phone_Number || null
            });
        } // Admin role simply creates a User record, requiring no secondary profile

        res.status(201).json({ message: 'User registered successfully', userId: newUser.User_ID, role: newUser.Role });

    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error during registration', error: error.message });
    }
};

exports.login = async (req, res) => {
    const { Email, Password } = req.body;

    try {
        // 1. Find User
        const user = await User.findOne({ where: { Email } });
        if (!user) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }
        const isMatch = await user.comparePassword(Password);
        if (!isMatch) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }

        const token = Buffer.from(`${user.User_ID}:${user.Role}`).toString('base64');

        res.json({ token, role: user.Role, userId: user.User_ID });

    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error during login', error: error.message });
    }
};
