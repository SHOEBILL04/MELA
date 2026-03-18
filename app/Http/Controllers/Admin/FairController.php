<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FairController extends Controller
{
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
        ]);

        try {
            // Call the stored procedure using DB::select to catch the returned NewFairID
            // Note: Since we are in the initial setup, we might manually pass an admin_id if Auth is not ready.
            // For now, let's assume we use the authenticated user or a default admin (id=1).
            $adminId = Auth::id() ?? 1;

            $result = DB::select('EXEC usp_CreateFair ?, ?, ?, ?, ?, ?, ?', [
                $adminId,
                $validated['name'],
                $validated['location'],
                $validated['start_date'],
                $validated['end_date'],
                $validated['total_stalls'],
                $validated['default_limit'],
            ]);

            $newFairId = $result[0]->NewFairID ?? null;

            return redirect()->route('admin.fairs.create')
                ->with('success', "Fair created successfully! New Fair ID: $newFairId");

        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Database Error: ' . $e->getMessage()]);
        }
    }
}
