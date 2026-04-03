@extends('layouts.app', ['header' => 'Event Ticket Sales'])

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('vendor.events') }}" class="inline-flex items-center text-sm font-bold text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Events
        </a>
    </div>

    <!-- Event Summary Card -->
    <div class="bg-indigo-600 rounded-2xl shadow-sm border border-indigo-700 overflow-hidden mb-8 text-white relative">
        <div class="absolute inset-0 opacity-10 bg-[url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <div class="p-8 relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <span class="inline-block bg-white/20 px-3 py-1 text-xs font-black uppercase tracking-widest rounded-lg mb-3 border border-white/10">Event Overview</span>
                <h2 class="text-3xl font-black mb-1">{{ $event->name }}</h2>
                <div class="flex items-center gap-4 text-indigo-100 font-medium">
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> {{ \Carbon\Carbon::parse($event->event_date)->format('M d, Y') }}</span>
                    <span class="flex items-center gap-1"><svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</span>
                </div>
            </div>
            
            <div class="flex gap-4">
                <div class="bg-indigo-700/50 backdrop-blur-sm rounded-xl p-4 text-center min-w-24 border border-indigo-500/30">
                    <span class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">Tickets Sold</span>
                    <span class="block text-2xl font-black">{{ $event->tickets_sold }} <span class="text-xs text-indigo-300 font-bold">/ {{ $event->max_capacity }}</span></span>
                </div>
                <div class="bg-indigo-700/50 backdrop-blur-sm rounded-xl p-4 text-center min-w-24 border border-indigo-500/30">
                    <span class="block text-[10px] font-black uppercase tracking-widest text-indigo-200 mb-1">Total Revenue</span>
                    <span class="block text-2xl font-black text-emerald-300">${{ number_format($event->tickets_sold * $event->ticket_price, 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Buyers List Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">Purchaser Details</h3>
            <span class="bg-slate-200 text-slate-700 text-xs font-bold px-3 py-1 rounded-full">{{ count($buyers) }} records</span>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 text-slate-500 text-xs uppercase tracking-widest font-black border-b border-slate-100">
                        <th class="px-6 py-4">Visitor</th>
                        <th class="px-6 py-4">Purchase Date</th>
                        <th class="px-6 py-4">QR Code Ref</th>
                        <th class="px-6 py-4 text-right">Amount Paid</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-medium text-slate-700 divide-y divide-slate-50">
                    @forelse($buyers as $buyer)
                        <tr class="hover:bg-slate-50/50 transition-colors cursor-default">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs shrink-0">
                                        {{ substr($buyer->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900">{{ $buyer->name }}</div>
                                        <div class="text-[11px] text-slate-500">{{ $buyer->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ \Carbon\Carbon::parse($buyer->purchase_date)->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-slate-100 text-slate-600 px-2 py-1 rounded-md font-mono text-[10px]">{{ $buyer->qr_code ?? 'Pending' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <span class="font-black text-emerald-600">${{ number_format($buyer->ticket_price, 2) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="font-medium">No tickets have been sold yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
