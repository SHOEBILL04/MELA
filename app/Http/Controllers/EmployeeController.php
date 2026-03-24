<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EmployeeController extends Controller
{
    public function browsePositions(Request $request)
    {
        $statusFilter = $request->query('fair_status', 'all');

        $query = DB::table('vw_AvailablePositions');
        
        if ($statusFilter !== 'all') {
            $query->where('fair_status', $statusFilter);
        }

        $positions = $query->get();

        return view('employee.positions', compact('positions', 'statusFilter'));
    }

    public function applyPosition(Request $request, $positionId)
    {
        $user = Auth::user();

        // Perform raw insert so the MSSQL INSTEAD OF INSERT trigger can run
        try {
            DB::table('applications')->insert([
                'employee_id' => $user->user_id,
                'position_id' => $positionId,
                'status' => 'pending',
                'applied_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            return back()->with('success', 'Successfully applied to the position!');
        } catch (\Exception $e) {
            // Trigger will RAISERROR code, catch and show user
            return back()->with('error', 'Error applying: ' . $e->getMessage());
        }
    }

    public function viewHistory(Request $request)
    {
        $user = Auth::user();
        $statusFilter = $request->query('status', 'all');

        // Requirement: SELECT with ORDER BY, status filter, and correlated subquery
        $sql = "
            SELECT 
                a.application_id, 
                a.position_id, 
                a.status, 
                a.applied_at,
                (SELECT ep.title FROM employee_positions ep WHERE ep.position_id = a.position_id) as position_title,
                (SELECT s.category FROM stalls s WHERE s.stall_id = (SELECT ep.stall_id FROM employee_positions ep WHERE ep.position_id = a.position_id)) as category
            FROM applications a
            WHERE a.employee_id = ?
        ";
        $bindings = [$user->user_id];

        if ($statusFilter !== 'all') {
            $sql .= " AND a.status = ?";
            $bindings[] = $statusFilter;
        }

        $sql .= " ORDER BY a.applied_at DESC";

        $applications = DB::select($sql, $bindings);

        return view('employee.history', compact('applications', 'statusFilter'));
    }
}
