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
        return view('stall_dashboard', compact('fair'));
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
            'bid_amount' => 'required|numeric|min:1',
        ]);

        try {
            // Check if stall is still available before accepting bid
            $stall = DB::table('stalls')->where('stall_id', $request->stall_id)->first();
            if (!$stall || $stall->status !== 'available') {
                throw new \Exception('already sold');
            }

            // Insert Bid
            DB::table('stall_bids')->insert([
                'vendor_id' => $request->vendor_id,
                'stall_id' => $request->stall_id,
                'bid_amount' => $request->bid_amount,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Bid submitted successfully! Pending Admin approval.'
            ]);

        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();

            if (stripos($errorMessage, 'already sold') !== false || stripos($errorMessage, 'reserved') !== false) {
                $userFriendlyMessage = "This stall is already sold or reserved!";
            } else {
                $userFriendlyMessage = "Failed to submit bid: " . $e->getMessage();
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
            $stalls = DB::select("SELECT stall_id, stall_number, status FROM stalls WHERE fair_id = ?", [$fair_id]);
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
}