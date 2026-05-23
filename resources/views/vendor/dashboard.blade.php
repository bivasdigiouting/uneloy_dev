@extends('vendor.layout')

@section('title', 'Vendor Dashboard')

@section('content')
    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900 card-shadow">
            <div class="flex items-start gap-3">
                <div class="mt-0.5">
                    <i data-lucide="check-circle-2" class="w-5 h-5"></i>
                </div>
                <div class="font-semibold">{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <div class="relative group mb-10">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-[2.5rem] blur opacity-20 transition duration-1000 group-hover:opacity-40"></div>
        <div class="relative bg-white rounded-[2.5rem] p-6 sm:p-8 flex flex-col md:flex-row items-center justify-between border border-slate-100 card-shadow overflow-hidden">
            <div class="flex items-center space-x-6 relative z-10">
                <div class="w-16 h-16 bg-gradient-to-br from-indigo-600 to-violet-700 rounded-3xl flex items-center justify-center text-white shadow-lg shadow-indigo-200">
                    <i data-lucide="sparkles" class="w-8 h-8"></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1 flex items-center">
                        <i data-lucide="zap" class="w-3 h-3 mr-1"></i>
                        Intelligence Layer
                    </p>
                    <h2 class="text-2xl font-bold text-slate-900 leading-tight">
                        Welcome back, {{ $vendor->first_name ?? 'Vendor' }}.
                    </h2>
                    <p class="text-sm text-slate-500 font-medium mt-1">
                        Vendor ID: <span class="font-extrabold text-slate-900">{{ $vendor->vendor_number }}</span>
                        <span class="mx-2 text-slate-300">|</span>
                        Status:
                        <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ strtolower($vendor->status) === 'active' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ ucfirst($vendor->status) }}
                        </span>
                    </p>
                </div>
            </div>

            <a href="{{ route('vendor.profile') }}" class="mt-6 md:mt-0 px-6 py-3 bg-indigo-50 text-indigo-700 rounded-2xl font-bold flex items-center space-x-2 hover:bg-indigo-100 transition-all">
                <span>Update Profile</span>
                <i data-lucide="chevron-right" class="w-[18px] h-[18px]"></i>
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 mb-10">
        <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-slate-100 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Products</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-2">0</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-slate-100 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Orders</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-2">0</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-slate-100 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pending Orders</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-2">0</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6"></i>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-7 rounded-[2rem] border border-slate-100 card-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Earnings</p>
                    <p class="text-3xl font-extrabold text-slate-900 mt-2">₹0</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="indian-rupee" class="w-6 h-6"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-10">
        <div class="lg:col-span-2 bg-white p-6 sm:p-8 rounded-[3rem] border border-slate-100 card-shadow">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">Vendor Information</h3>
                    <p class="text-xs text-slate-400 font-medium">Profile summary & business details</p>
                </div>
                <a href="{{ route('vendor.profile') }}" class="px-5 py-2 bg-slate-900 text-white rounded-2xl font-bold hover:bg-indigo-600 transition-all">
                    Edit
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Business Name</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $vendor->business_name ?? 'Not provided' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Contact Email</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $vendor->gmail_id ?? 'Not provided' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Mobile</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $vendor->mobile_no ?? 'Not provided' }}</p>
                </div>
                <div class="rounded-2xl border border-slate-100 bg-slate-50 p-5 sm:p-6">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Vendor Type</p>
                    <p class="text-lg font-extrabold text-slate-900 mt-2">{{ $vendor->vendor_type ?? 'Not specified' }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-[3rem] border border-slate-100 card-shadow">
            <div class="mb-8">
                <h3 class="text-xl font-bold text-slate-900">Quick Actions</h3>
                <p class="text-xs text-slate-400 font-medium">Shortcuts for daily tasks</p>
            </div>

            <div class="space-y-4">
                <a href="{{ route('vendor.profile') }}" class="flex items-center justify-between px-5 sm:px-6 py-4 rounded-2xl bg-indigo-50 text-indigo-800 font-bold hover:bg-indigo-100 transition-all">
                    <span class="flex items-center gap-3"><i data-lucide="user" class="w-5 h-5"></i>Update Profile</span>
                    <i data-lucide="chevron-right" class="w-5 h-5 opacity-60"></i>
                </a>
                <a href="{{ route('vendor.products') }}" class="flex items-center justify-between px-5 sm:px-6 py-4 rounded-2xl bg-slate-900 text-white font-bold hover:bg-indigo-600 transition-all">
                    <span class="flex items-center gap-3"><i data-lucide="package-plus" class="w-5 h-5"></i>Add Product</span>
                    <i data-lucide="chevron-right" class="w-5 h-5 opacity-60"></i>
                </a>
                <a href="{{ route('vendor.reports') }}" class="flex items-center justify-between px-5 sm:px-6 py-4 rounded-2xl bg-slate-100 text-slate-900 font-bold hover:bg-slate-200 transition-all">
                    <span class="flex items-center gap-3"><i data-lucide="bar-chart-3" class="w-5 h-5"></i>View Reports</span>
                    <i data-lucide="chevron-right" class="w-5 h-5 opacity-60"></i>
                </a>
            </div>
        </div>
    </div>
@endsection
