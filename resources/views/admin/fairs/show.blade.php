@extends('layouts.app', ['header' => 'Fair Details'])

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.fairs.index') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Directory
        </a>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl mb-8 text-sm font-bold flex items-center gap-3 shadow-sm">
            <div class="bg-emerald-100 p-1.5 rounded-full text-emerald-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8 flex flex-col md:flex-row">
        <div class="p-8 md:w-2/3 border-b md:border-b-0 md:border-r border-slate-100">
            <div class="flex items-center gap-3 mb-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100">
                    {{ $fair->fair_status }}
                </span>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">ID: #{{ $fair->fair_id }}</span>
            </div>
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-6">{{ $fair->fair_name }}</h2>
            
            <div class="grid grid-cols-2 gap-6">
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Stalls Booked</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $fair->stalls_sold }}</p>
                </div>
                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tickets Sold</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $fair->total_visitors }}</p>
                </div>
            </div>
        </div>
        
        <div class="p-8 md:w-1/3 bg-slate-50 flex flex-col justify-center">
            <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2">Total Revenue</p>
            <p class="text-4xl font-black text-slate-900 tracking-tight">${{ number_format($fair->total_fair_revenue, 2) }}</p>
            
            <div class="mt-6 space-y-3">
                <div class="flex justify-between items-center text-sm border-b border-slate-200 pb-2">
                    <span class="font-semibold text-slate-500">Stalls</span>
                    <span class="font-bold text-slate-800">${{ number_format($fair->stall_revenue, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-200 pb-2">
                    <span class="font-semibold text-slate-500">Entry</span>
                    <span class="font-bold text-slate-800">${{ number_format($fair->entry_revenue, 2) }}</span>
                </div>
                <div class="flex justify-between items-center text-sm border-b border-slate-200 pb-2">
                    <span class="font-semibold text-slate-500">Events</span>
                    <span class="font-bold text-slate-800">${{ number_format($fair->event_revenue, 2) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
