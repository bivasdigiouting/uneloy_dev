@extends('vendor.layout')

@section('title', 'Reports & Analytics')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Header Section -->
<div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
    <div>
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Reports</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Welcome back, manager. Here's what's happening today.</p>
    </div>
    <div class="flex items-center gap-3">
        <form method="GET" action="{{ route('vendor.reports') }}" class="inline-block" id="rangeForm">
            <select name="range" onchange="document.getElementById('rangeForm').submit();" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 card-shadow cursor-pointer">
                <option value="last_30_days" @if(request('range') == 'last_30_days') selected @endif>Last 30 Days</option>
                <option value="last_quarter" @if(request('range') == 'last_quarter') selected @endif>Last Quarter</option>
                <option value="this_year" @if(request('range') == 'this_year') selected @endif>This Year</option>
            </select>
        </form>
        <button onclick="window.print()" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold flex items-center gap-2 transition-colors card-shadow">
            <i data-lucide="download" class="w-4 h-4"></i>
            Generate PDF
        </button>
    </div>
</div>

<!-- Advanced Analytics Charts -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10 mb-10">
    <!-- Revenue vs Profit Growth -->
    <div class="lg:col-span-2 bg-white rounded-[3rem] p-6 sm:p-8 border border-slate-100 card-shadow flex flex-col h-[450px]">
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-xl font-bold text-slate-900">Revenue vs Profit Growth</h2>
                <p class="text-sm text-slate-500 font-medium">Monthly trajectory over the last 6 months</p>
            </div>
            <div class="flex gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-indigo-500"></div>
                    <span class="text-xs font-bold text-slate-600">Revenue</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-3 h-3 rounded-full bg-emerald-500"></div>
                    <span class="text-xs font-bold text-slate-600">Profit</span>
                </div>
            </div>
        </div>
        <div class="flex-1 min-h-0 relative">
            <canvas id="revenueProfitChart"></canvas>
        </div>
    </div>

    <!-- Revenue Contribution -->
    <div class="bg-white rounded-[3rem] p-6 sm:p-8 border border-slate-100 card-shadow flex flex-col h-[450px]">
        <div class="mb-6 text-center">
            <h2 class="text-xl font-bold text-slate-900">Revenue Contribution</h2>
            <p class="text-sm text-slate-500 font-medium">Sales split by top categories</p>
        </div>
        <div class="flex-1 relative flex items-center justify-center min-h-0">
            <div class="relative w-full h-full max-h-[220px]">
                <canvas id="contributionChart"></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-extrabold text-slate-900">{{ number_format($chartData['totalSalesCount']) }}</span>
                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Sales</span>
                </div>
            </div>
        </div>
        
        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-2 gap-4">
            @php $colors = ['bg-indigo-500', 'bg-emerald-500', 'bg-amber-500', 'bg-rose-500']; @endphp
            @foreach($chartData['categories'] as $index => $cat)
                <div class="flex items-center gap-3">
                    <div class="w-2.5 h-2.5 rounded-full {{ $colors[$index % count($colors)] }} shrink-0"></div>
                    <div class="min-w-0">
                        <p class="text-xs font-bold text-slate-700 truncate">{{ $cat }}</p>
                        <p class="text-[10px] font-medium text-slate-500">{{ $chartData['categoryData'][$index] }} Sales</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Downloadable Reports Grid -->
