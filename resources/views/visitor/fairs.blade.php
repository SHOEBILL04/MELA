@extends('layouts.app', ['header' => 'MELA Fairs'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800">Browse Active Fairs</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Discover and purchase entry tickets to upcoming fairs. An entry ticket is required before purchasing dedicated event access.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($fairDays as $day)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <div class="h-2 w-full {{ $day->remaining_slots > 0 ? 'bg-indigo-500' : 'bg-slate-300' }}"></div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight pr-4">{{ $day->name }}</h3>
                        <div class="flex flex-col items-center justify-center p-2 bg-slate-50 rounded-lg border border-slate-200 min-w-16">
                            <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">{{ \Carbon\Carbon::parse($day->day_date)->format('M') }}</span>
                            <span class="text-xl font-black text-slate-900">{{ \Carbon\Carbon::parse($day->day_date)->format('d') }}</span>
                        </div>
                    </div>

                    <p class="text-sm font-medium text-slate-500 mb-6 flex items-start gap-1.5 flex-grow">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $day->location }}
                    </p>
                    
                    <div class="mt-auto mb-6">
                        <div class="flex justify-between items-end mb-1">
                            <span class="text-xs font-bold text-slate-500 uppercase">Capacity Status</span>
                            <span class="text-xs font-black {{ $day->remaining_slots > 0 ? 'text-indigo-600' : 'text-red-600' }}">
                                {{ $day->remaining_slots > 0 ? $day->remaining_slots . ' tickets left' : 'Sold Out' }}
                            </span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                            @php $percent = min(100, ($day->visitors_count / max(1, $day->max_visitors)) * 100); @endphp
                            <div class="h-2 rounded-full {{ $day->remaining_slots > 0 ? 'bg-indigo-500' : 'bg-red-500' }}" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 text-right mt-1">{{ $day->visitors_count }} / {{ $day->max_visitors }} sold</p>
                    </div>
                    
                    <form method="POST" action="{{ route('visitor.buyFairTicket', ['fairId' => $day->fair_id, 'dayId' => $day->day_id]) }}">
                        @csrf
                        @if($day->remaining_slots > 0)
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                Buy Entry Ticket  &middot;  $50.00
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        @else
                            <button disabled class="w-full bg-slate-100 text-slate-400 font-bold py-3 px-4 rounded-xl cursor-not-allowed border border-slate-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                Sold Out
                            </button>
                        @endif
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white border border-slate-200 border-dashed rounded-3xl text-center">
                <div class="w-20 h-20 bg-indigo-50 text-indigo-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">No Fairs Available</h3>
                <p class="text-lg text-slate-500 font-medium">There are currently no active fairs to browse.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
