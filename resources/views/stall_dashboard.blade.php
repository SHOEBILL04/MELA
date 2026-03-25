@extends('layouts.app', ['header' => 'Live Stall Availability Map'])

@section('content')
<div class="max-w-6xl mx-auto">
    <div id="loader" class="flex flex-col items-center justify-center py-20 text-indigo-600">
        <svg class="animate-spin h-10 w-10 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="font-medium">Loading stall data...</p>
    </div>

    <div id="gridContainer" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6 hidden"></div>
</div>

<script>
document.addEventListener("DOMContentLoaded", fetchStalls);

async function fetchStalls() {
    const container = document.getElementById('gridContainer');
    const loader = document.getElementById('loader');

    try {
        const response = await fetch('/vendor/api/stalls/{{ $fair->fair_id }}');
        const result = await response.json();

        if (result.status === 'success') {
            loader.style.display = 'none';
            container.classList.remove('hidden');
            
            result.data.forEach(stall => {
                const isAvailable = stall.status.toLowerCase() === 'available';
                
                const cardClass = isAvailable 
                    ? 'bg-white border-2 border-emerald-100 hover:border-emerald-300 hover:shadow-md hover:shadow-emerald-100 cursor-pointer text-emerald-600' 
                    : 'bg-slate-50 border border-slate-200 opacity-60 cursor-not-allowed text-slate-500';
                
                const icon = isAvailable 
                    ? '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                    : '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>';
                
                const numberStyle = isAvailable ? 'text-slate-900' : 'text-slate-400 line-through';

                const cardHTML = `
                    <div class="rounded-xl p-6 text-center transition-all ${cardClass}" onclick="handleStallClick(${stall.stall_id}, '${stall.status}')">
                        <div class="text-3xl font-black mb-2 ${numberStyle}">${stall.stall_number}</div>
                        <div class="flex items-center justify-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                            ${icon} ${stall.status}
                        </div>
                    </div>
                `;
               
                container.innerHTML += cardHTML;
            });
        } else {
            loader.innerHTML = "<p class='text-red-500 font-medium'>❌ Failed to load data.</p>";
        }
    } catch (error) {
        loader.innerHTML = "<p class='text-red-500 font-medium'>❌ Server Connection Error!</p>";
    }
}

function handleStallClick(stallId, status) {
    if(status.toLowerCase() === 'available') {
        window.location.href = '/vendor/buy-stall-page?stall=' + stallId;
    }
}
</script>
@endsection