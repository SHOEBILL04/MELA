@extends('layouts.app', ['header' => 'MELA Events'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800">Exclusive Event Access</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Showing events restricted to the fairs you already possess an entry ticket for.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($events as $event)
            @php $remaining = $event->max_capacity - $event->tickets_sold; @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:border-emerald-200 transition-all flex flex-col group relative">
                @if($remaining > 0 && $remaining <= 5)
                    <div class="absolute top-4 right-4 bg-orange-100 text-orange-700 text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md border border-orange-200">
                        Selling Fast
                    </div>
                @endif
                
                <div class="p-6 pb-5 border-b border-slate-50 bg-slate-50/50 flex flex-col gap-1">
                    <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest">{{ $event->fair_name }}</span>
                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-emerald-600 transition-colors leading-tight">{{ $event->event_name }}</h3>
                </div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex items-center gap-3 mb-6 bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div class="w-10 h-10 bg-white rounded-lg shadow-sm border border-slate-200 flex flex-col items-center justify-center">
                            <span class="text-[9px] font-bold text-slate-400 uppercase leading-none">{{ \Carbon\Carbon::parse($event->event_date)->format('M') }}</span>
                            <span class="text-sm font-black text-slate-800 leading-none mt-0.5">{{ \Carbon\Carbon::parse($event->event_date)->format('d') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Time</span>
                            <span class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                        </div>
                    </div>
                    
                    <div class="mt-auto mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-xs font-bold text-slate-500 uppercase">Availability</span>
                            <span class="text-xs font-black {{ $remaining > 0 ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $remaining > 0 ? $remaining . ' seats left' : 'Full' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                            @php $percent = min(100, ($event->tickets_sold / max(1, $event->max_capacity)) * 100); @endphp
                            <div class="h-1.5 rounded-full {{ $remaining > 0 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('visitor.buyEventTicket', ['eventId' => $event->event_id]) }}">
                        @csrf
                        @if($remaining > 0)
                            <button type="submit" class="w-full bg-slate-900 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                Book Ticket  &middot;  ${{ number_format($event->ticket_price, 2) }}
                            </button>
                        @else
                            <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed border border-slate-200">
                                Event Full
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white border border-slate-200 border-dashed rounded-3xl text-center">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">No Events Found</h3>
                <p class="text-slate-500 font-medium">You must purchase at least one fair entry ticket before matching events will appear here.</p>
                <a href="{{ route('visitor.fairs') }}" class="inline-flex items-center gap-2 px-6 py-2.5 mt-6 border border-transparent rounded-xl shadow-sm text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 transition">
                    Browse Fairs Now
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
