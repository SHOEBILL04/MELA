@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-[80vh] py-8">
    <div class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 w-full max-w-md">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900 tracking-tight">Join MELA</h2>
            <p class="text-sm text-slate-500 mt-2 font-medium">Create your account to get started</p>
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

        <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="name">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required 
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="email">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}" required 
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password">Password</label>
                    <input type="password" name="password" id="password" required 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required 
                        class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="role">Register As</label>
                <select name="role" id="role" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm text-slate-700 appearance-none">
                    <option value="visitor" @selected(old('role') == 'visitor')>Visitor</option>
                    <option value="vendor" @selected(old('role') == 'vendor')>Vendor</option>
                    <option value="employee" @selected(old('role') == 'employee')>Employee</option>
                    <option value="admin" @selected(old('role') == 'admin')>Admin (Testing only)</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1.5" for="phone">Phone (Optional)</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" 
                    class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all text-sm">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors shadow-sm shadow-indigo-600/20 mt-4">
                Create Account
            </button>
        </form>
        
        <p class="mt-8 text-center text-sm font-medium text-slate-500">
            Already have an account? <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-800 transition-colors hover:underline">Log in</a>
        </p>
    </div>
</div>
@endsection
