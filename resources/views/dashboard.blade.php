@extends('layouts.app', ['header' => 'Dashboard Portal'])

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="px-8 py-10 text-center bg-slate-50/50 border-b border-slate-100">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight mb-2 flex items-center justify-center gap-3">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h2>
            <p class="text-slate-500 font-medium text-lg">You are signed in as a <span class="text-indigo-600 uppercase tracking-wider font-bold">{{ auth()->user()->role }}</span>.</p>
        </div>

        <div class="p-8">
            <h3 class="text-sm font-bold tracking-widest text-slate-400 uppercase mb-6 text-center">Quick Access Portals</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'vendor' || auth()->user()->role === 'employee' || auth()->user()->role === 'visitor')
                    <!-- Always show portals based on role, but since testing allows cross-role, we show links to allowed sections -->
                @endif
                
                @if(in_array(auth()->user()->role, ['admin']))
                    <a href="{{ route('admin.fairs.create') }}" class="group block p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:shadow-indigo-500/10 transition-all text-center">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Create Fair</h4>
                        <p class="text-sm text-slate-500 font-medium">Manage and launch new fairs</p>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['vendor', 'admin']))
                    <a href="{{ route('vendor.fairs') }}" class="group block p-6 bg-white border border-slate-200 rounded-xl hover:border-blue-500 hover:shadow-md hover:shadow-blue-500/10 transition-all text-center">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Vendor Portal</h4>
                        <p class="text-sm text-slate-500 font-medium">Manage stalls and events</p>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['employee', 'admin']))
                    <a href="{{ route('employee.positions') }}" class="group block p-6 bg-white border border-slate-200 rounded-xl hover:border-emerald-500 hover:shadow-md hover:shadow-emerald-500/10 transition-all text-center">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Employee Portal</h4>
                        <p class="text-sm text-slate-500 font-medium">Browse jobs and apply</p>
                    </a>
                @endif

                @if(in_array(auth()->user()->role, ['visitor', 'admin']))
                    <a href="{{ route('visitor.fairs') }}" class="group block p-6 bg-white border border-slate-200 rounded-xl hover:border-indigo-500 hover:shadow-md hover:shadow-indigo-500/10 transition-all text-center">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 mb-1">Visitor Portal</h4>
                        <p class="text-sm text-slate-500 font-medium">Browse fairs and buy tickets</p>
                    </a>
                @endif
            </div>

            <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-lg text-sm font-semibold text-red-600 bg-red-50 hover:bg-red-100 transition-colors">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Sign Out Completely
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection