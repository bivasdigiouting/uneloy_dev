@extends('vendor.layout')

@section('title', $title ?? 'Settlements')

@section('content')
<!-- Include Alpine.js internally since it relies on it -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="vendorSettlements()" x-cloak class="h-full flex flex-col gap-6 w-full max-w-[1600px] mx-auto">

    <!-- Header Section matching template styling -->
    <div class="flex flex-col md:flex-row md:items-start lg:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Settlements</h1>
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
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-emerald-500 border-2 border-white rounded-full"></span>
            </button>

            <!-- Create Invoice matching template button -->
            <a href="{{ url('vendor/billing') }}" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white rounded-full text-sm font-bold shadow-lg shadow-slate-900/20 transition-all flex items-center gap-2 group active:scale-95">
                <i data-lucide="shopping-cart" class="w-4 h-4"></i> Create Invoice
                <i data-lucide="chevron-right" class="w-4 h-4 text-slate-400 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Available for Payout -->
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 shadow-xl shadow-indigo-600/30 text-white relative overflow-hidden group">
            <div class="absolute -right-8 -top-8 w-40 h-40 bg-indigo-500/50 rounded-full blur-2xl group-hover:bg-indigo-400/50 transition-colors"></div>
            
            <p class="text-indigo-200 text-sm font-bold tracking-widest uppercase mb-2">Available for Payout</p>
            <h3 class="text-4xl md:text-5xl font-black mb-8 tracking-tighter">₹{{ number_format($availableForPayout, 2) }}</h3>
            
            <div class="flex items-center justify-between relative z-10 w-full">
                <span class="text-xs font-bold text-indigo-200">
                    Last Settled: ₹12,500
                </span>
                <button class="px-5 py-2.5 bg-white text-indigo-600 rounded-xl text-sm font-black hover:bg-slate-50 transition-colors shadow-lg active:scale-95">
                    Request Payout
                </button>
            </div>
            
            <div class="absolute right-6 top-6 opacity-20 disabled pointer-events-none hidden lg:block">
                <i data-lucide="banknote" class="w-16 h-16"></i>
            </div>
        </div>

        <!-- Total Settled -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 group relative flex flex-col justify-between">
            <div class="flex justify-between items-start mb-6 w-full">
                <div>
                    <p class="text-slate-400 text-xs font-black uppercase tracking-widest mb-1.5">Total Settled</p>
                    <h3 class="text-3xl font-black text-slate-800 tracking-tighter">₹{{ number_format($totalSettled) }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="check-circle-2" class="w-6 h-6"></i>
                </div>
            </div>
            
            <div>
                <div class="flex items-center justify-between text-xs font-black uppercase tracking-widest text-slate-400 mb-2 mt-4">
                    <span>YTD Progress</span>
                    <span class="text-indigo-500">82%</span>
                </div>
                <!-- Progress Bar -->
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-indigo-500 rounded-full" style="width: 82%;"></div>
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 group relative flex flex-col justify-center gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center shadow-sm">
                    <i data-lucide="refresh-cw" class="w-5 h-5"></i>
                </div>
                <div>
                    <h4 class="text-slate-800 text-sm font-black uppercase tracking-wider mb-0.5">Auto-Settlement Active</h4>
                    <p class="text-emerald-500 text-xs font-bold">System Status Healthy</p>
                </div>
            </div>
            
            <div class="mt-2 p-4 bg-slate-50 border border-slate-100 rounded-[1.5rem]">
                <p class="text-xs font-bold text-slate-500 flex items-start gap-2">
                    <i data-lucide="info" class="w-4 h-4 shrink-0 text-slate-400"></i>
                    Your next automatic settlement is scheduled for Monday at 09:00 AM IST.
                </p>
            </div>
        </div>

    </div>

    <!-- Settlement Board -->
    <div class="bg-white rounded-[2rem] p-2 md:p-8 shadow-sm border border-slate-100 flex flex-col flex-1 min-h-[400px]">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 px-4 md:px-0">
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Settlement Board</h2>
            
            <button class="px-4 py-2 bg-white border border-slate-200 rounded-xl flex items-center gap-2 font-bold text-slate-600 hover:text-indigo-600 hover:border-indigo-200 hover:bg-slate-50 shadow-sm text-sm transition-all">
                <i data-lucide="download" class="w-4 h-4"></i> Download All Reports
            </button>
        </div>

        <div class="overflow-x-auto w-full pb-4">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black tracking-widest text-slate-400 uppercase bg-white">
                        <th class="py-4 px-4 whitespace-nowrap min-w-[200px]">ID & Reference</th>
                        <th class="py-4 px-4 whitespace-nowrap">Execution Date</th>
                        <th class="py-4 px-4 whitespace-nowrap">Destination</th>
                        <th class="py-4 px-4 whitespace-nowrap text-right">Amount</th>
                        <th class="py-4 px-4 whitespace-nowrap text-center">Status</th>
                        <th class="py-4 px-4 whitespace-nowrap text-right">Receipt</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $settlement)
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors group">
                            <!-- ID & Reference -->
                            <td class="py-4 px-4">
                                <p class="font-bold text-slate-800 text-sm flex items-center gap-2">
                                    <i data-lucide="hash" class="w-3.5 h-3.5 text-slate-300"></i> {{ $settlement->id }}
                                </p>
                                <p class="text-[11px] text-slate-400 font-bold font-mono tracking-widest mt-1">{{ $settlement->reference }}</p>
                            </td>

                            <!-- Execution Date -->
                            <td class="py-4 px-4">
                                <span class="text-sm font-bold text-slate-600">{{ $settlement->date }}</span>
                            </td>

                            <!-- Destination -->
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center shrink-0">
                                        <i data-lucide="landmark" class="w-4 h-4"></i>
                                    </div>
                                    <span class="text-sm font-bold text-slate-700 tracking-wide">{{ $settlement->destination }}</span>
                                </div>
                            </td>

                            <!-- Amount -->
                            <td class="py-4 px-4 text-right">
                                <span class="font-black text-slate-800 tracking-tighter text-sm">₹{{ number_format($settlement->amount, 2) }}</span>
                            </td>

                            <!-- Status -->
                            <td class="py-4 px-4 text-center">
                                @if(strtoupper($settlement->status) === 'COMPLETED')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-widest mx-auto">
                                        <i data-lucide="check" class="w-3 h-3"></i> Completed
                                    </span>
                                @elseif(strtoupper($settlement->status) === 'PROCESSING')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 border border-amber-100 rounded-full text-[10px] font-black uppercase tracking-widest mx-auto">
                                        <i data-lucide="loader-2" class="w-3 h-3 animate-spin"></i> Processing
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase tracking-widest mx-auto">
                                        <i data-lucide="x" class="w-3 h-3"></i> Failed
                                    </span>
                                @endif
                            </td>

                            <!-- Receipt -->
                            <td class="py-4 px-4 text-right">
                                <button class="text-xs font-black text-indigo-600 hover:text-indigo-800 transition-colors uppercase tracking-widest flex items-center gap-1 justify-end w-full group/btn">
                                    <i data-lucide="file-down" class="w-4 h-4 group-hover/btn:-translate-y-0.5 transition-transform"></i> PDF
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-16 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-4">
                                        <i data-lucide="receipt" class="w-8 h-8"></i>
                                    </div>
                                    <p class="font-black text-slate-600 tracking-wide">No Settlements Found</p>
                                    <p class="text-xs mt-1 max-w-[250px] mx-auto">Your settlement history will appear here once payouts are processed.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(!$settlements->isEmpty())
        <div class="mt-auto pt-6 border-t border-slate-100 flex items-center justify-center">
            <p class="text-xs font-bold text-slate-400 flex items-center gap-1.5 uppercase tracking-widest">
                <i data-lucide="history" class="w-3.5 h-3.5"></i> Showing latest records. Full history available in Reports.
            </p>
        </div>
        @endif
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('vendorSettlements', () => ({
            init() {
                setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
            }
        }))
    });
</script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
