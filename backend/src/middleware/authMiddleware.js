const { User } = require('../models');

// Simple Middleware to verify token created without JWT
// Token structure is base64 of "User_ID:Role"
exports.verifyToken = async (req, res, next) => {
    const authHeader = req.header('Authorization');

    if (!authHeader || !authHeader.startsWith('Bearer ')) {
        return res.status(401).json({ message: 'No token, authorization denied' });
    }

    const token = authHeader.split(' ')[1];

    try {
        const decodedString = Buffer.from(token, 'base64').toString('ascii');
        const [userId, role] = decodedString.split(':');

        if (!userId || !role) {
            return res.status(401).json({ message: 'Token is invalid format' });
        }

        const user = await User.findByPk(userId);
        if (!user || user.Role !== role) {
            return res.status(401).json({ message: 'Token is no longer valid' });
        }

        req.user = { id: user.User_ID, role: user.Role };
        next();
    } catch (err) {
        res.status(401).json({ message: 'Token is not valid' });
    }
};

// Middleware to check if user has required role(s)
exports.checkRole = (roles) => {
    return (req, res, next) => {
        if (!req.user) {
            return res.status(401).json({ message: 'Unauthorized, no user in request' });
        }

        if (!roles.includes(req.user.role)) {
            return res.status(403).json({ message: `Forbidden: Requires one of these roles: ${roles.join(', ')}` });
        }

        next();
    };
};
