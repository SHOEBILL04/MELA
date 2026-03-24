<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function browseFairs()
    {
        // 1. Computed difference expression to show remaining slots for active fairs
        $fairDays = DB::table('fairs')
            ->join('fair_days', 'fairs.fair_id', '=', 'fair_days.fair_id')
            ->selectRaw('
                fairs.fair_id, 
                fairs.name, 
                fairs.location, 
                fair_days.day_id, 
                fair_days.day_date, 
                fair_days.max_visitors, 
                fair_days.visitors_count, 
                (fair_days.max_visitors - fair_days.visitors_count) as remaining_slots
            ')
            ->where('fairs.status', 'active')
            ->orderBy('fair_days.day_date', 'asc')
            ->get();

        return view('visitor.fairs', compact('fairDays'));
    }

    public function buyFairTicket(Request $request, $fairId, $dayId)
    {
        $user = Auth::user();
        $ticketPrice = 50.00; // Hardcoded default for the app logic simplicity

        try {
            DB::statement("EXEC usp_BuyFairTicket @visitor_id = ?, @fair_id = ?, @day_id = ?, @ticket_price = ?", [
                $user->user_id,
                $fairId,
                $dayId,
                $ticketPrice
            ]);
            return back()->with('success', 'Fair ticket purchased successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }

    public function browseEvents()
    {
        $userId = Auth::user()->user_id;

        // 2. SELECT with EXISTS subquery for FK check
        // Only show events if the user already possesses a fair_ticket for that fair
        $sql = "
            SELECT 
                e.event_id,
                e.fair_id,
                e.name as event_name,
                e.event_date,
                e.start_time,
                e.end_time,
                e.ticket_price,
                e.max_capacity,
                e.tickets_sold,
                f.name as fair_name
            FROM events e
            INNER JOIN fairs f ON e.fair_id = f.fair_id
            WHERE EXISTS (
                SELECT 1 
                FROM fair_tickets ft 
                WHERE ft.fair_id = e.fair_id AND ft.visitor_id = ?
            )
            ORDER BY e.event_date ASC
        ";

        $events = DB::select($sql, [$userId]);

        return view('visitor.events', compact('events'));
    }

    public function buyEventTicket(Request $request, $eventId)
    {
        $user = Auth::user();
        
        // Find ticket price for the event
        $event = DB::table('events')->where('event_id', $eventId)->first();
        if (!$event) {
            return back()->with('error', 'Event not found.');
        }

        try {
            DB::statement("EXEC usp_BuyEventTicket @visitor_id = ?, @event_id = ?, @ticket_price = ?", [
                $user->user_id,
                $eventId,
                $event->ticket_price
            ]);
            return back()->with('success', 'Event ticket purchased successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Purchase failed: ' . $e->getMessage());
        }
    }
}
