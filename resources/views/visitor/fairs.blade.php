@extends('layouts.app', ['header' => 'MELA Fairs'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800">Browse Active Fairs</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Discover incoming MELA festivals and explore their available dates.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fairs as $fair)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex flex-col group hover:shadow-lg hover:-translate-y-1 transition-all">
                <div class="flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-amber-600 transition-colors">{{ $fair->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider {{ $fair->status === 'active' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">
                            {{ $fair->status }}
                        </span>
                    </div>

                    <p class="text-sm font-medium text-slate-500 mb-6 flex items-start gap-1.5 line-clamp-2">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $fair->location }}
                    </p>
                </div>
                
                <div class="mt-4 pt-4 border-t border-slate-100 flex justify-between items-center">
                    <span class="text-xs font-bold text-slate-500">
                        {{ \Carbon\Carbon::parse($fair->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($fair->end_date)->format('M d, Y') }}
                    </span>
                    <a href="{{ route('visitor.fair_days', $fair->fair_id) }}" class="inline-flex items-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold py-2 px-4 rounded-xl text-sm transition-colors">
                        View Dates
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white border border-slate-200 border-dashed rounded-3xl text-center">
                <div class="w-20 h-20 bg-amber-50 text-amber-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">No Fairs Available</h3>
                <p class="text-lg text-slate-500 font-medium">There are currently no upcoming fairs scheduled.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
