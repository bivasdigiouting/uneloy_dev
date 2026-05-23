@extends('vendor.layout')

@section('title', $title ?? 'Ads & Promotions')

<!-- Include Alpine.js internally since it relies on it -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<!-- Include Chart.js for the visual graph mimicking React -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

@section('content')
<div x-data="vendorAds()" x-cloak class="h-full flex flex-col gap-6 w-full max-w-[1600px] mx-auto">

    <!-- Header Section matching template styling -->
    <div class="flex flex-col md:flex-row md:items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Ads & Promotions</h1>
            <p class="text-slate-500 font-medium tracking-wide">Welcome back, manager. Here's what's happening today.</p>
        </div>

        <div class="flex flex-wrap items-center gap-4">
            <!-- Search Bar -->
            <div class="relative hidden lg:block group">
                <i data-lucide="search" class="absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-500 transition-colors w-5 h-5"></i>
                <input type="text" placeholder="Search analytics, staff, orders..." class="w-72 pl-14 pr-4 py-3 bg-white border border-slate-100 rounded-[2rem] text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm shadow-slate-200/50">
            </div>

            <button class="w-12 h-12 bg-white rounded-full border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 shadow-sm relative transition-colors">
                <i data-lucide="bell" class="w-5 h-5"></i>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-rose-500 border-2 border-white rounded-full"></span>
            </button>

            <!-- Create Invoice matching template button -->
            <a href="{{ url('vendor/billing') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-sm font-bold shadow-lg shadow-slate-900/20 transition-all flex items-center gap-2 group active:scale-95">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Create Invoice
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Ad Platform Tab Banner -->
    <div class="bg-indigo-50/30 rounded-[2rem] p-2 flex flex-col lg:flex-row lg:items-center justify-between gap-6 border border-slate-100/50 mt-4 h-auto lg:h-24">
        <div class="flex items-center gap-6 px-6">
            <div class="w-14 h-14 bg-white/60 backdrop-blur-md rounded-2xl flex items-center justify-center text-indigo-500 shadow-sm">
                <i data-lucide="megaphone" class="w-7 h-7"></i>
            </div>
            <div>
                <h2 class="text-xl font-black text-slate-800">Ad Platform</h2>
                <p class="text-slate-500 text-sm font-semibold tracking-wide mt-0.5">Promote your business to the entire E-Card user base.</p>
            </div>
        </div>

        <!-- Tabs -->
        <div class="bg-white/50 backdrop-blur-md p-1.5 rounded-[1.5rem] flex items-center w-max shadow-sm border border-slate-100 mx-4 lg:mx-2 mb-2 lg:mb-0">
            <button @click="activeTab = 'dashboard'" class="px-8 py-3 rounded-xl text-sm font-black tracking-widest uppercase transition-all" :class="activeTab === 'dashboard' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-500 hover:bg-slate-50'">Dashboard</button>
            <button @click="activeTab = 'packages'" class="px-8 py-3 rounded-xl text-sm font-black tracking-widest uppercase transition-all" :class="activeTab === 'packages' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-600/30' : 'text-slate-500 hover:bg-slate-50'">Ad Packages</button>
            <button @click="showLaunchModal = true" class="px-8 py-3 bg-slate-900 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-md hover:bg-slate-800 transition-all flex items-center gap-2 active:scale-95 ml-2">
                <i data-lucide="plus" class="w-4 h-4"></i> Launch New Ad
            </button>
        </div>
    </div>

    <!-- DASHBOARD VIEW -->
    <div x-show="activeTab === 'dashboard'" x-transition.opacity.duration.300ms class="flex flex-col gap-6 mt-2">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
            
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                        <i data-lucide="eye" class="w-5 h-5"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-black">+22%</span>
                </div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Active Impressions</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($totalImpressions / 1000, 1) }}K</h3>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                        <i data-lucide="mouse-pointer-click" class="w-5 h-5"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-black">+14%</span>
                </div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Total Clicks</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($totalClicks) }}</h3>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-12 h-12 bg-orange-50 text-orange-500 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                        <i data-lucide="trending-up" class="w-5 h-5"></i>
                    </div>
                    <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-black">-2.1%</span>
                </div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Avg. CTR</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">{{ number_format($avgCtr, 2) }}%</h3>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 relative overflow-hidden group">
                <div class="flex justify-between items-center mb-8">
                    <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                        <i data-lucide="wallet" class="w-5 h-5"></i>
                    </div>
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-black">{{ $activeCampaigns }} Campaigns</span>
                </div>
                <p class="text-xs font-black uppercase tracking-widest text-slate-400 mb-2">Ad Wallet</p>
                <h3 class="text-4xl font-black text-slate-800 tracking-tighter">₹{{ number_format($adWallet) }}</h3>
            </div>

        </div>

        <!-- Content Row -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            
            <!-- Dynamic Graph -->
            <div class="xl:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-xl font-black text-slate-800">Reach & Engagement</h2>
                        <p class="text-xs font-black uppercase tracking-widest text-slate-400 mt-1">7 Day Insight</p>
                    </div>
                    <button class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors">
                        <i data-lucide="bar-chart-2" class="w-5 h-5"></i>
                    </button>
                </div>
                <div class="relative w-full h-[300px] flex-1">
                    <canvas id="reachChart"></canvas>
                </div>
            </div>

            <!-- Running Ads Tracking list -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-xl font-black text-slate-800">Running Ads</h2>
                    <span class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-[10px] font-black tracking-widest uppercase">Live</span>
                </div>

                <div class="flex-1 overflow-y-auto space-y-4 custom-scrollbar pr-2 h-[300px]">
                    @forelse($runningAds as $ad)
                        <div class="flex items-start gap-4 p-4 rounded-[1.5rem] bg-slate-50 border border-slate-100/50 hover:bg-white hover:border-indigo-100 transition-all group">
                            <div class="w-14 h-14 bg-indigo-100 rounded-xl overflow-hidden shrink-0 shadow-sm relative">
                                <!-- using icon if banner path isn't specifically stored robustly yet to prevent broken imgs -->
                                <div class="absolute inset-0 flex items-center justify-center bg-slate-800 text-white">
                                    <i data-lucide="image" class="w-6 h-6 opacity-30"></i>
                                </div>
                            </div>
                            <div class="flex-1 pt-1 truncate tracking-tight">
                                <h4 class="font-bold text-slate-800 text-sm truncate" title="{{ $ad->campaign_name }}">{{ $ad->campaign_name }}</h4>
                                <div class="flex items-center gap-2 mt-2">
                                    <div class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest {{ $ad->request_status === 'Active' ? 'text-emerald-500' : 'text-slate-400' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $ad->request_status === 'Active' ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                        {{ $ad->request_status }}
                                    </div>
                                    <span class="text-slate-200">|</span>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-slate-400">Reach {{ number_format(rand(1000, 15000)/1000, 1) }}K</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="h-full flex flex-col items-center justify-center text-slate-400">
                            <i data-lucide="target" class="w-12 h-12 mb-4 opacity-50"></i>
                            <p class="font-bold text-sm uppercase tracking-widest">No Active Campaigns</p>
                            <p class="text-xs text-center mt-2 max-w-[200px]">Launch a new ad to instantly connect with millions of users.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <!-- PACKAGES VIEW -->
    <div x-show="activeTab === 'packages'" x-transition.opacity.duration.300ms class="flex flex-col gap-6 mt-2 pb-10">
        <div class="text-center max-w-2xl mx-auto mb-6">
            <h2 class="text-3xl font-black text-slate-800 mb-3 tracking-tighter">Choose Your Reach</h2>
            <p class="text-slate-500 font-medium">Select a pricing tier that aligns with your promotional goals. Upgrade or cancel anytime based on performance.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Basic Tier -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col hover:border-indigo-200 transition-colors">
                <h4 class="text-lg font-black text-slate-800">Basic</h4>
                <div class="mt-4 flex items-baseline gap-1">
                    <span class="text-4xl font-black tracking-tighter">₹2,499</span><span class="text-slate-400 font-bold text-sm">/mo</span>
                </div>
                <p class="text-slate-500 text-sm mt-4 pb-6 border-b border-slate-100">Perfect for local stores looking to establish a minor presence.</p>
                <ul class="mt-6 space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Local Mall Feed Visibility
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> 5,000 Guaranteed Impressions
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Standard Analytics
                    </li>
                </ul>
                <button @click="showLaunchModal = true" class="mt-8 w-full py-4 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-2xl font-black transition-colors focus:ring-4 focus:ring-slate-200">Get Started</button>
            </div>

            <!-- Standard Tier -->
            <div class="bg-indigo-600 rounded-[2rem] p-8 shadow-2xl shadow-indigo-600/30 text-white flex flex-col relative scale-[1.02] transform z-10">
                <div class="absolute inset-0 bg-gradient-to-b from-white/10 to-transparent rounded-[2rem] pointer-events-none"></div>
                <div class="absolute -top-4 inset-x-0 flex justify-center">
                    <span class="bg-gradient-to-r from-orange-400 to-amber-500 text-white text-[10px] font-black tracking-widest uppercase px-4 py-1.5 rounded-full shadow-lg shadow-orange-500/30">Most Popular</span>
                </div>

                <h4 class="text-lg font-black text-indigo-50">Standard</h4>
                <div class="mt-4 flex items-baseline gap-1">
                    <span class="text-4xl font-black tracking-tighter">₹5,999</span><span class="text-indigo-200 font-bold text-sm">/mo</span>
                </div>
                <p class="text-indigo-100 text-sm mt-4 pb-6 border-b border-indigo-500/50">Comprehensive reach utilizing push notifications and banners.</p>
                <ul class="mt-6 space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-sm font-bold text-white">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-300"></i> Promoted Top Search Results
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-white">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-300"></i> 15,000 Guaranteed Impressions
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-white">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-300"></i> 2x Push Notification Broadcasts
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-white">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-300"></i> Banner Ad Networking
                    </li>
                </ul>
                <button @click="showLaunchModal = true" class="mt-8 w-full py-4 bg-white text-indigo-600 hover:bg-slate-50 rounded-2xl font-black transition-colors shadow-xl shadow-black/10">Select Package</button>
            </div>

            <!-- Enterprise Tier -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col hover:border-indigo-200 transition-colors">
                <h4 class="text-lg font-black text-slate-800">Enterprise</h4>
                <div class="mt-4 flex items-baseline gap-1">
                    <span class="text-4xl font-black tracking-tighter">₹12,499</span><span class="text-slate-400 font-bold text-sm">/mo</span>
                </div>
                <p class="text-slate-500 text-sm mt-4 pb-6 border-b border-slate-100">Dominant brand awareness campaign toolkit.</p>
                <ul class="mt-6 space-y-4 flex-1">
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> 50,000 Guaranteed Impressions
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Splash Screen Ad Takeovers
                    </li>
                    <li class="flex items-center gap-3 text-sm font-bold text-slate-700">
                        <i data-lucide="check" class="w-5 h-5 text-emerald-500"></i> Dedicated Account Manager
                    </li>
                </ul>
                <button @click="showLaunchModal = true" class="mt-8 w-full py-4 bg-slate-50 hover:bg-slate-100 text-slate-800 rounded-2xl font-black transition-colors focus:ring-4 focus:ring-slate-200">Contact Support</button>
            </div>
        </div>
    </div>

    <!-- CREATE AD MODAL OVERLAY -->
    <div x-show="showLaunchModal" x-transition.opacity.duration.300ms class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 h-screen w-screen overflow-hidden">
        <div @click.outside="showLaunchModal = false" class="bg-white w-full max-w-2xl rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden transform transition-all relative">
            
            <div class="px-8 py-6 border-b border-slate-100 flex items-center justify-between shrink-0">
                <h3 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 flex items-center justify-center rounded-xl"><i data-lucide="rocket" class="w-5 h-5"></i></div>
                    Ad Creation Studio
                </h3>
                <button @click="showLaunchModal = false" class="w-10 h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <div class="p-8 space-y-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50/30">
                <!-- Title Field -->
                <div>
                    <label class="block text-xs font-black tracking-widest uppercase text-slate-500 mb-2 pl-1">Advertisement Title</label>
                    <input type="text" placeholder="e.g. Summer Blowout Sale 50% Off" class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
                </div>

                <!-- Descriptive Field -->
                <div>
                    <label class="block text-xs font-black tracking-widest uppercase text-slate-500 mb-2 pl-1">Ad Description</label>
                    <textarea rows="3" placeholder="Engaging message to convince customers to click..." class="w-full bg-white border border-slate-200 rounded-2xl px-5 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm resize-none"></textarea>
                </div>

                <!-- Destination Link -->
                <div>
                    <label class="block text-xs font-black tracking-widest uppercase text-slate-500 mb-2 pl-1">Primary Destination Link</label>
                    <div class="relative">
                        <i data-lucide="link-2" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-5 h-5"></i>
                        <input type="url" placeholder="https://eservice.mall/vendor/your-link" class="w-full bg-white border border-slate-200 rounded-2xl pl-12 pr-5 py-4 text-sm font-bold focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 outline-none transition-all shadow-sm">
                    </div>
                </div>

                <!-- Banner Image Component mimicking react drag n drop zone -->
                <div>
                     <label class="block text-xs font-black tracking-widest uppercase text-slate-500 mb-2 pl-1">Upload Banner</label>
                     <div class="w-full bg-white border-2 border-dashed border-slate-200 hover:border-indigo-400 hover:bg-indigo-50/50 rounded-3xl p-8 flex flex-col items-center justify-center text-slate-400 transition-all cursor-pointer group">
                        <div class="w-16 h-16 bg-slate-50 group-hover:bg-indigo-100 rounded-2xl flex items-center justify-center mb-4 transition-colors">
                            <i data-lucide="image-plus" class="w-8 h-8 group-hover:text-indigo-500 transition-colors"></i>
                        </div>
                        <p class="font-bold text-slate-600 mb-1">Click or drag image to upload</p>
                        <p class="text-xs">PNG, JPG up to 5MB (1200x628px recommended)</p>
                     </div>
                </div>

            </div>

            <!-- Footer Actions -->
            <div class="px-8 py-5 border-t border-slate-100 bg-white flex items-center justify-end gap-3 shrink-0">
                <button @click="showLaunchModal = false" class="px-6 py-3.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 rounded-xl font-bold transition-all">Cancel</button>
                <button @click="showLaunchModal = false" class="px-6 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-600/30 active:scale-95 transition-all w-40 flex justify-center">Launch Ad</button>
            </div>
            
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('vendorAds', () => ({
            activeTab: 'dashboard',
            showLaunchModal: false,

            init() {
                setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
                
                // Initialize the Graph dynamically hooking into a mock structure matching React's visuals
                this.initChart();
                
                this.$watch('activeTab', () => {
                   if(this.activeTab === 'dashboard') {
                       // Re-draw chart slightly delayed allowing DOM animation explicitly
                       setTimeout(() => this.initChart(), 350);
                   }
                });
            },

            initChart() {
                const ctx = document.getElementById('reachChart');
                if(!ctx) return;
                
                // Destroy previous instance to avoid canvas overlapping errors during Tab switches
                if(window.adsReachChart) window.adsReachChart.destroy();
                
                // Replicate aesthetics from React dashboard spline chart
                window.adsReachChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'Reach',
                            data: [3200, 3800, 3500, 4800, 4200, 5600, parseInt("{{ $totalImpressions }}")], // Map final data array value to live controller feed.
                            borderColor: '#6366f1',
                            borderWidth: 4,
                            tension: 0.4, // Smooth Spline effect
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#6366f1',
                            pointBorderWidth: 3,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            backgroundColor: (context) => {
                                const chart = context.chart;
                                const {ctx, chartArea} = chart;
                                if (!chartArea) return null;
                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                gradient.addColorStop(0, 'rgba(99, 102, 241, 0)');
                                gradient.addColorStop(1, 'rgba(99, 102, 241, 0.2)');
                                return gradient;
                            },
                            fill: true,
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                border: { display: false },
                                grid: {
                                    color: '#f1f5f9',
                                    drawTicks: false,
                                },
                                ticks: {
                                    color: '#94a3b8',
                                    font: { family: 'Inter, sans-serif', size: 11, weight: 'bold' },
                                    padding: 10
                                }
                            },
                            x: {
                                border: { display: false },
                                grid: { display: false },
                                ticks: {
                                    color: '#64748b',
                                    font: { family: 'Inter, sans-serif', size: 11, weight: 'bold' },
                                    padding: 10
                                }
                            }
                        }
                    }
                });
            }
        }))
    });
</script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
