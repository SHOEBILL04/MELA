<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function fairs()
    {
        $fairs = DB::table('fairs')->whereIn('status', ['active', 'upcoming'])->orderBy('created_at', 'desc')->get();
        return view('vendor.fairs', compact('fairs'));
    }

    public function stalls($fair_id)
    {
        $fair = DB::table('fairs')->where('fair_id', $fair_id)->first();
        if (!$fair) abort(404);
        
        $availableCount = DB::table('stalls')
            ->where('fair_id', $fair_id)
            ->where('status', 'available')
            ->count();
            
        $stallPrice = DB::table('stalls')
            ->where('fair_id', $fair_id)
            ->value('price') ?? 0;

        return view('stall_dashboard', compact('fair', 'availableCount', 'stallPrice'));
    }

    public function buyStallPage(Request $request)
    {
        $stall_id = $request->query('stall');
        return view('buy_stall', compact('stall_id'));
    }

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
                'message' => 'Stall purchased and locked successfully!'
            ]);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            if (stripos($errorMessage, 'already sold') !== false || stripos($errorMessage, 'reserved') !== false) {
                $userFriendlyMessage = "This stall is already sold or reserved by another vendor!";
            } else {
                $userFriendlyMessage = "Failed to buy stall: " . $e->getMessage();
            }

            return response()->json([
                'status' => 'error',
                'message' => $userFriendlyMessage
            ], 400);
        }
    }

    public function getAllStalls($fair_id)
    {
        try {
            $stalls = DB::select("SELECT stall_id, stall_number, status, price FROM stalls WHERE fair_id = ?", [$fair_id]);
            return response()->json([
                'status' => 'success',
                'data' => $stalls
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function buyStallsBulk(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|integer',
            'fair_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $vendorId = $request->vendor_id;
        $fairId = $request->fair_id;
        $qty = $request->quantity;

        try {
            DB::beginTransaction();
            
            // Get available stalls for this fair with row lock
            $stalls = DB::table('stalls')
                ->where('fair_id', $fairId)
                ->where('status', 'available')
                ->lockForUpdate()
                ->limit($qty)
                ->get();

            if ($stalls->count() < $qty) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => 'Not enough stalls available. Only ' . $stalls->count() . ' left.'
                ], 400);
            }

            foreach ($stalls as $stall) {
                DB::statement("SET NOCOUNT ON; EXEC usp_BuyStall @vendor_id = ?, @stall_id = ?", [
                    $vendorId, 
                    $stall->stall_id
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => "$qty Stalls secured successfully!"
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => "Failed to purchase stalls: " . $e->getMessage()
            ], 400);
        }
    }
}