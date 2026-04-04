<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    // Dashboard: Only Welcome Summary & Pending Recruitment [cite: 23, 106]
    public function dashboard()
    {
        $vendorId = auth()->id();
        $stallCount = DB::table('stalls')->where('vendor_id', $vendorId)->count();

        // FIX: ->where('applications.status', 'pending') kete diyechi, jate approved gulo o dekha jay
        $applications = DB::table('applications')
            ->join('users', 'applications.employee_id', '=', 'users.user_id')
            ->join('employee_positions', 'applications.position_id', '=', 'employee_positions.position_id')
            ->join('stalls', 'employee_positions.stall_id', '=', 'stalls.stall_id')
            ->where('stalls.vendor_id', $vendorId)
            ->select('applications.*', 'users.name as user_name', 'employee_positions.title')
            ->orderBy('applications.applied_at', 'desc')
            ->get();

        return view('vendor.dashboard', compact('stallCount', 'applications'));
    }

    public function my_stalls()
    {
        $vendorId = auth()->id();
        
        // Using vw_VendorStalls for employee count, but we need vendor_id which is in stalls table.
        // Let's just do the DB query directly to include employee count if we don't want to rely on the View's vendor_name string match.
        $myStalls = DB::table('stalls')
            ->join('fairs', 'stalls.fair_id', '=', 'fairs.fair_id')
            ->where('stalls.vendor_id', $vendorId)
            ->select(
                'stalls.*', 
                'fairs.name as fair_name',
                DB::raw('(SELECT COUNT(*) FROM employee_positions WHERE employee_positions.stall_id = stalls.stall_id AND employee_positions.status = \'filled\') as employee_count'),
                DB::raw('(SELECT COALESCE(SUM(et.ticket_price), 0) FROM events e JOIN event_tickets et ON e.event_id = et.event_id WHERE e.vendor_id = stalls.vendor_id AND e.fair_id = stalls.fair_id) as stall_revenue')
            )
            ->orderBy('stalls.updated_at', 'desc')
            ->get();

        return view('vendor.my_stalls', compact('myStalls'));
    }

    public function recruitEmployee($applicationId)
    {
        try {
            // FIX: Positional parameter (?) use korlam jate SQL error na khay
            DB::statement("EXEC usp_RecruitEmployee ?", [$applicationId]);
            
            // FIX: Jodi SP status update na kore, tobe Laravel theke Force Update kore dilam!
            DB::table('applications')
                ->where('application_id', $applicationId)
                ->update(['status' => 'approved']);
                
            return back()->with('success', 'Employee successfully recruited!');
        } catch (\Exception $e) {
            // Error dhora porle jeno amra dekhte pari
            return back()->with('error', 'Database Error: ' . $e->getMessage());
        }
    }

    public function fairs()
    {
        $fairs = DB::table('fairs')->whereIn('status', ['active', 'upcoming'])->get();
        return view('vendor.fairs', compact('fairs'));
    }

    public function buyStall(Request $request)
    {
        try {
            // Atomic transaction (Issue 12) [cite: 106, 136]
            DB::statement("EXEC usp_BuyStall @vendor_id = ?, @stall_id = ?", [auth()->id(), $request->stall_id]);
            return response()->json(['status' => 'success', 'message' => 'Stall secured!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
        }
    }

    public function events()
    {
        $vendorId = auth()->id();

        // Get events created by this vendor
        $events = DB::table('events')
            ->join('fairs', 'events.fair_id', '=', 'fairs.fair_id')
            ->where('events.vendor_id', $vendorId)
            ->select('events.*', 'fairs.name as fair_name')
            ->orderBy('events.event_date', 'asc')
            ->get();

        // Get fairs where vendor has a stall (to populate dropdown)
        $stalledFairs = DB::table('fairs')
            ->join('stalls', 'stalls.fair_id', '=', 'fairs.fair_id')
            ->where('stalls.vendor_id', $vendorId)
            ->select('fairs.fair_id', 'fairs.name')
            ->distinct()
            ->get();

        return view('vendor.events', compact('events', 'stalledFairs'));
    }

    public function storeEvent(Request $request)
    {
        $request->validate([
            'fair_id' => 'required|exists:fairs,fair_id',
            'name' => 'required|string|max:150',
            'event_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'ticket_price' => 'required|numeric|min:0',
            'max_capacity' => 'required|integer|min:1'
        ]);

        try {
            DB::table('events')->insert([
                'fair_id' => $request->fair_id,
                'vendor_id' => auth()->id(),
                'name' => $request->name,
                'event_date' => $request->event_date,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'ticket_price' => $request->ticket_price,
                'max_capacity' => $request->max_capacity,
                'tickets_sold' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return back()->with('success', 'Event successfully created!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating event: ' . $e->getMessage());
        }
    }

    public function eventBuyers($id)
    {
        $vendorId = auth()->id();

        // Check if event belongs to this vendor
        $event = DB::table('events')
            ->where('event_id', $id)
            ->where('vendor_id', $vendorId)
            ->first();

        if (!$event) {
            abort(404);
        }

        $buyers = DB::table('event_tickets')
            ->join('users', 'event_tickets.visitor_id', '=', 'users.user_id')
            ->where('event_tickets.event_id', $id)
            ->select('users.name', 'users.email', 'event_tickets.purchase_date', 'event_tickets.ticket_price', 'event_tickets.qr_code')
            ->orderBy('event_tickets.purchase_date', 'desc')
            ->get();

        return view('vendor.event_buyers', compact('event', 'buyers'));
    }
}