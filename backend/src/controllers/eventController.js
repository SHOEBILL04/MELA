const eventService = require('../services/eventService');
const { Fair } = require('../models');

class EventController {
    static async createEvent(req, res) {
        try {
            const { Event_Name, Event_Type, Event_Date, Start_Time, End_Time, Fair_ID } = req.body;

            // Assume req.user contains the authenticated vendor/admin
            console.log("Mela Token User Data:", req.user);
            const Organizer_ID = req.user.User_ID || req.user.userId || req.user.id;
            if (!Event_Name || !Event_Date || !Fair_ID) {
                return res.status(400).json({ success: false, message: 'Missing required fields' });
            }

            const newEvent = await eventService.createEvent({
                Event_Name,
                Event_Type,
                Event_Date,
                Start_Time,
                End_Time,
                Fair_ID,
                Organizer_ID
            });

            res.status(201).json({
                success: true,
                message: 'Event created successfully',
                data: newEvent
            });
        } catch (error) {
            console.error('Create Event Error:', error);
            res.status(500).json({ success: false, message: 'Server Error' });
        }
    }

    static async getVendorEvents(req, res) {
        try {
            // req.user.User_ID jodi na pay, tahole req.user.userId ba req.user.id khujbe
            const vendorId = req.user.User_ID || req.user.userId || req.user.id;

            console.log("Fetching events for Vendor ID:", vendorId); // Debugging er jonno

            const events = await eventService.getVendorEvents(vendorId);

            res.status(200).json({
                success: true,
                data: events
            });
        } catch (error) {
            console.error('Get Vendor Events Error:', error);
            res.status(500).json({ success: false, message: 'Server Error' });
        }
    }
}

module.exports = EventController;
