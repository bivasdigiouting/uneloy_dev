@extends('vendor.layout')

@section('title', $title ?? 'Payments')

@section('content')
<!-- Alpine.js is assumed to be loaded by layout -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<div x-data="vendorPayments()" x-cloak class="h-full flex flex-col gap-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-black text-slate-800 tracking-tight">Payments</h1>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Total Balance -->
        <div class="bg-indigo-600 rounded-[2.5rem] p-8 shadow-xl shadow-indigo-600/30 text-white relative overflow-hidden group col-span-1">
            <div class="absolute -right-8 -bottom-8 w-40 h-40 bg-indigo-500/50 rounded-full blur-2xl group-hover:bg-indigo-400/50 transition-colors"></div>
            
            <p class="text-indigo-200 text-sm font-bold tracking-widest uppercase mb-2">Total Balance</p>
            <h3 class="text-4xl md:text-5xl font-black mb-8 tracking-tighter">₹{{ number_format($totalBalance, 2) }}</h3>
            
            <div class="flex items-center gap-4 relative z-10">
                <span class="px-4 py-2 bg-indigo-500/50 rounded-full text-xs font-black flex items-center gap-2 border border-indigo-400/30">
                    <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-300"></i> Settled
                </span>
                <button class="flex items-center gap-2 text-sm font-bold hover:text-indigo-200 transition-colors group/btn">
                    Withdraw <i data-lucide="arrow-right" class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform"></i>
                </button>
            </div>
            
            <!-- decorative background element simulating the card icon -->
            <div class="absolute right-6 top-1/2 -translate-y-1/2 opacity-20 hidden lg:block">
                <svg width="100" height="70" viewBox="0 0 100 70" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="2" y="2" width="96" height="66" rx="14" stroke="white" stroke-width="4"/>
                    <line x1="2" y1="20" x2="98" y2="20" stroke="white" stroke-width="4"/>
                </svg>
            </div>
        </div>

        <!-- UPI Transactions -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 group relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-slate-500 text-sm font-bold">UPI Transactions</h4>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">₹{{ number_format($upiTotal, 0) }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="smartphone" class="w-6 h-6"></i>
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-between text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                <span>Weekly Trend</span>
                <span class="text-emerald-500">+18%</span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-purple-400 to-indigo-500 rounded-full" style="width: 75%;"></div>
            </div>
        </div>

        <!-- Cash Collection -->
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 group relative">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-slate-500 text-sm font-bold">Cash Collection</h4>
                    <h3 class="text-3xl font-black text-slate-800 mt-1">₹{{ number_format($cashTotal, 0) }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform shadow-sm">
                    <i data-lucide="banknote" class="w-6 h-6"></i>
                </div>
            </div>
            
            <div class="mt-8 flex items-center justify-between text-xs font-black uppercase tracking-widest text-slate-400 mb-2">
                <span>Collection Rate</span>
                <span class="text-slate-800">High</span>
            </div>
            <!-- Progress Bar -->
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 90%;"></div>
            </div>
        </div>

    </div>

    <!-- Recent Transactions Table Area -->
    <div class="bg-white rounded-[2rem] p-2 md:p-8 shadow-sm border border-slate-100 flex-1 flex flex-col min-h-[400px]">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6 px-4 md:px-0">
            <h2 class="text-xl font-black text-slate-800">Recent Transactions</h2>
            
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <div class="relative group flex-1 sm:w-64">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 w-4 h-4"></i>
                    <input type="text" x-model="searchQuery" placeholder="Transaction ID, Member..." class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 outline-none transition-all">
                </div>
                <button class="w-10 h-10 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-100 transition-colors shrink-0">
                    <i data-lucide="filter" class="w-4 h-4"></i>
                </button>
            </div>
        </div>

        <div class="overflow-x-auto flex-1">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-[10px] font-black tracking-widest text-slate-400 uppercase">
                        <th class="py-4 px-4 whitespace-nowrap">TXN Details</th>
                        <th class="py-4 px-4 whitespace-nowrap">Member ID</th>
                        <th class="py-4 px-4 whitespace-nowrap">Amount</th>
                        <th class="py-4 px-4 whitespace-nowrap">Status</th>
                        <th class="py-4 px-4 whitespace-nowrap">Method</th>
                        <th class="py-4 px-4 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <template x-if="filteredTransactions().length === 0">
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="flex flex-col items-center justify-center">
                                    <i data-lucide="file-x" class="w-12 h-12 mb-3 opacity-20"></i>
                                    <p class="font-bold">No transactions found.</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                    
                    <template x-for="txn in filteredTransactions()" :key="txn.id">
                        <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors group">
                            <td class="py-4 px-4">
                                <p class="font-bold text-slate-800 text-sm" x-text="txn.txId"></p>
                                <p class="text-xs text-slate-400 font-medium" x-text="txn.date"></p>
                            </td>
                            <td class="py-4 px-4 font-bold text-indigo-600 text-sm" x-text="txn.customer"></td>
                            <td class="py-4 px-4 font-black text-slate-800 text-sm" x-text="'₹' + txn.amount"></td>
                            <td class="py-4 px-4">
                                <!-- Using success styling identically to the template -->
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-xs font-bold flex items-center gap-1 w-max">
                                    <i data-lucide="check-circle" class="w-3 h-3"></i> Success
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-500 text-xs uppercase" x-text="txn.method"></td>
                            <td class="py-4 px-4 text-right">
                                <button class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors">View Receipt</button>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('vendorPayments', () => ({
            searchQuery: '',
            rawTransactions: @json($transactions ?? []),
            
            get transactions() {
                // Map database transaction array objects into standard UI-friendly objects
                return this.rawTransactions.map(t => {
                    const dateObj = new Date(t.created_at);
                    const formattedDate = dateObj.toLocaleDateString('en-US') + ' • ' + dateObj.toLocaleTimeString('en-US', {hour: '2-digit', minute:'2-digit'});
                    
                    let method = 'UPI';
                    if (t.narration && t.narration.toLowerCase().includes('cash')) method = 'CASH';
                    if (t.narration && t.narration.toLowerCase().includes('card')) method = 'ONLINE';
                    
                    // Mocking Member ID for visual completeness if not strictly captured structurally yet
                    let customer = 'ID-' + Math.floor(1000 + Math.random() * 9000);
                    if (t.narration && t.narration.includes('Cust:')) {
                        customer = t.narration.split('Cust:')[1].trim().split(' ')[0];
                    }

                    return {
                        id: t.id,
                        txId: 'T' + t.id,
                        date: formattedDate,
                        customer: customer,
                        amount: parseFloat(t.amount).toLocaleString('en-IN'),
                        method: method,
                        searchString: (`T${t.id} ${customer} ${t.amount} ${method}`).toLowerCase()
                    };
                });
            },

            filteredTransactions() {
                if (this.searchQuery === '') return this.transactions;
                const sq = this.searchQuery.toLowerCase();
                return this.transactions.filter(t => t.searchString.includes(sq));
            },

            init() {
                setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
                this.$watch('searchQuery', () => {
                    setTimeout(() => { if(window.lucide) window.lucide.createIcons(); }, 10);
                });
            }
        }))
    });
</script>
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
