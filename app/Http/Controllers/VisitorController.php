<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function browseFairs()
    {
        $fairs = DB::table('fairs')
            ->whereIn('status', ['active', 'upcoming'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('visitor.fairs', compact('fairs'));
    }

    public function fairDays($fair_id)
    {
        $fair = DB::table('fairs')->where('fair_id', $fair_id)->first();
        if (!$fair) abort(404);

        $fairDays = DB::table('fair_days')
            ->where('fair_id', $fair_id)
            ->selectRaw('
                day_id, 
                day_date, 
                max_visitors, 
                visitors_count, 
                (max_visitors - visitors_count) as remaining_slots
            ')
            ->orderBy('day_date', 'asc')
            ->get();

        return view('visitor.fair_days', compact('fair', 'fairDays'));
    }

    public function buyFairTicketsBulk(Request $request)
    {
        $request->validate([
            'fair_id' => 'required|integer',
            'day_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $user = Auth::user();
        $fairId = $request->fair_id;
        $dayId = $request->day_id;
        $qty = $request->quantity;

        // Fetch ticket price from fair
        $ticketPrice = DB::table('fairs')->where('fair_id', $fairId)->value('default_ticket_price') ?? 50.00;

        try {
            DB::beginTransaction();

            // Lock the fair_day for update to prevent overselling race conditions
            $day = DB::table('fair_days')
                ->where('day_id', $dayId)
                ->where('fair_id', $fairId)
                ->lockForUpdate()
                ->first();

            if (!$day) {
                throw new \Exception("Invalid fair day.");
            }

            $remaining = $day->max_visitors - $day->visitors_count;
            if ($remaining < $qty) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => "Purchase failed. Only $remaining tickets available for this day."
                ], 400);
            }

            // Loop and execute procedure N times
            for ($i = 0; $i < $qty; $i++) {
                DB::statement("SET NOCOUNT ON; EXEC usp_BuyFairTicket @visitor_id = ?, @fair_id = ?, @day_id = ?, @ticket_price = ?", [
                    $user->user_id,
                    $fairId,
                    $dayId,
                    $ticketPrice
                ]);
            }

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => "$qty Tickets purchased successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Purchase failed: ' . $e->getMessage()
            ], 400);
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

    public function myTickets()
    {
        $userId = Auth::user()->user_id;

        $fairTickets = DB::table('fair_tickets')
            ->join('fairs', 'fair_tickets.fair_id', '=', 'fairs.fair_id')
            ->where('fair_tickets.visitor_id', $userId)
            ->select('fair_tickets.ticket_id as id', 'fairs.name as title', 'fair_tickets.purchase_date', 'fair_tickets.ticket_price', 'fair_tickets.qr_code', DB::raw("'Fair Entry' as type"))
            ->get();

        $eventTickets = DB::table('event_tickets')
            ->join('events', 'event_tickets.event_id', '=', 'events.event_id')
            ->where('event_tickets.visitor_id', $userId)
            ->select('event_tickets.event_ticket_id as id', 'events.name as title', 'event_tickets.purchase_date', 'event_tickets.ticket_price', 'event_tickets.qr_code', DB::raw("'Event Ticket' as type"))
            ->get();

        $allTickets = $fairTickets->concat($eventTickets)->sortByDesc('purchase_date');

        return view('visitor.tickets', compact('allTickets'));
    }
}
