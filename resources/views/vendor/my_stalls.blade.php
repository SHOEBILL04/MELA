@extends('layouts.app', ['header' => 'My Purchased Stalls'])

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">My Purchased Stalls</h2>
            <p class="text-slate-500">Full history of your stall bookings, employee counts, and event revenue.</p>
        </div>
    </div>

    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-indigo-100 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase text-xs font-bold">
                        <th class="p-4 border-b">Fair Name</th>
                        <th class="p-4 border-b">Stall No</th>
                        <th class="p-4 border-b">Cost Price</th>
                        <th class="p-4 border-b">Stall Revenue</th>
                        <th class="p-4 border-b text-center">Employees</th>
                        <th class="p-4 border-b text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($myStalls as $stall)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-medium text-slate-800">{{ $stall->fair_name }}</td>
                        <td class="p-4"><span class="bg-indigo-50 text-indigo-700 px-2 py-1 rounded text-xs font-mono font-bold">#{{ $stall->stall_number }}</span></td>
                        <td class="p-4 font-semibold text-slate-500">${{ number_format($stall->price, 2) }}</td>
                        <td class="p-4 font-bold text-emerald-600">${{ number_format($stall->stall_revenue, 2) }}</td>
                        <td class="p-4 text-center font-bold text-slate-700">
                            <span class="bg-slate-100 px-2.5 py-0.5 rounded-full text-sm">
                                {{ $stall->employee_count }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <button onclick="alert('--- RECEIPT ---\nFair: {{ $stall->fair_name }}\nStall: {{ $stall->stall_number }}')" 
                                class="text-indigo-600 hover:text-indigo-900 text-sm font-bold transition hover:underline">
                                View Receipt
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-12 text-center text-slate-400 italic">No stalls purchased yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection