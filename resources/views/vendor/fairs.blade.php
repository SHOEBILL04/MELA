@extends('layouts.app', ['header' => 'Vendor Fair Directory'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h2 class="text-xl font-bold text-slate-800">Browse Fairs to Book Stalls</h2>
        <p class="text-sm font-medium text-slate-500 mt-1">Select an active fair to view the live stall availability map and secure your prime location.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fairs as $fair)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg hover:-translate-y-1 transition-all duration-300 flex flex-col group">
                <div class="h-2 w-full bg-blue-500"></div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-blue-600 transition-colors leading-tight pr-4">{{ $fair->name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100">
                            {{ $fair->status }}
                        </span>
                    </div>

                    <p class="text-sm font-medium text-slate-500 mb-6 flex items-start gap-1.5">
                        <svg class="w-4 h-4 text-slate-400 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $fair->location }}
                    </p>
                    
                    <div class="mt-auto">
                        <a href="{{ route('vendor.stalls', $fair->fair_id) }}" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                            View Live Stall Map
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-24 bg-white border border-slate-200 border-dashed rounded-3xl text-center">
                <div class="w-20 h-20 bg-blue-50 text-blue-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-1">No Fairs Available</h3>
                <p class="text-lg text-slate-500 font-medium">There are currently no active fairs available for stall booking.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
