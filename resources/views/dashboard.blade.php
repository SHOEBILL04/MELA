@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-indigo-100 p-10 mb-8 text-center">
        <h2 class="text-3xl font-black text-slate-800 mb-2">
            Welcome back, {{ Auth::user()->name }}! 👋
        </h2>
        <p class="text-slate-500 font-medium">You are currently managing your MELA vendor portal.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-indigo-600 p-8 rounded-2xl shadow-lg shadow-indigo-100 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-sm font-bold uppercase tracking-widest mb-1">Status</p>
                <p class="text-2xl font-black">Active Vendor</p>
            </div>
            <svg class="absolute -right-4 -bottom-4 w-24 h-24 text-indigo-500 opacity-30" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5z"></path></svg>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-1">Quick Action</p>
            <p class="text-xl font-bold text-slate-800 mb-4">Need help?</p>
            <a href="mailto:support@mela.com" class="text-indigo-600 font-bold text-sm hover:underline italic">Contact Admin Support &rarr;</a>
        </div>

        <div class="bg-white p-8 rounded-2xl border border-slate-100 shadow-sm">
            <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mb-1">System Version</p>
            <p class="text-xl font-bold text-slate-800">MELA v3.1</p>
            <p class="text-xs text-slate-400 mt-2 font-mono">Developed by Partha Biswas</p>
        </div>
    </div>
</div>
@endsection