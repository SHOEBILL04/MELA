@extends('layouts.app', ['header' => 'Admin Analytics Dashboard'])

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-end">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">System Analytics Dashboard</h2>
            <p class="text-sm font-medium text-slate-500 mt-1">Real-time overview of global performance, revenue, and activity.</p>
        </div>
        <a href="{{ route('admin.fairs.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold py-2.5 px-5 rounded-xl transition-colors shadow-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
            Deploy New Fair
        </a>
    </div>

    <!-- Global Summary KPIs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Total Revenue -->
        <div class="bg-indigo-600 rounded-2xl p-5 text-white shadow-md relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider mb-1">Total Revenue</p>
                <h3 class="text-2xl font-black">${{ number_format($globalStats['total_overall_revenue'], 2) }}</h3>
            </div>
            <svg class="absolute -bottom-4 -right-2 w-24 h-24 text-indigo-500 opacity-50" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"/><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"/></svg>
        </div>
        
        <!-- Total Stalls Sold -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Stalls Sold</p>
            <h3 class="text-2xl font-black text-slate-800">{{ number_format($globalStats['total_stalls_sold']) }}</h3>
        </div>

        <!-- Stall Revenue -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Stall Revenue</p>
            <h3 class="text-2xl font-black text-slate-800">${{ number_format($globalStats['total_stall_revenue'], 2) }}</h3>
        </div>

        <!-- Entry Tickets Revenue -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Fair Entry Revenue</p>
            <h3 class="text-2xl font-black text-slate-800">${{ number_format($globalStats['total_entry_revenue'], 2) }}</h3>
        </div>

        <!-- Event Tickets Revenue -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider mb-1">Event Revenue</p>
            <h3 class="text-2xl font-black text-slate-800">${{ number_format($globalStats['total_event_revenue'], 2) }}</h3>
        </div>
    </div>

    <!-- Analytics Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Visitor Trend Graph -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-slate-800 mb-4">Daily Visitor Trend</h3>
            <div class="relative h-72 w-full">
                <canvas id="visitorTrendChart"></canvas>
            </div>
        </div>

        <!-- Top Hit Events -->
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm flex flex-col">
            <div class="p-6 border-b border-slate-100">
                <h3 class="text-lg font-bold text-slate-800">Most Hit Events</h3>
                <p class="text-xs text-slate-500 mt-1">Ranked by tickets sold</p>
            </div>
            <div class="flex-1 overflow-y-auto p-0">
                @forelse($topEvents as $index => $event)
                <div class="flex items-center gap-4 p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors">
                    <div class="text-xl font-black {{ $index == 0 ? 'text-amber-500' : ($index == 1 ? 'text-slate-400' : ($index == 2 ? 'text-amber-700' : 'text-slate-200')) }} w-6 text-center">
                        #{{ $index + 1 }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-sm font-bold text-slate-800">{{ $event->name }}</h4>
                        <p class="text-xs font-medium text-slate-500">{{ number_format($event->tickets_sold) }} tickets sold</p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-black text-emerald-600 block">${{ number_format($event->revenue, 2) }}</span>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-500 text-sm">
                    No events or tickets sold yet.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Fairs Directory -->
    <div>
        <h3 class="text-xl font-black text-slate-800 mb-4">All Fairs Directory</h3>
        <div class="bg-white shadow-sm border border-slate-200 rounded-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest">Fair</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Stalls Booked</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-center">Tickets Sold</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Total Revenue</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-widest text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($fairs as $fair)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-bold text-slate-900">{{ $fair->fair_name }}</span>
                                        <span class="text-xs font-medium text-slate-500">ID: #{{ $fair->fair_id }} &middot; Rank #{{ $fair->revenue_rank }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $fair->fair_status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-slate-700">{{ $fair->stalls_sold }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-sm font-bold text-slate-700">{{ $fair->total_visitors }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-sm font-black text-emerald-600">${{ number_format($fair->total_fair_revenue, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.fairs.show', $fair->fair_id) }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">
                                            View Details
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                        <form action="{{ route('admin.fairs.destroy', $fair->fair_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to completely delete this fair? This will remove all associated stalls, tickets, and revenue records FOREVER.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-colors group">
                                                <svg class="w-3.5 h-3.5 group-hover:animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="w-12 h-12 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                    </div>
                                    <p class="text-slate-500 font-medium text-sm">No fairs have been created yet.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dailyData = @json($dailyVisitors);
    
    // Process Data
    const labels = dailyData.map(d => {
        const date = new Date(d.day_date);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    });
    const dataPoints = dailyData.map(d => d.total_visitors);

    const ctx = document.getElementById('visitorTrendChart').getContext('2d');
    
    // Create Chart
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total Daily Visitors',
                data: dataPoints,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                borderWidth: 3,
                pointBackgroundColor: '#ffffff',
                pointBorderColor: '#4f46e5',
                pointBorderWidth: 2,
                pointRadius: 4,
                pointHoverRadius: 6,
                fill: true,
                tension: 0.4 // Makes line smooth
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    titleFont: { size: 13, family: "'Inter', sans-serif" },
                    bodyFont: { size: 14, family: "'Inter', sans-serif", weight: 'bold' },
                    padding: 12,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.raw.toLocaleString() + ' Visitors';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 11 },
                        color: '#64748b'
                    }
                },
                y: {
                    beginAtZero: true,
                    grid: {
                        color: '#f1f5f9',
                        drawBorder: false,
                        borderDash: [5, 5]
                    },
                    ticks: {
                        font: { family: "'Inter', sans-serif", size: 11 },
                        color: '#64748b',
                        stepSize: 10,
                        callback: function(value) {
                            if (value >= 1000) return (value/1000) + 'k';
                            return value;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
        }
    });
});
</script>
@endsection
