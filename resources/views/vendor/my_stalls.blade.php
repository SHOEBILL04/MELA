@extends('layouts.app', ['header' => 'My Purchased Stalls'])

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mb-8 bg-white p-6 rounded-xl shadow-sm border border-slate-200 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">My Purchased Stalls</h2>
            <p class="text-slate-500">Full history of your stall bookings, employee counts, and event revenue.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-bold shadow-sm">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-bold shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-indigo-100 p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 text-slate-600 uppercase text-xs font-bold font-mono">
                        <th class="p-4 border-b">Fair Details</th>
                        <th class="p-4 border-b">Stall Number</th>
                        <th class="p-4 border-b">Stall Stats</th>
                        <th class="p-4 border-b text-center">Employees</th>
                        <th class="p-4 border-b text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($myStalls as $stall)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="p-4">
                            <span class="font-bold text-slate-800 block">{{ $stall->fair_name }}</span>
                            <span class="text-[10px] uppercase font-bold text-slate-400">Owner ID: #{{ auth()->id() }}</span>
                        </td>
                        <td class="p-4">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-lg text-xs font-bold border border-indigo-100 italic">
                                {{ $stall->stall_number }}
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-xs font-bold text-slate-400 uppercase tracking-tighter">Revenue</span>
                                <span class="text-emerald-600 font-black text-sm">${{ number_format($stall->stall_revenue, 2) }}</span>
                            </div>
                        </td>
                        <td class="p-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <span class="text-sm font-bold text-slate-700">{{ $stall->employee_count }}</span>
                                <svg class="w-4 h-4 text-slate-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/></svg>
                            </div>
                        </td>
                        <td class="p-4 text-right flex gap-2 justify-end">
                            <button onclick="openHireModal({{ $stall->stall_id }}, '{{ $stall->stall_number }}')" 
                                class="bg-indigo-600 hover:bg-slate-800 text-white px-4 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all shadow-md active:scale-95">
                                RECRUIT
                            </button>
                            <button onclick="alert('Receipt ID: STALL-{{ $stall->stall_id }}')" class="p-2 text-slate-400 hover:text-indigo-600 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-12 text-center text-slate-400 italic font-medium">You don't own any stalls yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Simple Recruitment Modal -->
<div id="jobModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen p-4">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900 bg-opacity-75 backdrop-blur-sm transition-opacity" onclick="closeJobModal()"></div>

        <!-- Modal Container -->
        <div class="relative bg-white rounded-3xl overflow-hidden shadow-2xl transform transition-all sm:max-w-lg sm:w-full border border-slate-100">
            <form action="{{ route('vendor.positions.create') }}" method="POST" id="recruitmentForm">
                @csrf
                <input type="hidden" name="stall_id" id="modal_stall_id">
                
                <div class="px-8 py-6 bg-slate-50/50 border-b border-slate-100">
                    <h3 class="text-xl font-bold text-slate-900 flex items-center gap-3">
                        <span class="bg-indigo-100 p-2 rounded-xl text-indigo-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </span>
                        Post Job Vacancy
                    </h3>
                </div>

                <div class="px-8 py-6 space-y-5">
                    <div class="flex items-center gap-3 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-50">
                        <span class="text-xs font-black uppercase text-indigo-400">Recruiting for Stall</span>
                        <span id="modal_stall_number_display" class="text-sm font-black text-indigo-700 italic"></span>
                    </div>

                    <div>
                        <label for="title" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Job Position Name</label>
                        <input type="text" name="title" id="title" required placeholder="e.g. Counter Sales, Security" 
                            class="w-full px-5 py-3 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-800 outline-none">
                        @error('title') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="salary" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Monthly Salary (Estimated)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <span class="text-slate-400 font-bold">$</span>
                            </div>
                            <input type="number" name="salary" id="salary" step="1" min="0" placeholder="0.00" 
                                class="w-full pl-10 pr-5 py-3 rounded-2xl bg-slate-50 border-transparent focus:bg-white focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all font-bold text-slate-800 outline-none">
                        </div>
                    </div>
                </div>

                <div class="px-8 py-6 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end gap-4">
                    <button type="button" onclick="closeJobModal()" class="text-sm font-black text-slate-400 hover:text-slate-600 transition">CANCEL</button>
                    <button type="submit" class="bg-indigo-600 hover:bg-slate-900 text-white px-8 py-3 rounded-2xl text-xs font-black tracking-widest uppercase shadow-xl shadow-indigo-500/20 active:scale-95 transition-all">
                        PUBLISH JOB
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openHireModal(stallId, stallNumber) {
        console.log('Hire Modal Triggered for:', stallId);
        // Set values directly
        document.getElementById('modal_stall_id').value = stallId;
        document.getElementById('modal_stall_number_display').innerText = '#' + stallNumber;
        
        // Show modal
        const modal = document.getElementById('jobModal');
        modal.classList.remove('hidden');
        
        // Anti-scroll body
        document.body.style.overflow = 'hidden';
    }

    function closeJobModal() {
        const modal = document.getElementById('jobModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close on ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeJobModal();
        }
    });

    // Form feedback
    document.getElementById('recruitmentForm').onsubmit = function() {
        const titleVal = document.getElementById('title').value.trim();
        if(!titleVal) {
            alert('Please enter a Job Position Name.');
            return false;
        }
        const btn = this.querySelector('button[type="submit"]');
        btn.disabled = true;
        btn.innerText = 'PUBLISHING...';
        return true;
    };
</script>
@endsection