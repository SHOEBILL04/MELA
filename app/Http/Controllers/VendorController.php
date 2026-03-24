<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function buyStall(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer',
            'stall_id' => 'required|integer',
        ]);

        try {
            DB::statement("SET NOCOUNT ON; EXEC usp_BuyStall @vendor_id = ?, @stall_id = ?", [
                $request->vendor_id, 
                $request->stall_id
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Stall purchased successfully!'
            ]);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            if (stripos($errorMessage, 'already sold') !== false || stripos($errorMessage, 'reserved') !== false) {
                $userFriendlyMessage = "This stall is already sold or reserved!";
            } else {
                $userFriendlyMessage = "Failed to buy stall. Please try again.";
            }

            return response()->json([
                'status' => 'error',
                'message' => $userFriendlyMessage
            ], 400);
        }
    }
    // Issue 13: Vendor posts a new employee position
    public function postEmployeePosition(Request $request)
    {
        
        $stallId = $request->input('stall_id');
        $title = $request->input('title');
        $salary = $request->input('salary');

        try {
            $inserted = \DB::insert("
                INSERT INTO employee_positions (stall_id, title, salary, status)
                SELECT ?, ?, ?, 'open'
                WHERE (
                    SELECT COUNT(*) FROM employee_positions WHERE stall_id = ?
                ) < (
                    SELECT max_employees FROM stalls WHERE stall_id = ?
                )
            ", [$stallId, $title, $salary, $stallId, $stallId]);

            if ($inserted) {
                return response()->json([
                    'status' => 'success',
                    'message' => "Job role '{$title}' posted successfully!"
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot create position. Maximum employee limit reached for this stall!'
                ], 400);
            }

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}