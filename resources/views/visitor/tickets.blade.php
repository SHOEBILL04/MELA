<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Tickets - Visitor Dashboard</title>
    <!-- Using Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-indigo-600 p-4 text-white shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-xl font-bold">MELA Visitor Portal</h1>
        <div class="space-x-4">
            <a href="{{ route('visitor.fairs') }}" class="hover:underline">Browse Fairs</a>
            <a href="{{ route('visitor.events') }}" class="hover:underline">Browse Events</a>
            <a href="{{ route('visitor.tickets') }}" class="hover:underline">My Tickets</a>
            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="hover:underline text-red-300">Logout</a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
        </div>
    </div>
</nav>

<div class="container mx-auto py-8 px-4">
    <h2 class="text-2xl font-bold mb-6 text-gray-800">My Ticket History</h2>

    @if(count($tickets) > 0)
        <!-- Cumulative Spend Header -->
        <div class="mb-6 p-4 bg-indigo-50 border-l-4 border-indigo-600 rounded shadow-sm text-lg text-indigo-900 font-semibold">
            Total Spent on All Tickets: ${{ number_format($tickets->first()->cumulative_spend ?? 0, 2) }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tickets as $ticket)
            <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden hover:shadow-xl transition-all">
                <div class="p-4 {{ $ticket->ticket_type == 'Fair' ? 'bg-indigo-600' : 'bg-pink-600' }} text-white flex justify-between items-center">
                    <h3 class="text-lg font-bold">{{ $ticket->ticket_type }} Ticket</h3>
                    <span class="text-sm border border-white px-2 py-1 rounded">#{{ $ticket->reference_id }}</span>
                </div>
                
                <div class="p-6">
                    <p class="text-gray-800 font-semibold text-lg mb-2">{{ $ticket->target_name }}</p>
                    <p class="text-gray-600 text-sm mb-1">📅 Purchase Date: {{ \Carbon\Carbon::parse($ticket->purchase_date)->format('F d, Y h:i A') }}</p>
                    <p class="text-gray-800 font-bold mb-4">💰 Price: ${{ number_format($ticket->ticket_price, 2) }}</p>
                    
                    <div class="mt-4 border-t pt-4 flex flex-col items-center">
                        <p class="text-xs text-gray-500 mb-2 uppercase tracking-wide">Scan at Entry</p>
                        <!-- Container for dynamic QR Code generation -->
                        <div id="qrcode-{{ $ticket->reference_id }}-{{ $ticket->ticket_type }}" class="p-2 border rounded bg-white"></div>
                        <p class="text-xs font-mono text-gray-400 mt-2">{{ $ticket->qr_code }}</p>
                    </div>
                </div>
            </div>

            <!-- Generate QR Code using JS -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    new QRCode(document.getElementById("qrcode-{{ $ticket->reference_id }}-{{ $ticket->ticket_type }}"), {
                        text: "{{ $ticket->qr_code }}",
                        width: 100,
                        height: 100,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });
                });
            </script>
        @empty
            <div class="col-span-full shadow-sm bg-white p-8 rounded text-center">
                <p class="text-gray-500 text-lg">You haven't bought any tickets yet.</p>
                <a href="{{ route('visitor.fairs') }}" class="mt-4 inline-block text-indigo-600 font-semibold hover:underline">Go buy a Fair ticket!</a>
            </div>
        @endforelse
    </div>
</div>

</body>
</html>
