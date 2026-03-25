<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Events - Visitor Dashboard</title>
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
    <h2 class="text-2xl font-bold mb-2 text-gray-800">Events For You</h2>
    <p class="text-gray-600 mb-6">Showing events only for the fairs you have purchased a ticket to.</p>

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
        @forelse($events as $event)
            @php $remaining = $event->max_capacity - $event->tickets_sold; @endphp
            <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-indigo-500 hover:shadow-xl transition-shadow">
                <span class="text-xs bg-indigo-100 text-indigo-800 font-semibold px-2 py-1 rounded">{{ $event->fair_name }}</span>
                <h3 class="text-xl font-bold text-gray-800 mt-2">{{ $event->event_name }}</h3>
                <p class="text-gray-600 mt-2">🕒 {{ \Carbon\Carbon::parse($event->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($event->end_time)->format('h:i A') }}</p>
                <p class="text-gray-600">📅 {{ \Carbon\Carbon::parse($event->event_date)->format('F d, Y') }}</p>
                
                <div class="mt-4 p-3 rounded {{ $remaining > 0 ? 'bg-blue-50' : 'bg-red-50' }}">
                    <p class="text-sm font-semibold {{ $remaining > 0 ? 'text-blue-700' : 'text-red-700' }}">
                        Capacity: {{ $event->tickets_sold }} / {{ $event->max_capacity }}
                    </p>
                </div>
                
                <form method="POST" action="{{ route('visitor.buyEventTicket', ['eventId' => $event->event_id]) }}" class="mt-4">
                    @csrf
                    <button type="submit" 
                            class="w-full font-bold py-2 px-4 rounded transition-colors {{ $remaining > 0 ? 'bg-green-500 hover:bg-green-600 text-white' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                            @disabled($remaining <= 0)>
                        Buy Ticket (${{ number_format($event->ticket_price, 2) }})
                    </button>
                </form>
            </div>
        @empty
            <div class="col-span-full shadow-sm bg-white p-8 rounded text-center">
                <p class="text-gray-500 text-lg">You don't have access to any upcoming events. Buy a fair ticket first!</p>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
