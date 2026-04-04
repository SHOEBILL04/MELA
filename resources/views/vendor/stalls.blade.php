@extends('layouts.app', ['header' => 'Live Stall Map'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-black text-slate-800">{{ $fair->name }} - Live Availability</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Select an available stall and confirm your purchase.</p>
        </div>
        <a href="{{ route('vendor.fairs') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-bold py-2.5 px-5 rounded-xl transition-colors">
            &larr; Back to Fairs
        </a>
    </div>

    <!-- Map Grid -->
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-200">
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($stalls as $stall)
                @php
                    $isAvailable = $stall->status === 'available';
                @endphp
                <div class="relative p-4 rounded-xl border-2 {{ $isAvailable ? 'border-blue-100 hover:border-blue-400 bg-blue-50/50 cursor-pointer group' : 'border-slate-100 bg-slate-50 opacity-50 cursor-not-allowed' }} flex flex-col items-center justify-center text-center transition-all h-32"
                     @if($isAvailable) onclick="confirmPurchase({{ $stall->stall_id }}, '{{ $stall->stall_number }}', {{ $stall->price }})" @endif>
                    
                    <span class="text-xs font-bold uppercase tracking-wider {{ $isAvailable ? 'text-blue-500' : 'text-slate-400' }} mb-1">
                        Stall
                    </span>
                    <span class="text-2xl font-black {{ $isAvailable ? 'text-slate-800 group-hover:text-blue-600' : 'text-slate-400' }}">
                        #{{ $stall->stall_number }}
                    </span>
                    
                    @if($isAvailable)
                        <span class="mt-2 text-xs font-black text-emerald-600 bg-emerald-50 px-2 py-1 rounded-md">
                            ${{ number_format($stall->price, 2) }}
                        </span>
                    @else
                        <span class="mt-2 text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-200 px-2 py-0.5 rounded-sm">
                            Sold Out
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
function confirmPurchase(stallId, stallNumber, price) {
    if (confirm(`Are you sure you want to purchase Stall #${stallNumber} for $${price.toFixed(2)}?`)) {
        
        fetch("{{ route('vendor.buy_stall') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ stall_id: stallId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                alert('Success! ' + data.message);
                window.location.reload();
            } else {
                alert('Error: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('A network error occurred.');
        });
    }
}
</script>
@endsection
