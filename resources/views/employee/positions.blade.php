<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Positions - Employee Dashboard</title>
    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-indigo-600 p-4 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">MELA Employee Portal</h1>
        <div class="space-x-4">
            <a href="{{ route('employee.positions') }}" class="hover:underline">Browse Positions</a>
            <a href="{{ route('employee.history') }}" class="hover:underline">My Applications</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:underline text-red-300">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
</nav>

<div class="container mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Available Jobs via `vw_AvailablePositions`</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form method="GET" action="{{ route('employee.positions') }}" class="mb-6 flex gap-4 items-end">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Filter by Fair Status:</label>
            <select name="fair_status" class="border rounded px-4 py-2 hover:border-indigo-500 text-gray-700 focus:outline-none">
                <option value="all" @selected($statusFilter == 'all')>All</option>
                <option value="upcoming" @selected($statusFilter == 'upcoming')>Upcoming</option>
                <option value="active" @selected($statusFilter == 'active')>Active</option>
                <option value="completed" @selected($statusFilter == 'completed')>Completed</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            Filter
        </button>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($positions as $pos)
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-indigo-500 hover:shadow-xl transition-shadow">
                <h3 class="text-xl font-bold text-gray-800">{{ $pos->title }}</h3>
                <p class="text-gray-600 mt-2">📍 Fair: <span class="font-semibold">{{ $pos->fair_name }}</span></p>
                <p class="text-gray-600">🏛️ Stall: {{ $pos->stall_number }} ({{ $pos->category }})</p>
                <p class="text-gray-600">💰 Salary: ${{ number_format($pos->salary, 2) }}</p>
                <p class="text-sm mt-3 inline-block px-2 py-1 rounded bg-blue-100 text-blue-800 font-semibold">{{ ucfirst($pos->fair_status) }}</p>
                
                <form method="POST" action="{{ route('employee.apply', $pos->position_id) }}" class="mt-4">
                    @csrf
                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded transition-colors">
                        Apply Now
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full shadow-sm bg-gray-50 p-8 rounded text-center">
                <p class="text-gray-500 text-lg">No open positions found matching the criteria.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
