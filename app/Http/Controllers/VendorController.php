<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    // Ei function-ta website theke request nibe
    public function buyStall(Request $request)
    {
        // 1. Ekhon shudhu vendor_id ar stall_id check korbo
        $request->validate([
            'vendor_id' => 'required|integer',
            'stall_id' => 'required|integer',
        ]);

        try {
            // 2. Tomar database-er sathe mil rekhe procedure call korchi
            DB::statement(
                "EXEC usp_BuyStall @vendor_id = ?, @stall_id = ?",
                [
                    $request->vendor_id, 
                    $request->stall_id
                ]
            );

            // 3. Shob thik thakle Success message dibe
            return response()->json([
                'status' => 'success',
                'message' => 'Stall purchased successfully!'
            ]);

        } catch (\Exception $e) {
            // Error khele ei message dibe
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to buy stall: ' . $e->getMessage()
            ], 500);
        }
    }
}