const { User, Visitor, Vendor, Fair } = require('../models');

exports.register = async (req, res) => {
    const { Email, Password, Role, Name, Age, Gender, Contact_Number, Phone_Number, Address, Stall_ID } = req.body;

    try {
        const existingUser = await User.findOne({ where: { Email } });
        if (existingUser) {
            return res.status(400).json({ message: 'User already exists with this email' });
        }

        // 2. Create User record 
        const newUser = await User.create({
            Email,
            Password: Password,
            Role
        });

        // 3. Create associated profile based on role
        if (Role === 'Visitor') {
            await Visitor.create({
                User_ID: newUser.User_ID,
                Visitor_Name: Name || 'Anonymous Visitor',
                Age: Age || null,
                Gender: Gender || null,
                Contact_Number: Contact_Number || null
            });
        } else if (Role === 'Vendor') {
            await Vendor.create({
                User_ID: newUser.User_ID,
                Vendor_Name: Name || 'Anonymous Vendor',
                Phone_Number: Phone_Number || null,
                Address: Address || null
            });
        }

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

        // 2. Compare passwords (Plain text comparison)
        if (user.Password !== Password) {
            return res.status(400).json({ message: 'Invalid credentials' });
        }

        // 3. Generate Simple Custom Token (Format: User_ID:Role)
        const token = Buffer.from(`${user.User_ID}:${user.Role}`).toString('base64');

        res.json({ token, role: user.Role, userId: user.User_ID });

    } catch (error) {
        console.error(error);
        res.status(500).json({ message: 'Server error during login', error: error.message });
    }
};
