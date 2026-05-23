@extends('vendor.layout')

@section('title', 'Staff Management')

@section('content')
<div>
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Staff</h1>
        <p class="text-sm font-medium text-slate-500 mt-1">Welcome back, manager. Here's what's happening today.</p>
    </div>

    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900 card-shadow flex items-start gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 mt-0.5"></i>
            <div class="font-semibold">{{ session('success') }}</div>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-8 rounded-2xl border border-rose-100 bg-rose-50 px-6 py-4 text-rose-900 card-shadow">
            <div class="flex items-start gap-3 mb-2">
                <i data-lucide="alert-circle" class="w-5 h-5 mt-0.5"></i>
                <div class="font-semibold">Please fix the following errors:</div>
            </div>
            <ul class="list-disc list-inside text-sm ml-8 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Team Members Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Team Members</h2>
            <p class="text-sm text-slate-500 font-medium">Manage your store staff and their daily performance.</p>
        </div>
        <button onclick="document.getElementById('addStaffModal').classList.remove('hidden')" class="mt-4 sm:mt-0 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-bold flex items-center gap-2 hover-lift transition-all">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            Add New Staff
        </button>
    </div>

    <!-- Staff Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6 mb-10">
        @forelse($vendorStaffs as $staff)
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 card-shadow relative overflow-hidden group hover-lift">
                <!-- Abstract corner shape -->
                <div class="absolute -top-12 -right-12 w-32 h-32 bg-emerald-50 rounded-full opacity-50 transition-transform duration-500 group-hover:scale-110"></div>
                
                <div class="flex justify-between items-start mb-6 align-top">
                    <!-- Avatar Area -->
                    <div class="relative">
                        <div class="w-16 h-16 rounded-2xl bg-indigo-50 flex items-center justify-center text-indigo-600 font-bold text-2xl">
                            {{ substr($staff->name, 0, 1) }}
                        </div>
                        <!-- Status dot -->
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-white rounded-full flex items-center justify-center">
                            <div class="w-3 h-3 rounded-full {{ $staff->is_online ? 'bg-emerald-500' : 'bg-slate-300' }}"></div>
                        </div>
                    </div>
                    
                    <!-- Menu -->
                    <button class="text-slate-400 hover:text-slate-600 transition-colors z-10 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-50 relative mt-2 mr-2">
                        <i data-lucide="more-horizontal" class="w-5 h-5"></i>
                    </button>
                </div>

                <div class="mb-6 relative z-10">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">{{ $staff->name }}</h3>
                    <p class="text-[11px] font-bold text-indigo-600 uppercase tracking-widest mt-1">{{ $staff->role }}</p>
                </div>

                <div class="space-y-3 mb-6 relative z-10">
                    <div class="flex items-center text-sm font-medium text-slate-500">
                        <i data-lucide="phone" class="w-4 h-4 mr-3 text-slate-400"></i>
                        {{ $staff->phone }}
                    </div>
                    <div class="flex items-center text-sm font-medium text-slate-500">
                        <i data-lucide="clock" class="w-4 h-4 mr-3 text-slate-400"></i>
                        Shift: {{ \Carbon\Carbon::parse($staff->shift_start)->format('h:i A') }} - {{ \Carbon\Carbon::parse($staff->shift_end)->format('h:i A') }}
                    </div>
                </div>

                <div class="flex items-end justify-between pt-5 border-t border-slate-50 relative z-10">
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Performance Score</p>
                        <div class="flex items-center gap-1.5 font-bold text-slate-900">
                            <i data-lucide="star" class="w-4 h-4 text-amber-500 fill-amber-500"></i>
                            {{ $staff->performance_score }}%
                        </div>
                    </div>
                    <button class="px-4 py-2 bg-slate-50 text-slate-700 rounded-xl text-sm font-bold hover:bg-slate-100 transition-colors">
                        View Profile
                    </button>
                </div>
            </div>
        @empty
            <div class="col-span-1 md:col-span-2 xl:col-span-3 text-center py-16 bg-white rounded-[2rem] border border-slate-100 border-dashed">
                <div class="w-20 h-20 bg-slate-50 text-slate-300 rounded-3xl flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="users" class="w-10 h-10"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-2">No Staff Members Yet</h3>
                <p class="text-slate-500 font-medium mb-6">You haven't added any staff members to your store yet.</p>
                <button onclick="document.getElementById('addStaffModal').classList.remove('hidden')" class="px-6 py-3 bg-indigo-50 text-indigo-600 hover:bg-indigo-100 rounded-2xl font-bold transition-colors inline-block">
                    Add Your First Staff Member
                </button>
            </div>
        @endforelse
    </div>

    <!-- Quick Attendance Check -->
    <div class="bg-indigo-900 rounded-[2.5rem] p-8 sm:p-10 relative overflow-hidden">
        <!-- Decorative overlapping circles -->
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-indigo-800 rounded-full opacity-50 blur-2xl"></div>
        <div class="absolute -left-20 -top-20 w-64 h-64 bg-violet-800 rounded-full opacity-40 blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-2xl font-bold text-white mb-2">Quick Attendance Check</h3>
                <p class="text-indigo-200 font-medium relative">Monitor real-time presence and active shifts.</p>
            </div>
            
            <div class="flex items-center gap-4 w-full md:w-auto">
                <div class="bg-indigo-800/50 backdrop-blur-md rounded-2xl px-6 py-4 flex-1 md:flex-none border border-indigo-700/50">
                    <p class="text-[10px] font-bold text-indigo-300 uppercase tracking-widest mb-1">Currently Online</p>
                    <p class="text-3xl font-extrabold text-white flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-400"></span>
                        {{ $vendorStaffs->where('is_online', true)->count() }}
                        <span class="text-lg text-indigo-300 font-medium">/ {{ $vendorStaffs->count() }}</span>
                    </p>
                </div>
                <button class="h-full border border-indigo-700/50 bg-indigo-800/50 backdrop-blur-md hover:bg-indigo-700/80 rounded-2xl px-6 py-4 text-white font-bold transition-all flex flex-col items-center justify-center">
                    <i data-lucide="scan-line" class="w-6 h-6 mb-1"></i>
                    <span class="text-xs">Scan</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div id="addStaffModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" aria-hidden="true" onclick="document.getElementById('addStaffModal').classList.add('hidden')"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100 relative z-10">
                
                <div class="px-6 py-5 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-xl leading-6 font-bold text-slate-900" id="modal-title">
                        Add New Staff
                    </h3>
                    <button onclick="document.getElementById('addStaffModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 rounded-full p-2 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                
                <form action="{{ route('vendor.staff.store') }}" method="POST" class="p-6 sm:p-8">
                    @csrf
                    
                    <div class="space-y-5">
                        <!-- Name Field -->
                        <div>
                            <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Full Name</label>
                            <div class="relative">
                                <i data-lucide="user" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                <input type="text" name="name" id="name" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-900 font-medium" placeholder="e.g. Rahul Sharma">
                            </div>
                        </div>

                        <!-- Role Field -->
                        <div>
                            <label for="role" class="block text-sm font-bold text-slate-700 mb-2">Role / Designation</label>
                            <div class="relative">
                                <i data-lucide="briefcase" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                <input type="text" name="role" id="role" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-900 font-medium" placeholder="e.g. STORE MANAGER">
                            </div>
                        </div>

                        <!-- Phone Field -->
                        <div>
                            <label for="phone" class="block text-sm font-bold text-slate-700 mb-2">Phone Number</label>
                            <div class="relative">
                                <i data-lucide="phone" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400"></i>
                                <input type="text" name="phone" id="phone" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-900 font-medium" placeholder="+91 98765 43210">
                            </div>
                        </div>

                        <!-- Shift Timings -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="shift_start" class="block text-sm font-bold text-slate-700 mb-2">Shift Start</label>
                                <div class="relative">
                                    <i data-lucide="clock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none"></i>
                                    <input type="time" name="shift_start" id="shift_start" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-900 font-medium cursor-pointer">
                                </div>
                            </div>
                            <div>
                                <label for="shift_end" class="block text-sm font-bold text-slate-700 mb-2">Shift End</label>
                                <div class="relative">
                                    <i data-lucide="clock" class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400 pointer-events-none"></i>
                                    <input type="time" name="shift_end" id="shift_end" required class="block w-full pl-11 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-shadow outline-none text-slate-900 font-medium cursor-pointer">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex gap-3 sm:flex-row-reverse">
                        <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-bold text-white bg-indigo-600 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                            Save Staff Member
                        </button>
                        <button type="button" onclick="document.getElementById('addStaffModal').classList.add('hidden')" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 border border-slate-200 rounded-xl shadow-sm text-base font-bold text-slate-700 bg-white hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
