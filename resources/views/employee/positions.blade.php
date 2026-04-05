@extends('layouts.app', ['header' => 'Available Positions'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Job Board</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Browse stalls currently recruiting employees across all active fairs.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('employee.history') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-xl font-bold text-sm transition shadow-sm flex items-center gap-2 h-full border border-indigo-700">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                My History
            </a>

            <form method="GET" action="{{ route('employee.positions') }}" class="flex items-center gap-3 bg-white p-2 border border-slate-200 rounded-xl shadow-sm">
                <div class="relative">
                    <select name="fair_status" class="appearance-none pl-4 pr-10 py-2 border-0 bg-transparent text-sm font-medium text-slate-700 focus:ring-0 cursor-pointer">
                        <option value="all" @selected($statusFilter == 'all')>All Fairs</option>
                        <option value="upcoming" @selected($statusFilter == 'upcoming')>Upcoming Only</option>
                        <option value="active" @selected($statusFilter == 'active')>Active Now</option>
                        <option value="completed" @selected($statusFilter == 'completed')>Completed</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="w-px h-6 bg-slate-200"></div>
                <button type="submit" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors">
                    Apply Filter
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($positions as $pos)
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md hover:border-indigo-100 transition-all flex flex-col h-full group">
                <div class="p-6 flex-grow">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 uppercase tracking-wider">
                            {{ ucfirst($pos->fair_status) }}
                        </span>
                        <span class="text-lg font-black text-slate-900">${{ number_format($pos->salary, 2) }}<span class="text-xs text-slate-400 font-medium">/mo</span></span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors">{{ $pos->title }}</h3>
                    
                    <p class="text-sm font-medium text-slate-500 mt-1 mb-6 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        Stall {{ $pos->stall_number }} @if($pos->category) ({{ $pos->category }}) @endif
                    </p>

                    <div class="bg-slate-50 rounded-lg p-3 border border-slate-100 flex items-center gap-3">
                        <div class="bg-white p-2 rounded shadow-sm border border-slate-200">
                            <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider focus">Event Location</p>
                            <p class="text-sm text-slate-800 font-semibold">{{ $pos->fair_name }}</p>
                        </div>
                    </div>
                </div>

                {{-- This part shows the apply button or a disabled button if already applied --}}
                <div class="px-6 pb-6 mt-auto">
                    @if(in_array($pos->position_id, $appliedPositionsArray))
                        <button disabled class="w-full bg-slate-100 border border-slate-200 text-slate-400 font-bold py-2.5 px-4 rounded-xl cursor-not-allowed flex items-center justify-center gap-2">
                            ✅ Already Applied
                        </button>
                    @else
                        <form method="POST" action="{{ route('employee.apply', $pos->position_id) }}">
                            @csrf
                            <button type="submit" class="w-full bg-slate-900 hover:bg-indigo-600 text-white font-bold py-2.5 px-4 rounded-xl transition-colors shadow-sm flex items-center justify-center gap-2">
                                Quick Apply
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white border border-slate-200 border-dashed rounded-2xl text-center">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">No Open Positions</h3>
                <p class="text-slate-500 font-medium">There are currently no job openings matching your filters.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection