<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MELA - Fair Management System</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.svg') }}">
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col">
        <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-2xl font-black text-indigo-600 tracking-tight flex items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                MELA
                            </a>
                        </div>

                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                @if(auth()->user()->role === 'vendor')
                                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition">
                                        Dashboard
                                    </a>
                                    <a href="{{ route('vendor.my_stalls') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.my_stalls') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition ml-8">
                                        My Stalls
                                    </a>
                                    <a href="{{ route('vendor.fairs') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.fairs') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition ml-8">
                                        Browse Fairs
                                    </a>
                                    <a href="{{ route('vendor.events') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.events') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition ml-8">
                                        Events
                                    </a>
                                @endif

                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.fairs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.fairs.*') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition">
                                        
                                    Manage Fairs
                                    </a>
                                @endif

                                @if(auth()->user()->role === 'visitor')
                                    <a href="{{ route('visitor.fairs') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visitor.fairs') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition">
                                        Browse Fairs
                                    </a>
                                    <a href="{{ route('visitor.events') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visitor.events') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition ml-8">
                                        Browse Events
                                    </a>
                                    <a href="{{ route('visitor.tickets') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visitor.tickets') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition ml-8">
                                        My Tickets
                                    </a>
                                @endif

                                @if(auth()->user()->role === 'employee')
                                    <a href="{{ route('employee.positions') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('employee.positions') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 transition">
                                        Job Board
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col text-right">
                                        <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                                        <span class="text-[10px] text-indigo-500 font-bold uppercase tracking-widest">{{ Auth::user()->role }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-red-500 hover:text-red-700 transition px-3 py-1.5 rounded-lg hover:bg-red-50 border border-transparent hover:border-red-100">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition mr-4">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm font-bold hover:bg-indigo-700 transition">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        @if (isset($header))
            <header class="bg-white border-b border-slate-100 shadow-sm">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endif

        <main class="flex-grow">
            <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 text-sm font-medium shadow-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-200 text-red-800 text-sm font-medium shadow-sm flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"/></svg>
                        {{ session('error') }}
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
    </div>
</body>
</html>
