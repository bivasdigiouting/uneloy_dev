@extends('vendor.layout')

@section('title', $title ?? 'Inventory')

@section('content')

<!-- Alpine.js is assumed to be loaded by layout, if not, adding it -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="vendorInventory()" x-cloak class="h-full flex flex-col gap-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Inventory</h1>
            <p class="text-slate-500 font-medium">Welcome back, manager. Here's what's happening today.</p>
        </div>

        <div class="flex items-center gap-4">
            <!-- Search Bar -->
            <div class="relative group hidden md:block">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors w-5 h-5"></i>
                <input type="text" placeholder="Search analytics, staff, orders..." class="w-64 pl-12 pr-4 py-3 bg-white border border-slate-100 rounded-full text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
            </div>

            <!-- Notifications -->
            <button class="relative w-12 h-12 bg-white rounded-full flex items-center justify-center border border-slate-100 shadow-sm text-slate-500 hover:text-indigo-600 transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-3 right-3 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
            </button>

            <!-- Create Invoice -->
            <a href="{{ url('vendor/billing') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-sm font-bold shadow-lg shadow-slate-900/20 active:scale-95 transition-all flex items-center gap-2">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Create Invoice <i data-lucide="chevron-right" class="w-4 h-4 ml-1"></i>
            </a>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Items -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="box" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-400">Total Items</span>
            </div>
            <h3 class="text-4xl font-black text-slate-800">{{ number_format($totalItems) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-full text-xs font-bold flex items-center gap-1">
                    <i data-lucide="arrow-up" class="w-3 h-3"></i> {{ $newToday }} New Today
                </span>
            </div>
        </div>

        <!-- Sold Stock -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="arrow-down" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-400">Sold Stock</span>
            </div>
            <h3 class="text-4xl font-black text-slate-800">{{ number_format($soldStock) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold flex items-center gap-1">
                    Average {{ $soldAvg }}/day
                </span>
            </div>
        </div>

        <!-- Inbound -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i data-lucide="truck" class="w-6 h-6"></i>
                </div>
                <span class="text-xs font-black uppercase tracking-widest text-slate-400">Inbound</span>
            </div>
            <h3 class="text-4xl font-black text-slate-800">₹{{ number_format($inbound) }}</h3>
            <div class="mt-4 flex items-center gap-2">
                <span class="px-3 py-1 bg-amber-50 text-amber-600 rounded-full text-xs font-bold flex items-center gap-1">
                    {{ $inboundDeliveries }} Deliveries Pending
                </span>
            </div>
        </div>

    </div>

    <!-- Secondary Actions Header -->
    <div class="flex flex-col sm:flex-row items-center justify-between mt-4 gap-4">
        <h2 class="text-xl font-black text-slate-800">Stock Valuation & Movement</h2>
        <div class="flex items-center gap-3">
            <button class="w-10 h-10 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 shadow-sm transition-all text-sm group">
                <i data-lucide="refresh-cw" class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500"></i>
            </button>
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl flex items-center gap-2 font-bold text-slate-600 hover:bg-slate-50 shadow-sm text-sm">
                <i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i> This Month
            </button>
            <button class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl flex items-center gap-2 font-bold shadow-md shadow-indigo-600/20 active:scale-95 text-sm transition-all">
                <i data-lucide="download" class="w-4 h-4"></i> Export Report
            </button>
        </div>
    </div>

    <!-- Bottom Grids -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-2 pb-10">
        
        <!-- Critical Stock Alerts -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center"><i data-lucide="alert-triangle" class="w-4 h-4"></i></span>
                Critical Stock Alerts
            </h3>
            
            <div class="space-y-4">
                @forelse($criticalStockAlerts as $item)
                    <div class="flex items-center justify-between p-4 bg-slate-50/50 rounded-2xl border border-slate-100 hover:border-slate-200 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center font-black">
                                {{ $item->stock }}
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800 text-sm">{{ $item->name }}</h4>
                                <p class="text-xs text-slate-400 font-medium">SKU: {{ $item->id ?? 'N/A' }} | Cat: {{ $item->category }}</p>
                            </div>
                        </div>
                        <a href="{{ url('vendor/products') }}" class="text-xs font-black text-indigo-600 hover:text-indigo-700 tracking-wider uppercase">Reorder Now</a>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i data-lucide="check-circle" class="w-8 h-8"></i>
                        </div>
                        <p class="font-bold text-slate-600">Stock Levels Healthy</p>
                        <p class="text-xs text-slate-400">No critical alerts at this moment.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Inventory Movement Log -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100">
            <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                <span class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center"><i data-lucide="activity" class="w-4 h-4"></i></span>
                Inventory Movement Log
            </h3>
            
            <!-- Timeline -->
            <div class="relative pl-4 space-y-8 before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-200 before:to-transparent">
                
                @forelse($movements as $log)
                    <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                        <div class="flex items-center justify-center w-3 h-3 rounded-full border-4 border-white bg-{{ $log['color'] }}-500 text-slate-500 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 z-10"></div>
                        <div class="w-[calc(100%-2rem)] md:w-[calc(50%-1.5rem)] bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex items-center justify-between mb-1">
                                <h4 class="font-bold text-slate-800 text-sm">{{ $log['title'] }}</h4>
                            </div>
                            <p class="text-xs text-slate-500 mb-2">{{ $log['description'] }}</p>
                            <time class="text-[10px] font-black tracking-wider uppercase text-slate-400">{{ $log['time'] }}</time>
                        </div>
                    </div>
                @empty
                    <!-- Mocked Log Data exactly matching the template if empty -->
                    <div class="relative flex items-center group is-active">
                        <div class="absolute left-[-5px] top-6 w-3 h-3 rounded-full border-2 border-white bg-emerald-500 shadow shrink-0 z-10"></div>
                        <div class="w-full bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-sm ml-6 relative">
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Restock Completed</h4>
                            <p class="text-xs text-slate-500 mb-2">20x Artisanal Sourdough added by Priya Verma</p>
                            <time class="text-[10px] font-black tracking-wider uppercase text-slate-400">10:45 AM TODAY</time>
                        </div>
                    </div>

                    <div class="relative flex items-center group is-active mt-6">
                        <div class="absolute left-[-5px] top-6 w-3 h-3 rounded-full border-2 border-white bg-rose-500 shadow shrink-0 z-10"></div>
                        <div class="w-full bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-sm ml-6 relative">
                            <h4 class="font-bold text-slate-800 text-sm mb-1">Stock Adjustment (Damaged)</h4>
                            <p class="text-xs text-slate-500 mb-2">3x Organic Honey removed from inventory</p>
                            <time class="text-[10px] font-black tracking-wider uppercase text-slate-400">YESTERDAY</time>
                        </div>
                    </div>
                @endforelse

            </div>
        </div>

    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('vendorInventory', () => ({
            init() {
                setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
            }
        }))
    });
</script>
<style>
    [x-cloak] { display: none !important; }
    /* Hide timeline connector pseudo-element when not on md */
    @media (max-width: 768px) {
        .before\:absolute::before { content: none; }
    }
</style>
@endsection
