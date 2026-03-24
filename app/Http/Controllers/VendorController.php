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
    // Vendor-er nijer kena stall gulo dekhanor function
    public function getMyStalls()
    {
        // dummy
        $vendorId = 2; 

        try {
            
            $myStalls = \DB::select("SELECT stall_id, stall_number, category, price, status FROM stalls WHERE vendor_id = ?", [$vendorId]);
            
            return response()->json([
                'status' => 'success',
                'data' => $myStalls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}