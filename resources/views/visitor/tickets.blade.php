@extends('layouts.app', ['header' => 'My Digital Tickets'])

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">My Tickets & Events</h2>
            <p class="text-slate-500">View all your purchased tickets. Show the QR code at the entrance.</p>
        </div>
        <a href="{{ route('visitor.fairs') }}" class="bg-indigo-50 hover:bg-indigo-100 text-indigo-700 font-bold py-2 px-4 rounded-lg transition-colors border border-indigo-200 text-sm">
            Buy More Tickets
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($allTickets as $ticket)
            <div class="bg-white rounded-2xl shadow-sm border {{ $ticket->type == 'Fair Entry' ? 'border-indigo-100' : 'border-emerald-100' }} overflow-hidden flex flex-col relative">
                
                <div class="{{ $ticket->type == 'Fair Entry' ? 'bg-indigo-600' : 'bg-emerald-600' }} h-2 w-full"></div>
                
                <div class="p-6 flex-grow flex flex-col">
                    <div class="flex justify-between items-start mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-bold {{ $ticket->type == 'Fair Entry' ? 'bg-indigo-50 text-indigo-700' : 'bg-emerald-50 text-emerald-700' }} uppercase tracking-wider">
                            {{ $ticket->type }}
                        </span>
                        <span class="text-lg font-black text-slate-900">${{ number_format($ticket->ticket_price, 2) }}</span>
                    </div>

                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $ticket->title }}</h3>
                    <p class="text-sm font-medium text-slate-500 mb-6 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v11a2 2 0 002 2z"/></svg>
                        Purchased on {{ \Carbon\Carbon::parse($ticket->purchase_date)->format('M d, Y') }}
                    </p>

                    <div class="mt-auto pt-6 border-t border-slate-100 border-dashed flex flex-col items-center">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-3">Entrance Pass</div>
                        
                        <!-- Simple QR Code Representation (If REAL QR generating library isn't present, we'll show an aesthetic block) -->
                        <div class="p-2 border-2 border-slate-200 rounded-xl bg-white shadow-sm inline-block">
                            <!-- Displaying raw Base64 QR from DB or a dummy one for mockup -->
                            @if(!empty($ticket->qr_code))
                                <img src="data:image/svg+xml;base64,{{ base64_encode($ticket->qr_code) }}" alt="QR Code" class="w-32 h-32 object-contain" onerror="this.src='https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($ticket->qr_code) }}'">
                            @else
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=TICKET-{{ $ticket->type }}-{{ $ticket->id }}" alt="QR Code" class="w-32 h-32">
                            @endif
                        </div>
                        
                        <div class="mt-3 font-mono text-xs font-bold text-slate-400 tracking-widest">TICKET-{{ strtoupper(substr(md5($ticket->type . $ticket->id), 0, 8)) }}</div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 bg-white border border-slate-200 border-dashed rounded-2xl text-center flex flex-col items-center justify-center">
                <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">No Tickets Yet</h3>
                <p class="text-slate-500 font-medium mb-6">You haven't purchased any fair entry or event tickets.</p>
                <a href="{{ route('visitor.fairs') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 px-6 rounded-xl transition-colors shadow-sm">
                    Browse Active Fairs
                </a>
            </div>
        @endforelse
    </div>
</div>
@endsection