<div>
    <div class="mb-6">
        <h2 class="text-xl font-bold text-slate-900">Downloadable Reports</h2>
        <p class="text-sm text-slate-500 font-medium">Access your comprehensive accounting and operational documents.</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Report Card 1 -->
        <a href="{{ route('vendor.reports.export', ['type' => 'daily_sales_audit']) }}" class="group block bg-white p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 transition-all card-shadow hover:-translate-y-1">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-600 group-hover:text-white transition-colors">
                <i data-lucide="file-text" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Daily Sales Audit</h3>
            <p class="text-xs font-medium text-slate-500">Auto-Generates CSV</p>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-indigo-600 opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Download</span>
                <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
            </div>
        </a>

        <!-- Report Card 2 -->
        <a href="{{ route('vendor.reports.export', ['type' => 'staff_attendance']) }}" class="group block bg-white p-5 rounded-2xl border border-slate-100 hover:border-emerald-100 transition-all card-shadow hover:-translate-y-1">
            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                <i data-lucide="file-spreadsheet" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Staff Attendance</h3>
            <p class="text-xs font-medium text-slate-500">Auto-Generates CSV</p>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-emerald-600 opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Download</span>
                <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
            </div>
        </a>

        <!-- Report Card 3 -->
        <a href="{{ route('vendor.reports.export', ['type' => 'inventory_valuation']) }}" class="group block bg-white p-5 rounded-2xl border border-slate-100 hover:border-amber-100 transition-all card-shadow hover:-translate-y-1">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-amber-600 group-hover:text-white transition-colors">
                <i data-lucide="file-json" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Inventory Valuation</h3>
            <p class="text-xs font-medium text-slate-500">Auto-Generates CSV</p>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-amber-600 opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Download</span>
                <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
            </div>
        </a>

        <!-- Report Card 4 -->
        <a href="{{ route('vendor.reports.export', ['type' => 'tax_liability_summary']) }}" class="group block bg-white p-5 rounded-2xl border border-slate-100 hover:border-rose-100 transition-all card-shadow hover:-translate-y-1">
            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center mb-4 group-hover:bg-rose-600 group-hover:text-white transition-colors">
                <i data-lucide="file-check-2" class="w-6 h-6"></i>
            </div>
            <h3 class="font-bold text-slate-900 mb-1">Tax Liability Summary</h3>
            <p class="text-xs font-medium text-slate-500">Auto-Generates CSV</p>
            <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm font-bold text-rose-600 opacity-0 group-hover:opacity-100 transition-opacity">
                <span>Download</span>
                <i data-lucide="arrow-down-to-line" class="w-4 h-4"></i>
            </div>
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartData = @json($chartData);

    // Common Chart.js options for clean, modern look
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.color = '#94a3b8';
    Chart.defaults.scale.grid.color = 'rgba(241, 245, 249, 0.5)'; // slate-100 semi-transparent

    // 1. Revenue vs Profit Chart (Grouped Bar)
    const ctxBar = document.getElementById('revenueProfitChart').getContext('2d');
    new Chart(ctxBar, {
        type: 'bar',
        data: {
            labels: chartData.months,
            datasets: [
                {
                    label: 'Revenue',
                    data: chartData.revenue,
                    backgroundColor: '#6366f1', // indigo-500
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                },
                {
                    label: 'Profit',
                    data: chartData.profit,
                    backgroundColor: '#10b981', // emerald-500
                    borderRadius: 6,
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false // Using custom legend in HTML
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 },
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) { label += ': '; }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR', maximumSignificantDigits: 3 }).format(context.parsed.y);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    border: { display: false },
                    ticks: {
                        callback: function(value) {
                            return '₹' + (value / 1000) + 'k';
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: { display: false },
                    border: { display: false }
                }
            }
        }
    });

    // 2. Revenue Contribution Chart (Donut)
    const ctxDoughnut = document.getElementById('contributionChart').getContext('2d');
    new Chart(ctxDoughnut, {
        type: 'doughnut',
        data: {
            labels: chartData.categories,
            datasets: [{
                data: chartData.categoryData,
                backgroundColor: [
                    '#6366f1', // indigo-500
                    '#10b981', // emerald-500
                    '#f59e0b', // amber-500
                    '#f43f5e'  // rose-500
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '75%',
            plugins: {
                legend: {
                    display: false // Using custom legend in HTML
                },
                tooltip: {
                    backgroundColor: '#1e293b',
                    padding: 12,
                    titleFont: { size: 13, weight: 'bold' },
                    bodyFont: { size: 12 }
                }
            }
        }
    });
});
</script>
@endsection
