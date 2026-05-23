@extends('vendor.layout')

@section('title', 'Payroll Management')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Payroll</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Welcome back, manager. Here's what's happening today.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900 card-shadow flex items-start gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 mt-0.5"></i>
            <div class="font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
        <!-- Monthly Payroll Summary Group -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 sm:p-8 rounded-[3rem] border border-slate-100 card-shadow h-full">
                <!-- Header and Tabs -->
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
                    <h2 class="text-xl font-bold text-slate-900">Monthly Payroll Summary</h2>
                    
                    <div class="flex items-center bg-slate-50 p-1 rounded-xl border border-slate-100">
                        <button class="px-5 py-2 text-sm font-bold bg-white text-indigo-600 rounded-lg shadow-sm">Current Month</button>
                        <button class="px-5 py-2 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">History</button>
                    </div>
                </div>

                <!-- Payroll Staff List -->
                <div class="space-y-4">
                    @forelse($payrolls as $payroll)
                        <div class="flex flex-col sm:flex-row items-center justify-between p-4 sm:p-5 rounded-3xl bg-slate-50 border border-slate-100 hover:border-indigo-100 transition-colors group">
                            <div class="flex items-center gap-4 w-full sm:w-auto">
                                <div class="w-12 h-12 rounded-2xl bg-slate-200 text-slate-400 flex items-center justify-center shrink-0">
                                    <i data-lucide="user" class="w-6 h-6"></i>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <h3 class="font-bold text-slate-900 truncate">{{ $payroll->staff->name ?? 'Unknown Staff' }}</h3>
                                    <p class="text-[10px] font-bold text-indigo-600 uppercase tracking-widest mt-0.5 truncate">{{ $payroll->staff->role ?? 'STAFF' }}</p>
                                </div>
                            </div>
                            
                            <div class="w-full sm:w-auto flex items-center justify-between sm:justify-end gap-6 mt-4 sm:mt-0 pl-16 sm:pl-0">
                                <div class="text-left sm:text-right">
                                    <p class="text-xl font-extrabold text-slate-900">₹{{ number_format($payroll->base_salary + $payroll->incentive) }}</p>
                                    @if($payroll->status === 'paid')
                                        <p class="text-[9px] font-extrabold text-emerald-600 uppercase tracking-widest mt-0.5">PAID {{ strtoupper($currentMonth->format('M Y')) }}</p>
                                    @else
                                        <p class="text-[9px] font-extrabold text-rose-500 uppercase tracking-widest mt-0.5">PENDING</p>
                                    @endif
                                </div>
                                
                                <button class="opacity-0 group-hover:opacity-100 transition-opacity px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl text-sm font-bold hover:bg-indigo-100 shrink-0">
                                    Slip
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i data-lucide="coffee" class="w-8 h-8"></i>
                            </div>
                            <h3 class="font-bold text-slate-900 mb-1">No Payroll Data Found</h3>
                            <p class="text-sm text-slate-500">Add staff members to start generating payrolls.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Right Side Stats -->
        <div class="flex flex-col gap-6">
            <!-- Total Disbursement Card -->
            <div class="bg-indigo-600 rounded-[2.5rem] p-8 sm:p-10 relative overflow-hidden text-white card-shadow">
                <!-- Abstract UI shapes -->
                <div class="absolute -right-20 -top-20 w-64 h-64 bg-indigo-500 rounded-full opacity-40 blur-2xl"></div>
                
                <div class="relative z-10">
                    <p class="text-[10px] font-bold text-indigo-200 uppercase tracking-widest mb-2">Total Disbursement</p>
                    <h2 class="text-5xl font-extrabold mb-8">₹{{ number_format($totalDisbursement) }}</h2>
                    
                    <div class="space-y-6">
                        <!-- Base Salaries -->
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-medium text-indigo-200">Base Salaries</span>
                                <span class="font-bold text-lg">₹{{ number_format($totalBasePaid) }}</span>
                            </div>
                            <div class="w-full bg-indigo-800/50 rounded-full h-2">
                                <div class="bg-emerald-400 h-2 rounded-full" style="width: {{ $totalDisbursement > 0 ? ($totalBasePaid / $totalDisbursement) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <!-- Incentives -->
                        <div>
                            <div class="flex justify-between items-end mb-2">
                                <span class="text-sm font-medium text-indigo-200">Incentives</span>
                                <span class="font-bold text-lg">₹{{ number_format($totalIncentivePaid) }}</span>
                            </div>
                            <div class="w-full bg-indigo-800/50 rounded-full h-2">
                                <div class="bg-emerald-400 h-2 rounded-full opacity-60" style="width: {{ $totalDisbursement > 0 ? ($totalIncentivePaid / $totalDisbursement) * 100 : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Queue Alerts -->
            <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 border border-slate-100 card-shadow flex-1">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-rose-50 text-rose-500 rounded-full flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-5 h-5"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-900">Payment Queue</h3>
                </div>
                
                <p class="text-sm text-slate-500 font-medium mb-8">
                    @if($pendingQueue->count() > 0)
                        {{ $pendingQueue->count() }} Staff members have pending incentive approvals from current sales.
                    @else
                        All staff members have been fully paid for this month. Excellent work!
                    @endif
                </p>

                <form action="{{ route('vendor.payroll.process') }}" method="POST">
                    @csrf
                    <button type="submit" @if($pendingQueue->count() == 0) disabled @endif class="w-full px-6 py-4 bg-slate-900 text-white rounded-2xl font-bold hover:bg-slate-800 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        Review Queue
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
