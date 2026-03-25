<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MELA - Fair Management System</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white border-b border-slate-200 shadow-sm sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="/" class="text-2xl font-black text-indigo-600 tracking-tight flex items-center gap-2">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                MELA
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            @auth
                                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                    Dashboard
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.fairs.index') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('admin.fairs.*') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        Manage Fairs
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'vendor')
                                    <a href="{{ route('vendor.fairs') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('vendor.fairs') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        Browse Fairs
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'visitor')
                                    <a href="{{ route('visitor.fairs') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visitor.fairs') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        Browse Fairs
                                    </a>
                                    <a href="{{ route('visitor.events') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visitor.events') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out ml-8">
                                        My Events
                                    </a>
                                @endif
                                @if(auth()->user()->role === 'employee')
                                    <a href="{{ route('employee.positions') }}" class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('employee.positions') ? 'border-indigo-500 text-slate-900' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }} text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
                                        Job Board
                                    </a>
                                @endif
                            @endauth
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <div class="relative flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-sm font-medium text-slate-700">{{ Auth::user()->name }}</span>
                                        <span class="text-xs text-slate-500 font-semibold uppercase tracking-wider">{{ Auth::user()->role }}</span>
                                    </div>
                                </div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-800 transition px-3 py-2 rounded-md hover:bg-red-50">Log Out</button>
                                </form>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 hover:text-slate-900 transition mr-4">Log in</a>
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow-[0_2px_4px_rgba(0,0,0,0.02)] border-b border-slate-100">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">
                        {{ $header }}
                    </h1>
                </div>
            </header>
        @endif

        <!-- Main Content -->
        <main class="flex-grow w-full">
            <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
                @if(session('success'))
                    <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
                      <div class="flex">
                        <div class="flex-shrink-0">
                          <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                          </svg>
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                      </div>
                    </div>
                @endif
                @if(session('error'))
                    <div class="mb-6 rounded-md bg-red-50 p-4 border border-red-200">
                      <div class="flex">
                        <div class="flex-shrink-0">
                          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                          </svg>
                        </div>
                        <div class="ml-3">
                          <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                      </div>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-slate-200 mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-between sm:flex-row gap-4">
                <p class="text-sm text-slate-500 font-medium tracking-wide">
                    &copy; {{ date('Y') }} MELA Fair System by Member 1
                </p>
                <div class="flex gap-4 text-slate-400">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12c0 4.42 2.865 8.166 6.839 9.489.5.092.682-.217.682-.482 0-.237-.008-.866-.013-1.7-2.782.603-3.369-1.34-3.369-1.34-.454-1.156-1.11-1.462-1.11-1.462-.908-.62.069-.608.069-.608 1.003.07 1.531 1.03 1.531 1.03.892 1.529 2.341 1.087 2.91.831.092-.646.35-1.086.636-1.336-2.22-.253-4.555-1.11-4.555-4.943 0-1.091.39-1.984 1.029-2.683-.103-.253-.446-1.27.098-2.647 0 0 .84-.269 2.75 1.025A9.578 9.578 0 0112 6.836c.85.004 1.705.114 2.504.336 1.909-1.294 2.747-1.025 2.747-1.025.546 1.377.203 2.394.1 2.647.64.699 1.028 1.592 1.028 2.683 0 3.842-2.339 4.687-4.566 4.935.359.309.678.919.678 1.852 0 1.336-.012 2.415-.012 2.743 0 .267.18.578.688.48C19.138 20.161 22 16.418 22 12c0-5.523-4.477-10-10-10z"/></svg>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
