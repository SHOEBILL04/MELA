<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DailyVisitorCount;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\Fair;
use App\Models\FairDay;
use App\Models\FairSummary;
use App\Models\FairTicket;
use App\Models\Stall;
use App\Models\StallBid;
use App\Models\EmployeePosition;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FairController extends Controller
{
    public function index()
    {
        $fairs = FairSummary::query()->orderBy('fair_id', 'desc')->get();
        
        $globalStats = [
            'total_stalls_sold' => collect($fairs)->sum('stalls_sold'),
            'total_stall_revenue' => collect($fairs)->sum('stall_revenue'),
            'total_entry_revenue' => collect($fairs)->sum('entry_revenue'),
            'total_event_revenue' => collect($fairs)->sum('event_revenue'),
            'total_overall_revenue' => collect($fairs)->sum('total_fair_revenue'),
        ];

        $topEvents = Event::query()
            ->leftJoin('event_tickets', 'events.event_id', '=', 'event_tickets.event_id')
            ->select('events.name', DB::raw('COUNT(event_tickets.event_ticket_id) as tickets_sold'), DB::raw('COALESCE(SUM(event_tickets.ticket_price), 0) as revenue'))
            ->groupBy('events.event_id', 'events.name')
            ->orderByDesc('tickets_sold')
            ->take(5)
            ->get();

        $dailyVisitors = DailyVisitorCount::query()
            ->select('day_date', DB::raw('SUM(visitors_count) as total_visitors'))
            ->groupBy('day_date')
            ->orderBy('day_date')
            ->get();

        return view('admin.fairs.index', compact('fairs', 'globalStats', 'topEvents', 'dailyVisitors'));
    }

    public function show($id)
    {
        $fair = FairSummary::query()->where('fair_id', $id)->first();
        if (!$fair) {
            abort(404);
        }
        return view('admin.fairs.show', compact('fair'));
    }

    /**
     * Show the form for creating a new fair.
     */
    public function create()
    {
        return view('admin.fairs.create');
    }

    /**
     * Store a newly created fair in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'location' => 'required|string|max:200',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'total_stalls' => 'required|integer|min:1',
            'default_limit' => 'required|integer|min:1',
            'default_stall_price' => 'required|numeric|min:0',
            'default_ticket_price' => 'required|numeric|min:0',
        ]);

        try {
            $adminId = Auth::id() ?? 1;

            $result = DB::select('EXEC usp_CreateFair ?, ?, ?, ?, ?, ?, ?, ?, ?', [
                $adminId,
                $validated['name'],
                $validated['location'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['total_stalls'],
                $validated['default_limit'],
                $validated['default_stall_price'],
                $validated['default_ticket_price']
            ]);

            $newFairId = $result[0]->NewFairID ?? null;

            return redirect()->route('admin.fairs.show', $newFairId)
                ->with('success', "Fair created successfully! New Fair ID: $newFairId");

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            // Delete child records first to handle missing ON DELETE CASCADE
            StallBid::query()->whereIn('stall_id', function($q) use ($id) {
                $q->select('stall_id')->from('stalls')->where('fair_id', $id);
            })->delete();
            EmployeePosition::query()->whereIn('stall_id', function($q) use ($id) {
                $q->select('stall_id')->from('stalls')->where('fair_id', $id);
            })->delete();
            Stall::query()->where('fair_id', $id)->delete();
            EventTicket::query()->whereIn('event_id', function($q) use ($id) {
                $q->select('event_id')->from('events')->where('fair_id', $id);
            })->delete();
            Event::query()->where('fair_id', $id)->delete();
            FairTicket::query()->where('fair_id', $id)->delete();
            FairDay::query()->where('fair_id', $id)->delete();
            
            Fair::query()->where('fair_id', $id)->delete();
            
            return redirect()->route('admin.fairs.index')->with('success', 'Fair deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete fair due to constraints. Error: ' . $e->getMessage());
        }
    }
}
