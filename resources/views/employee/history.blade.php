<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application History - Employee Dashboard</title>
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
    <h2 class="text-2xl font-bold mb-6 text-gray-800">My Application History</h2>

    <form method="GET" action="{{ route('employee.history') }}" class="mb-6 flex gap-4 items-end">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2">Filter by Status:</label>
            <select name="status" class="border rounded px-4 py-2 hover:border-indigo-500 text-gray-700 focus:outline-none">
                <option value="all" @selected($statusFilter == 'all')>All</option>
                <option value="pending" @selected($statusFilter == 'pending')>Pending</option>
                <option value="approved" @selected($statusFilter == 'approved')>Approved</option>
                <option value="rejected" @selected($statusFilter == 'rejected')>Rejected</option>
            </select>
        </div>
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
            Filter
        </button>
    </form>

    <div class="bg-white shadow relative sm:rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 border-collapse">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 border-b">ID</th>
                    <th scope="col" class="px-6 py-3 border-b">Position Title</th>
                    <th scope="col" class="px-6 py-3 border-b">Category</th>
                    <th scope="col" class="px-6 py-3 border-b">Applied At</th>
                    <th scope="col" class="px-6 py-3 border-b">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($applications as $app)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">{{ $app->application_id }}</td>
                        <td class="px-6 py-4">{{ $app->position_title }}</td>
                        <td class="px-6 py-4">{{ $app->category ?? 'General' }}</td>
                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($app->applied_at)->format('d M Y, h:i A') }}</td>
                        <td class="px-6 py-4">
                            @if($app->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2.5 py-0.5 rounded">Pending</span>
                            @elseif($app->status == 'approved')
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">Approved</span>
                            @else
                                <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">Rejected</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">You have no application history matching the criteria.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
