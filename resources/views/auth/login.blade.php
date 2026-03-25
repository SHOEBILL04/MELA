@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[80vh]">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Welcome Back</h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">Sign in to your MELA account</p>
        </div>

        @if($errors->any())
            <div class="bg-red-50 border border-red-100 text-red-600 px-4 py-3 rounded-lg mb-6 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password">Password</label>
                <input type="password" name="password" id="password" required 
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div class="flex items-center justify-between pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="remember" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                    <span class="text-sm font-medium text-slate-600">Remember me</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm shadow-indigo-600/20 mt-2">
                Sign In
            </button>
        </form>
        
        <p class="mt-8 text-center text-sm font-medium text-slate-500">
            Don't have an account? <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors hover:underline">Create one</a>
        </p>
    </div>
</div>
@endsection
