<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FairController extends Controller
{
    public function index()
    {
        $fairs = DB::table('vw_FairSummary')->orderBy('fair_id', 'desc')->get();
        return view('admin.fairs.index', compact('fairs'));
    }

    public function show($id)
    {
        $fair = DB::table('vw_FairSummary')->where('fair_id', $id)->first();
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
            DB::table('stall_bids')->whereIn('stall_id', function($q) use ($id) {
                $q->select('stall_id')->from('stalls')->where('fair_id', $id);
            })->delete();
            DB::table('employee_positions')->whereIn('stall_id', function($q) use ($id) {
                $q->select('stall_id')->from('stalls')->where('fair_id', $id);
            })->delete();
            DB::table('stalls')->where('fair_id', $id)->delete();
            DB::table('event_tickets')->whereIn('event_id', function($q) use ($id) {
                $q->select('event_id')->from('events')->where('fair_id', $id);
            })->delete();
            DB::table('events')->where('fair_id', $id)->delete();
            DB::table('fair_tickets')->where('fair_id', $id)->delete();
            DB::table('fair_days')->where('fair_id', $id)->delete();
            
            DB::table('fairs')->where('fair_id', $id)->delete();
            
            return redirect()->route('admin.fairs.index')->with('success', 'Fair deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete fair due to constraints. Error: ' . $e->getMessage());
        }
    }
}
