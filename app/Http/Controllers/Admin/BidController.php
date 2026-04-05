<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stall;
use App\Models\StallBid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BidController extends Controller
{
    public function index()
    {
        $bids = StallBid::query()
            ->join('users', 'stall_bids.vendor_id', '=', 'users.user_id')
            ->join('stalls', 'stall_bids.stall_id', '=', 'stalls.stall_id')
            ->join('fairs', 'stalls.fair_id', '=', 'fairs.fair_id')
            ->select(
                'stall_bids.*',
                'users.name as vendor_name',
                'users.email as vendor_email',
                'stalls.stall_number',
                'fairs.name as fair_name',
                'stalls.price as base_price'
            )
            ->orderBy('stall_bids.created_at', 'desc')
            ->get();

        return view('admin.bids.index', compact('bids'));
    }

    public function approve(Request $request, $bid_id)
    {
        $bid = StallBid::query()->where('bid_id', $bid_id)->first();
        if (!$bid || $bid->status !== 'pending') {
            return back()->with('error', 'Bid is invalid or already processed.');
        }

        try {
            // Check stall availability again
            $stall = Stall::query()->where('stall_id', $bid->stall_id)->first();
            if (!$stall || $stall->status !== 'available') {
                // Reject this bid if the stall was taken
                StallBid::query()->where('bid_id', $bid_id)->update(['status' => 'rejected']);
                return back()->with('error', 'This stall is already sold. Bid was automatically rejected.');
            }

            // Secure the stall via Stored Procedure
            DB::statement("SET NOCOUNT ON; EXEC usp_BuyStall @vendor_id = ?, @stall_id = ?", [
                $bid->vendor_id, 
                $bid->stall_id
            ]);

            // Update this bid as accepted
            StallBid::query()->where('bid_id', $bid_id)->update(['status' => 'accepted']);

            // Reject all other pending bids for this stall
            StallBid::query()
                ->where('stall_id', $bid->stall_id)
                ->where('bid_id', '!=', $bid_id)
                ->update(['status' => 'rejected']);

            return back()->with('success', 'Bid accepted and stall successfully secured for the vendor!');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to accept bid: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, $bid_id)
    {
        $bid = StallBid::query()->where('bid_id', $bid_id)->first();
        if (!$bid || $bid->status !== 'pending') {
            return back()->with('error', 'Bid is invalid or already processed.');
        }

        StallBid::query()->where('bid_id', $bid_id)->update(['status' => 'rejected']);
        return back()->with('success', 'Bid rejected successfully.');
    }
}
