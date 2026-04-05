@extends('layouts.app', ['header' => 'Quick Apply'])

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-100 bg-slate-50/70">
            <a href="{{ route('employee.positions') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-indigo-600 transition-colors mb-4">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Back to Job Board
            </a>

            <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-slate-900">{{ $position->title }}</h2>
                    <p class="text-sm font-medium text-slate-500 mt-1">
                        Stall {{ $position->stall_number }} @if($position->category) ({{ $position->category }}) @endif at {{ $position->fair_name }}
                    </p>
                </div>
                <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-indigo-50 text-indigo-700">
                    {{ ucfirst($position->fair_status) }} fair
                </div>
            </div>
        </div>

        <div class="p-8">
            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    <p class="font-semibold mb-2">Please fix the following:</p>
                    <ul class="list-disc pl-5 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('employee.apply', $position->position_id) }}" class="space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="applicant_name" class="block text-sm font-semibold text-slate-700 mb-2">Full Name</label>
                        <input
                            id="applicant_name"
                            name="applicant_name"
                            type="text"
                            value="{{ old('applicant_name', auth()->user()->name) }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="applicant_age" class="block text-sm font-semibold text-slate-700 mb-2">Age</label>
                        <input
                            id="applicant_age"
                            name="applicant_age"
                            type="number"
                            min="18"
                            max="100"
                            value="{{ old('applicant_age') }}"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                    </div>

                    <div>
                        <label for="applicant_gender" class="block text-sm font-semibold text-slate-700 mb-2">Gender</label>
                        <select
                            id="applicant_gender"
                            name="applicant_gender"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="">Select gender</option>
                            <option value="Male" @selected(old('applicant_gender') === 'Male')>Male</option>
                            <option value="Female" @selected(old('applicant_gender') === 'Female')>Female</option>
                            <option value="Other" @selected(old('applicant_gender') === 'Other')>Other</option>
                        </select>
                    </div>

                    <div>
                        <label for="education_status" class="block text-sm font-semibold text-slate-700 mb-2">Education Status</label>
                        <select
                            id="education_status"
                            name="education_status"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                            required
                        >
                            <option value="">Select education status</option>
                            <option value="School Student" @selected(old('education_status') === 'School Student')>School Student</option>
                            <option value="College Student" @selected(old('education_status') === 'College Student')>College Student</option>
                            <option value="Undergraduate" @selected(old('education_status') === 'Undergraduate')>Undergraduate</option>
                            <option value="Graduate" @selected(old('education_status') === 'Graduate')>Graduate</option>
                            <option value="Postgraduate" @selected(old('education_status') === 'Postgraduate')>Postgraduate</option>
                            <option value="Not Currently Studying" @selected(old('education_status') === 'Not Currently Studying')>Not Currently Studying</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="home_location" class="block text-sm font-semibold text-slate-700 mb-2">Home Location</label>
                    <input
                        id="home_location"
                        name="home_location"
                        type="text"
                        value="{{ old('home_location') }}"
                        placeholder="Enter your home area or city"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 text-slate-900 focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                </div>

                <div class="rounded-2xl bg-slate-50 border border-slate-200 p-5 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="text-sm font-semibold text-slate-800">Application summary</p>
                        <p class="text-sm text-slate-500 mt-1">You are applying for {{ $position->title }} with a monthly salary of ${{ number_format($position->salary, 2) }}.</p>
                    </div>
                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-6 py-3 text-sm font-bold text-white transition-colors hover:bg-indigo-600">
                        Submit Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
