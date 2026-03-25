@extends('layouts.app', ['header' => 'Stall Bids & Proposals'])

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-end mb-8">
        <div>
            <h2 class="text-2xl font-black text-slate-800 tracking-tight">Vendor Stall Bids</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Review, accept, or reject incoming stall purchase proposals from vendors.</p>
        </div>
    </div>

    <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-200">
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Fair & Stall</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Vendor Context</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Bid Structure</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bids as $bid)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-900">{{ $bid->fair_name }}</span>
                                    <span class="text-xs font-medium text-slate-500">Stall #{{ $bid->stall_number }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-800">{{ $bid->vendor_name }}</span>
                                    <span class="text-xs font-medium text-slate-500">{{ $bid->vendor_email }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex flex-col items-end">
                                    <span class="text-sm font-black text-emerald-600">${{ number_format($bid->bid_amount, 2) }}</span>
                                    <span class="text-xs font-medium text-slate-400">Base: ${{ number_format($bid->base_price, 2) }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($bid->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-200">Pending</span>
                                @elseif($bid->status === 'accepted')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-200">Accepted</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-red-50 text-red-600 border border-red-200">Rejected</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($bid->status === 'pending')
                                    <div class="flex items-center justify-end gap-2">
                                        <form method="POST" action="{{ route('admin.bids.approve', $bid->bid_id) }}">
                                            @csrf
                                            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-3 py-1.5 rounded-lg text-xs transition-colors shadow-sm focus:ring-2 focus:ring-emerald-500/50">Accept</button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.bids.reject', $bid->bid_id) }}">
                                            @csrf
                                            <button type="submit" class="bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 focus:ring-2 focus:ring-slate-200 font-bold px-3 py-1.5 rounded-lg text-xs transition-colors shadow-sm">Reject</button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">Processed</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <p class="text-slate-500 font-medium text-sm">No stall bids have been submitted yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
