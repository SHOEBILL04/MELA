<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Fairs - Visitor Dashboard</title>
    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-indigo-600 p-4 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">MELA Visitor Portal</h1>
        <div class="space-x-4">
            <a href="{{ route('visitor.fairs') }}" class="hover:underline">Browse Fairs</a>
            <a href="{{ route('visitor.events') }}" class="hover:underline">Browse Events</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:underline text-red-300">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
</nav>

<div class="container mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">Active Fairs</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 shadow">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 shadow text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($fairDays as $day)
            <div class="bg-white rounded-lg shadow-md p-6 border-t-4 border-indigo-500 hover:shadow-xl transition-all">
                <h3 class="text-xl font-bold text-gray-800">{{ $day->name }}</h3>
                <p class="text-gray-600 mt-2">📍 Location: {{ $day->location }}</p>
                <p class="text-gray-600">📅 Date: {{ \Carbon\Carbon::parse($day->day_date)->format('F d, Y') }}</p>
                
                <div class="mt-4 p-3 rounded {{ $day->remaining_slots > 0 ? 'bg-green-50' : 'bg-red-50' }}">
                    <p class="text-sm font-semibold {{ $day->remaining_slots > 0 ? 'text-green-700' : 'text-red-700' }}">
                        Tickets Sold: {{ $day->visitors_count }} / {{ $day->max_visitors }}
                    </p>
                    <p class="text-sm font-bold {{ $day->remaining_slots > 0 ? 'text-green-800' : 'text-red-800' }}">
                        {{ $day->remaining_slots > 0 ? $day->remaining_slots . ' slots remaining' : 'Sold Out' }}
                    </p>
                </div>
                
                <form method="POST" action="{{ route('visitor.buyFairTicket', ['fairId' => $day->fair_id, 'dayId' => $day->day_id]) }}" class="mt-4">
                    @csrf
                    <button type="submit" 
                            class="w-full font-bold py-2 px-4 rounded transition-colors {{ $day->remaining_slots > 0 ? 'bg-indigo-600 hover:bg-indigo-700 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                            @disabled($day->remaining_slots <= 0)>
                        Buy Entry Ticket ($50.00)
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full shadow-sm bg-white p-8 rounded text-center">
                <p class="text-gray-500 text-lg">No active fairs available at the moment.</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
