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

    // 2. Apply Logic
    public function applyPosition(Request $request, $id)
    {
        $employeeId = auth()->id();

        try {
            DB::statement("EXEC usp_ApplyForPosition @position_id = ?, @employee_id = ?", [$id, $employeeId]);
            return back()->with('success', 'Application submitted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    // 3. Application History Logic (Tumar Notun Blade-er sathe match kora)
    public function viewHistory(Request $request)
    {
        $employeeId = auth()->id();
        
        // Blade-er filter-er theke status anar beabostha
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
                'employee_positions.title as position_title',
                'stalls.stall_number',
                'fairs.name as fair_name'
            );

        // Jodi user kono nirdishto status (pending/approved) filter kore
        if ($statusFilter !== 'all') {
            $query->where('applications.status', $statusFilter);
        }

        $applications = $query->orderBy('applications.applied_at', 'desc')->get();

        // Tumar blade-er variabler nam-er sathe match kore pathalam
        return view('employee.history', compact('applications', 'statusFilter'));
    }
}