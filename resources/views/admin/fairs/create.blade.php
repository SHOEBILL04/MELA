@extends('layouts.app', ['header' => 'Create New Fair'])

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h3 class="text-lg font-bold text-slate-800">Fair Setup Details</h3>
            <p class="text-sm text-slate-500 font-medium mt-1">Configure a new fair and its global capacities. This will automatically generate active days.</p>
        </div>
        
        <div class="p-8">
            @if(session('success'))
                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-4 rounded-xl mb-8 text-sm font-bold flex items-center gap-3 shadow-sm">
                    <div class="bg-emerald-100 p-1.5 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-4 rounded-xl mb-8 text-sm font-bold flex items-center gap-3 shadow-sm">
                    <div class="bg-red-100 p-1.5 rounded-full text-red-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    {{ session('error') }}
                </div>
            @endif

            <form action="{{ route('admin.fairs.store') }}" method="POST" autocomplete="off" class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Fair Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required maxlength="150" autocomplete="off"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('name') border-red-300 ring-red-200 @enderror">
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="location" class="block text-sm font-semibold text-slate-700 mb-1.5">Location</label>
                    <input type="text" id="location" name="location" value="{{ old('location') }}" required maxlength="200" autocomplete="off"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('location') border-red-300 ring-red-200 @enderror">
                    @error('location')
                        <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="start_date" class="block text-sm font-semibold text-slate-700 mb-1.5">Start Date</label>
                        <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}" required autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm text-slate-700 @error('start_date') border-red-300 ring-red-200 @enderror">
                        @error('start_date')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="end_date" class="block text-sm font-semibold text-slate-700 mb-1.5">End Date</label>
                        <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" required autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm text-slate-700 @error('end_date') border-red-300 ring-red-200 @enderror">
                        @error('end_date')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="total_stalls" class="block text-sm font-semibold text-slate-700 mb-1.5">Total Stalls Capacity</label>
                        <input type="number" id="total_stalls" name="total_stalls" value="{{ old('total_stalls') }}" required min="1" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('total_stalls') border-red-300 ring-red-200 @enderror">
                        @error('total_stalls')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="default_limit" class="block text-sm font-semibold text-slate-700 mb-1.5">Daily Visitor Limit</label>
                        <input type="number" id="default_limit" name="default_limit" value="{{ old('default_limit') }}" required min="1" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('default_limit') border-red-300 ring-red-200 @enderror">
                        @error('default_limit')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="default_stall_price" class="block text-sm font-semibold text-slate-700 mb-1.5">Default Stall Price ($)</label>
                        <input type="number" id="default_stall_price" name="default_stall_price" value="{{ old('default_stall_price', '500.00') }}" step="0.01" required min="1" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('default_stall_price') border-red-300 ring-red-200 @enderror">
                        @error('default_stall_price')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                    <div>
                        <label for="default_ticket_price" class="block text-sm font-semibold text-slate-700 mb-1.5">Default Visitor Ticket Price ($)</label>
                        <input type="number" id="default_ticket_price" name="default_ticket_price" value="{{ old('default_ticket_price', '50.00') }}" step="0.01" required min="0" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm @error('default_ticket_price') border-red-300 ring-red-200 @enderror">
                        @error('default_ticket_price')
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-sm shadow-indigo-600/20 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        Deploy New Fair
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
