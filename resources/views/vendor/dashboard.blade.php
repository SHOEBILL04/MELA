@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Vendor Dashboard</h2>
            <p class="text-slate-500">Manage your bookings and track stall statuses.</p>
        </div>
        <div class="bg-indigo-50 px-4 py-2 rounded-lg">
            <span class="text-indigo-700 font-bold text-lg">{{ $myStalls->count() }}</span>
            <span class="text-indigo-600 text-sm ml-1">Stalls Secured</span>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-indigo-100">
        <div class="p-6">
            <h3 class="text-lg font-bold text-slate-700 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                My Purchased Stalls Details
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 uppercase text-xs font-bold">
                            <th class="p-4 border-b">Fair Name</th>
                            <th class="p-4 border-b">Stall No</th>
                            <th class="p-4 border-b">Price</th>
                            <th class="p-4 border-b">Status</th>
                            <th class="p-4 border-b text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($myStalls as $stall)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="p-4 font-medium text-slate-800">{{ $stall->fair_name }}</td>
                            <td class="p-4">
                                <span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-mono font-bold">
                                    #{{ $stall->stall_number }}
                                </span>
                            </td>
                            <td class="p-4 font-semibold text-slate-700">${{ number_format($stall->price, 2) }}</td>
                            <td class="p-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200">
                                    {{ $stall->status }}
                                </span>
                            <td class="p-4 text-right">
    <button onclick="alert('--- OFFICIAL RECEIPT ---\n\nStall Number: {{ $stall->stall_number }}\nFair: {{ $stall->fair_name }}\nPrice: ${{ number_format($stall->price, 2) }}\nStatus: {{ $stall->status }}\n\nThank you for securing your stall!')" 
            class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition hover:underline">
        View Receipt
    </button>
</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="p-12 text-center">
                                <div class="flex flex-col items-center">
                                    <p class="text-slate-400 italic mb-4">No stalls purchased yet.</p>
                                    <a href="{{ route('vendor.fairs') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 transition">
                                        Browse Fairs to Book
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection