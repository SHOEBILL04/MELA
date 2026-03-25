@extends('layouts.app', ['header' => 'Secure Stalls - ' . $fair->name])

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Back to Fairs -->
    <div class="mb-6">
        <a href="{{ route('vendor.fairs') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-blue-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Directory
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-xl font-bold text-slate-800">Purchase Stalls</h3>
            <p class="text-sm font-medium text-slate-500 mt-1">Select how many stalls you wish to secure for this fair.</p>
        </div>

        <div class="p-8">
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 border border-blue-100 p-6 rounded-2xl text-center">
                    <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Available Stalls</p>
                    <p class="text-4xl font-black text-blue-600 tracking-tight">{{ $availableCount }}</p>
                </div>
                <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-2xl text-center">
                    <p class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Price Per Stall</p>
                    <p class="text-4xl font-black text-emerald-600 tracking-tight">${{ number_format($stallPrice, 2) }}</p>
                </div>
            </div>

            @if($availableCount > 0)
                <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6">
                    <label class="block text-sm font-bold text-slate-700 mb-3">How many stalls do you want to secure?</label>
                    <div class="flex items-center gap-4">
                        <input type="number" id="purchaseQuantity" value="1" min="1" max="{{ $availableCount }}" class="w-24 px-4 py-3 bg-white border border-slate-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition-all text-xl font-black text-center text-slate-800" onchange="updateTotal()">
                        
                        <div class="flex-grow flex items-center justify-between px-6 py-3 bg-slate-800 text-white rounded-xl shadow-inner">
                            <span class="text-sm font-bold text-slate-400">Total Calculation:</span>
                            <span class="text-2xl font-black tracking-tight" id="calculatedTotal">${{ number_format($stallPrice, 2) }}</span>
                        </div>
                    </div>

                    <div id="modalMessage" class="hidden text-sm font-medium text-center mt-6 p-4 rounded-xl text-emerald-600 bg-emerald-50 border border-emerald-100"></div>

                    <button id="confirmBuyBtn" onclick="executePurchase()" class="mt-6 w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-[0_4px_14px_0_rgba(37,99,235,0.39)] hover:shadow-[0_6px_20px_rgba(37,99,235,0.23)] hover:-translate-y-0.5 flex justify-center items-center gap-2 text-lg">
                        Finalize Purchase
                        <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            @else
                <div class="p-8 text-center bg-red-50 border border-red-100 rounded-2xl">
                    <div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h4 class="text-xl font-bold text-slate-800 mb-2">Fair is Sold Out</h4>
                    <p class="text-slate-600 font-medium">Unfortunately, there are no stalls left available for this fair.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
const vendorId = {{ auth()->id() }};
const fairId = {{ $fair->fair_id }};
const pricePerStall = {{ $stallPrice }};
const maxAvailable = {{ $availableCount }};

function updateTotal() {
    let qty = parseInt(document.getElementById('purchaseQuantity').value) || 0;
    if(qty > maxAvailable) {
        qty = maxAvailable;
        document.getElementById('purchaseQuantity').value = qty;
    }
    if(qty < 1) {
        qty = 1;
        document.getElementById('purchaseQuantity').value = qty;
    }
    const total = qty * pricePerStall;
    document.getElementById('calculatedTotal').innerText = '$' + total.toFixed(2);
}

async function executePurchase() {
    const btn = document.getElementById('confirmBuyBtn');
    const msg = document.getElementById('modalMessage');
    const qty = parseInt(document.getElementById('purchaseQuantity').value) || 1;
    
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing Payment...';
    
    try {
        const response = await fetch('/vendor/buy-stalls-bulk', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                vendor_id: vendorId,
                fair_id: fairId,
                quantity: qty
            })
        });

        const result = await response.json();

        if (response.ok) {
            btn.classList.add('hidden');
            msg.className = "text-sm font-black text-center mt-6 p-4 rounded-xl text-emerald-600 bg-emerald-50 border border-emerald-200 flex flex-col items-center gap-3 block shadow-sm";
            msg.innerHTML = `<div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div> ${result.message}`;
            
            // Reload page
            setTimeout(() => { window.location.reload(); }, 2000);
        } else {
            btn.innerHTML = 'Try Again';
            btn.disabled = false;
            msg.className = "text-sm font-medium text-center mt-6 p-4 rounded-xl text-red-600 bg-red-50 border border-red-100 flex items-center gap-2 justify-center block";
            msg.innerHTML = `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> ${result.message || 'Failed'}`;
        }
    } catch (e) {
        btn.innerHTML = 'Try Again';
        btn.disabled = false;
        msg.className = "text-sm font-medium text-center mt-6 p-4 rounded-xl text-red-600 bg-red-50 border border-red-100 block";
        msg.innerHTML = "Server Connection Error";
    }
}
</script>
@endsection