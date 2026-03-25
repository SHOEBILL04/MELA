@extends('layouts.app', ['header' => 'Buy Your Perfect Stall'])

@section('content')
<div class="max-w-xl mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100">
        <form id="buyStallForm" class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="vendor_id">Vendor ID</label>
                <input type="number" id="vendor_id" value="{{ auth()->user()->id }}" readonly
                    class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-sm text-slate-500 cursor-not-allowed">
                <p class="mt-1 text-xs text-slate-400">Your Vendor ID is auto-filled.</p>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5">Proposed Bid Amount ($)</label>
                <input type="number" id="bid_amount" step="0.01" min="1" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-sm font-black text-slate-800" placeholder="e.g. 500.00">
            </div>

            <button type="submit" id="submitBidBtn" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                Submit Bid Proposal
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
            <div id="message" class="text-sm font-medium text-center mt-4 hidden p-3 rounded-md"></div>
        </form>
    </div>
</div>

<script>
// Make stall_id available in JS
const stallId = {{ $stall_id }};

async function submitBid(event) {
    event.preventDefault(); // Prevent default form submission

    const vendorId = document.getElementById('vendor_id').value;
    const bidAmountStr = document.getElementById('bid_amount').value;
    const msgBox = document.getElementById('message');
    const btn = document.getElementById('submitBidBtn');

    const bidAmount = parseFloat(bidAmountStr);

    if (isNaN(bidAmount) || bidAmount <= 0) {
        msgBox.className = "mt-4 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-100 flex items-center gap-2 block";
        msgBox.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Please enter a valid bid amount.';
        return;
    }

    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-wait');
    msgBox.className = "text-sm font-medium text-center mt-4 p-3 rounded-md bg-blue-50 text-blue-600 border border-blue-100 block";
    msgBox.innerHTML = "Processing bid...";

    try {
        const response = await fetch('/vendor/buy-stall', { // Assuming the route remains the same for now
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                vendor_id: parseInt(vendorId),
                stall_id: parseInt(stallId),
                bid_amount: bidAmount
            })
        });

        const result = await response.json();

        if (response.ok) { // Check for HTTP success status (2xx)
            msgBox.className = "mt-4 p-4 rounded-xl text-sm font-bold bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center gap-2 block";
            msgBox.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> ${result.message}`;
            setTimeout(() => { window.location.href = '/vendor/fairs'; }, 2000);
        } else {
            msgBox.className = "mt-4 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-100 flex items-center gap-2 block";
            msgBox.innerHTML = `❌ ${result.message || "Bid submission failed!"}`;
        }
    } catch (error) {
        msgBox.className = "mt-4 p-4 rounded-xl text-sm font-bold bg-red-50 text-red-600 border border-red-100 flex items-center gap-2 block";
        msgBox.innerHTML = "❌ Server Connection Error.";
    } finally {
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-wait');
    }
}
</script>
@endsection