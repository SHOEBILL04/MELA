@extends('layouts.app', ['header' => $fair->name . ' - Available Dates'])

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Back to Fairs -->
    <div class="mb-6">
        <a href="{{ route('visitor.fairs') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Directory
        </a>
    </div>

    <!-- Fair Info Header -->
    <div class="bg-indigo-600 rounded-2xl shadow-sm overflow-hidden mb-8 text-white relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="p-8 relative z-10 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black mb-2">{{ $fair->name }}</h2>
                <p class="text-indigo-100 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ $fair->location }}
                </p>
            </div>
            <div class="bg-yellow-400 text-yellow-900 rounded-2xl p-4 text-center transform rotate-3 shadow-lg">
                <span class="block text-[10px] font-black uppercase tracking-widest opacity-80 mb-1">Entry Price</span>
                <span class="block text-4xl font-black tracking-tight">${{ number_format($fair->default_ticket_price ?? 50, 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Date selector -->
    <div class="space-y-6">
        @foreach($fairDays as $day)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center justify-between gap-6 overflow-hidden relative group">
                <!-- Date Box -->
                <div class="flex flex-col items-center justify-center p-4 bg-slate-50 rounded-xl border border-slate-200 min-w-24 shrink-0">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($day->day_date)->format('M') }}</span>
                    <span class="text-3xl font-black text-slate-800">{{ \Carbon\Carbon::parse($day->day_date)->format('d') }}</span>
                </div>

                <!-- Info -->
                <div class="flex-grow">
                    <h4 class="text-lg font-bold text-slate-800 mb-2">General Admission Ticket</h4>
                    <div class="flex items-center gap-4">
                        <div class="flex-grow bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            @php $percent = min(100, ($day->visitors_count / max(1, $day->max_visitors)) * 100); @endphp
                            <div class="h-2.5 rounded-full {{ $day->remaining_slots > 0 ? 'bg-indigo-500' : 'bg-red-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                        <span class="text-xs font-bold {{ $day->remaining_slots > 0 ? 'text-indigo-600' : 'text-red-500' }} w-24 text-right">
                            {{ $day->remaining_slots > 0 ? $day->remaining_slots . ' tickets left' : 'Sold Out' }}
                        </span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="shrink-0 flex items-center gap-4 border-l border-slate-100 pl-6 h-full">
                    @if($day->remaining_slots > 0)
                        <div class="flex flex-col items-end">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Qty</label>
                            <input type="number" id="qty_{{ $day->day_id }}" value="1" min="1" max="{{ $day->remaining_slots }}" class="w-16 h-10 px-2 bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-lg font-black text-center text-slate-800">
                        </div>
                        <button onclick="buyTickets({{ $day->day_id }}, {{ $fair->default_ticket_price ?? 50 }})" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold h-10 px-6 rounded-xl transition-all shadow-sm flex justify-center items-center gap-2 mt-4 tooltip:mt-0">
                            Buy Now
                        </button>
                    @else
                        <button disabled class="bg-slate-100 text-slate-400 font-bold h-10 px-6 rounded-xl cursor-not-allowed border border-slate-200">
                            Sold Out
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Modal for purchase feedback -->
<div id="statusModal" class="fixed inset-0 z-[100] hidden items-center justify-center bg-slate-900/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-100 w-full max-w-sm mx-4 transform scale-95 opacity-0 transition-all duration-200" id="modalContent">
        <div class="p-8 text-center" id="modalBody">
            <!-- Content injected via JS -->
        </div>
    </div>
</div>

<script>
const fairId = {{ $fair->fair_id }};

function showModal(isSuccess, message) {
    const modal = document.getElementById('statusModal');
    const content = document.getElementById('modalContent');
    const body = document.getElementById('modalBody');
    
    body.innerHTML = isSuccess 
        ? `<div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg></div>
           <h3 class="text-xl font-black text-slate-800 mb-2">Success!</h3>
           <p class="text-sm font-medium text-slate-500">${message}</p>`
        : `<div class="w-16 h-16 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
           <h3 class="text-xl font-black text-slate-800 mb-2">Error</h3>
           <p class="text-sm font-medium text-slate-500">${message}</p>
           <button onclick="closeModal()" class="mt-6 w-full bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold py-2 px-4 rounded-xl">Close</button>`;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);

    if(isSuccess) {
        setTimeout(() => { window.location.reload(); }, 1500);
    }
}

function closeModal() {
    const modal = document.getElementById('statusModal');
    const content = document.getElementById('modalContent');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');
    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }, 200);
}

async function buyTickets(dayId, price) {
    const qty = parseInt(document.getElementById('qty_' + dayId).value) || 1;
    
    // Show loading explicitly
    showModal(true, 'Processing your purchase securely...');
    
    try {
        const response = await fetch('/visitor/buy-fair-tickets-bulk', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                fair_id: fairId,
                day_id: dayId,
                quantity: qty
            })
        });

        const result = await response.json();

        if (response.ok) {
            showModal(true, result.message);
        } else {
            showModal(false, result.message || 'Failed to purchase tickets.');
        }
    } catch (e) {
        showModal(false, 'Server connection error.');
    }
}
</script>
@endsection
