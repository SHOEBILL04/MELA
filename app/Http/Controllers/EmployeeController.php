<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    // 1. Employee Dashboard / Browse Positions Logic
    public function browsePositions(Request $request)
    {
        $statusFilter = $request->query('fair_status', 'all');

        $query = DB::table('vw_AvailablePositions');
        
        if ($statusFilter !== 'all') {
            $query->where('fair_status', $statusFilter);
        }

        $positions = $query->get();

        // Employee agey kon kon position-e apply korche shetar list ber kora holo
        $appliedPositionsArray = DB::table('applications')
            ->where('employee_id', auth()->id())
            ->pluck('position_id')
            ->toArray();

        return view('employee.positions', compact('positions', 'statusFilter', 'appliedPositionsArray'));
    }

    public function showApplyForm($id)
    {
        $position = DB::table('vw_AvailablePositions')->where('position_id', $id)->first();

        if (!$position) {
            abort(404);
        }

        $alreadyApplied = DB::table('applications')
            ->where('employee_id', auth()->id())
            ->where('position_id', $id)
            ->exists();

        if ($alreadyApplied) {
            return redirect()->route('employee.positions')->with('error', 'You have already applied for this position.');
        }

        return view('employee.apply', compact('position'));
    }

    // 2. Apply Logic
    //post req asle applyposition method call hobe
    public function applyPosition(Request $request, $id)
    {
        $employeeId = auth()->id();
        $position = DB::table('vw_AvailablePositions')->where('position_id', $id)->first();

        if (!$position) {
            abort(404);
        }

        $validated = $request->validate([
            'applicant_name' => 'required|string|max:150',
            'applicant_age' => 'required|integer|min:18|max:100',
            'applicant_gender' => 'required|string|max:50',
            'home_location' => 'required|string|max:255',
            'education_status' => 'required|string|max:150',
        ]);

        try {
            DB::statement("EXEC usp_ApplyForPosition @position_id = ?, @employee_id = ?", [$id, $employeeId]);

            DB::table('applications')
                ->where('employee_id', $employeeId)
                ->where('position_id', $id)
                ->update([
                    'applicant_name' => $validated['applicant_name'],
                    'applicant_age' => $validated['applicant_age'],
                    'applicant_gender' => $validated['applicant_gender'],
                    'home_location' => $validated['home_location'],
                    'education_status' => $validated['education_status'],
                    'updated_at' => now(),
                ]);

            return redirect()->route('employee.history')->with('success', 'Application submitted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // 3. Application History Logic 
    public function viewHistory(Request $request)
    {
        $employeeId = auth()->id();
        
        // Blade-er filter-er theke status anar beabos
        $statusFilter = $request->query('status', 'all');

        // Database theke employee-er applications data fetch kora
        $query = DB::table('applications')
            ->join('employee_positions', 'applications.position_id', '=', 'employee_positions.position_id')
            ->join('stalls', 'employee_positions.stall_id', '=', 'stalls.stall_id')
            ->join('fairs', 'stalls.fair_id', '=', 'fairs.fair_id')
            ->where('applications.employee_id', $employeeId)
            ->select(
                'applications.application_id',
                'applications.status',
                'applications.applied_at',
                'applications.position_id',
                'employee_positions.title as position_title',
                'stalls.stall_number',
                'stalls.category',
                'fairs.name as fair_name'
            );

        // Jodi user kono nirdishto status (pending/approved) filter kore
        if ($statusFilter !== 'all') {
            $query->where('applications.status', $statusFilter);
        }

        $applications = $query->orderBy('applications.applied_at', 'desc')->get();
        $selectedApplications = $applications->where('status', 'approved')->values();

        // Tumar blade-er variabler nam-er sathe match kore pathalam
        return view('employee.history', compact('applications', 'statusFilter', 'selectedApplications'));
    }
}
