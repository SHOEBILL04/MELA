@extends('layouts.app', ['header' => 'Event Management'])

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- Error and Success Messages --}}
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 flex items-center gap-3 shadow-sm">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl p-4 shadow-sm">
            <ul class="list-disc pl-5 font-semibold text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create New Event Form --}}
    <div class="bg-white rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-black text-slate-800">Host New Event</h2>
                <p class="text-sm font-medium text-slate-500 mt-1">Setup a specialized event within a fair where you hold a stall.</p>
            </div>
            <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
            </div>
        </div>

        <div class="p-8">
            <form action="{{ route('vendor.events.store') }}" method="POST" class="space-y-6 flex flex-col">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    
                    {{-- Fair Selection --}}
                    <div class="space-y-2 lg:col-span-3">
                        <label class="text-sm font-bold text-slate-700">Select Associated Fair</label>
                        <select name="fair_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-medium transition-all">
                            <option value="">-- Choose Fair --</option>
                            @forelse($stalledFairs as $fair)
                                <option value="{{ $fair->fair_id }}">{{ $fair->name }}</option>
                            @empty
                                <option disabled>No active stalls found. Purchase a stall first.</option>
                            @endforelse
                        </select>
                    </div>

                    {{-- Event Name --}}
                    <div class="space-y-2 lg:col-span-3">
                        <label class="text-sm font-bold text-slate-700">Event Title</label>
                        <input type="text" name="name" required placeholder="e.g. Traditional Music Concert" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-medium transition-all">
                    </div>

                    {{-- Event Date --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Event Date</label>
                        <input type="date" name="event_date" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-medium transition-all">
                    </div>

                    {{-- Start Time --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Start Time</label>
                        <input type="time" name="start_time" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-medium transition-all">
                    </div>

                    {{-- End Time --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">End Time</label>
                        <input type="time" name="end_time" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 font-medium transition-all">
                    </div>
                    
                    {{-- Capacity --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Max Capacity</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                            </div>
                            <input type="number" name="max_capacity" required min="1" placeholder="e.g. 50" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block p-3 pl-10 font-medium transition-all">
                        </div>
                    </div>

                    {{-- Price --}}
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Ticket Price ($)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold">$</span>
                            </div>
                            <input type="number" name="ticket_price" required min="0" step="0.01" placeholder="e.g. 15.00" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 block p-3 pl-8 font-medium transition-all">
                        </div>
                    </div>
                </div>

                <div class="pt-6 border-t border-slate-100 mt-6 lg:col-span-3 flex justify-end">
                    <button type="submit" class="px-8 py-3.5 bg-slate-900 hover:bg-indigo-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-slate-900/20 hover:shadow-indigo-500/30 transition-all flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" /></svg>
                        Publish Event
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Browse Created Events --}}
    <div class="mt-8">
        <h2 class="text-xl font-bold text-slate-800 mb-6">Your Hosted Events</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($events as $event)
                @php $remaining = $event->max_capacity - $event->tickets_sold; @endphp
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-all flex flex-col relative group">
                    
                    <div class="p-6 pb-5 border-b border-slate-50 bg-slate-50/50 flex flex-col gap-1">
                        <span class="text-[10px] font-black text-indigo-600 uppercase tracking-widest">{{ $event->fair_name }}</span>
                        <h3 class="text-xl font-bold text-slate-900 group-hover:text-indigo-600 transition-colors leading-tight">{{ $event->name }}</h3>
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
                        
                        <div class="mt-auto mb-2">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs font-bold text-slate-500 uppercase">Sales</span>
                                <span class="text-xs font-black text-indigo-600">
                                    {{ $event->tickets_sold }} / {{ $event->max_capacity }}
                                </span>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                @php $percent = min(100, ($event->tickets_sold / max(1, $event->max_capacity)) * 100); @endphp
                                <div class="h-1.5 rounded-full bg-indigo-500" style="width: {{ $percent }}%"></div>
                            </div>
                        </div>
                        
                        <div class="pt-4 mt-2 border-t border-slate-100 flex flex-col gap-3">
                            <div class="flex justify-between items-center">
                                <span class="text-xs font-semibold text-slate-500">Ticket Price</span>
                                <span class="text-lg font-black text-slate-800">${{ number_format($event->ticket_price, 2) }}</span>
                            </div>
                            
                            <a href="{{ route('vendor.events.buyers', ['id' => $event->event_id]) }}" class="w-full text-center bg-slate-50 hover:bg-indigo-50 border border-slate-200 hover:border-indigo-200 text-slate-700 hover:text-indigo-700 text-xs font-bold py-2 px-3 rounded-lg transition-all flex items-center justify-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                View Ticket Sales
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 bg-white border border-slate-200 border-dashed rounded-3xl text-center">
                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-4 border border-slate-200 shadow-inner">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-1">No Hosted Events</h3>
                    <p class="text-slate-500 font-medium">Use the form above to start hosting an event at your stall.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
