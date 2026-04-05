@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    
    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-6 font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-xl mb-6 font-bold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Vendor Dashboard</h2>
            <p class="text-slate-500">Welcome back! Manage your recruitment requests here.</p>
        </div>
        <div class="bg-indigo-50 px-4 py-2 rounded-lg text-center">
            <span class="text-indigo-700 font-bold text-lg block">{{ $stallCount ?? 0 }}</span>
            <span class="text-indigo-600 text-xs uppercase font-bold">Stalls Owned</span>
        </div>
    </div>

    <div class="mb-8">
        <h3 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            Employee Applications
        </h3>

        <div class="bg-white rounded-xl border border-indigo-100 shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="p-4 text-xs font-bold uppercase text-slate-500">Applicant</th>
                        <th class="p-4 text-xs font-bold uppercase text-slate-500">Position</th>
                        <th class="p-4 text-xs font-bold uppercase text-slate-500">Applied Date</th>
                        <th class="p-4 text-xs font-bold uppercase text-slate-500 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($applications as $app)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="p-4 font-bold text-slate-700">{{ $app->user_name }}</td>
                        <td class="p-4 text-slate-600">{{ $app->title }}</td>
                        <td class="p-4 text-slate-400 text-sm">{{ $app->applied_at }}</td>
                        <td class="p-4 text-right">
                            
                            @if($app->status == 'pending')
                                <form action="{{ route('vendor.recruit', $app->application_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Approve this recruitment?')" 
                                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-bold text-xs transition shadow-md shadow-indigo-100">
                                        APPROVE & RECRUIT
                                    </button>
                                </form>
                            @elseif($app->status == 'approved')
                                <button disabled class="bg-green-100 text-green-700 border border-green-200 px-4 py-2 rounded-lg font-black text-xs cursor-not-allowed uppercase tracking-widest">
                                    ✅ HIRED
                                </button>
                            @else
                                <span class="text-slate-400 italic font-bold uppercase text-xs">{{ $app->status }}</span>
                            @endif

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-12 text-center text-slate-400 italic font-medium">No applications found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection