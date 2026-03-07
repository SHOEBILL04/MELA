const Event = require('../models/Event');

// Notun Event jog korar function (POST API)
const addEvent = async (req, res) => {
    try {
        const newEvent = await Event.create(req.body);
        res.status(201).json({
            success: true,
            message: "Event added successfully!",
            data: newEvent
        });
    } catch (error) {
        console.error("Error adding event:", error);
        res.status(500).json({ 
            success: false, 
            message: "Failed to add event", 
            error: error.message 
        });
    }
};

// Shob Event dekhar function (GET API) - Eta testing er jonno kaje lagbe
const getAllEvents = async (req, res) => {
    try {
        const events = await Event.findAll();
        res.status(200).json({ success: true, data: events });
    } catch (error) {
        console.error("Error fetching events:", error);
        res.status(500).json({ success: false, message: "Failed to fetch events" });
    }
};

module.exports = { addEvent, getAllEvents };