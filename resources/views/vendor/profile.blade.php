@extends('vendor.layout')

@section('title', 'Business Profile')

@section('content')
<div class="max-w-5xl mx-auto pb-12">
    @if(session('success'))
        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900 card-shadow flex items-center gap-3">
            <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
            <span class="font-bold">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Header Section -->
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Profile Overview</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Manage your public presence and operational details.</p>
        </div>
        <button onclick="document.getElementById('editProfileModal').classList.remove('hidden')" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold flex items-center gap-2 transition-colors card-shadow group">
            <i data-lucide="edit-3" class="w-4 h-4 group-hover:-rotate-12 transition-transform"></i>
            Edit Profile
        </button>
    </div>

    <!-- Main Profile Card -->
    <div class="bg-white rounded-[2rem] p-6 md:p-8 border border-slate-100 card-shadow relative overflow-hidden mb-10">
        <!-- Abstract gradient background -->
        <div class="absolute top-0 left-0 right-0 h-32 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 opacity-90"></div>
        
        <div class="relative mt-12 flex flex-col md:flex-row gap-8 items-start md:items-center">
            <!-- Avatar -->
            <div class="w-24 h-24 rounded-2xl bg-white p-1.5 shadow-lg shrink-0 z-10">
                <div class="w-full h-full rounded-xl bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center text-indigo-700 font-black text-3xl">
                    {{ strtoupper(substr($vendor->business_name ?? $vendor->first_name ?? 'V', 0, 2)) }}
                </div>
            </div>

            <!-- Core Info -->
            <div class="flex-1 mt-2 md:mt-0">
                <div class="flex flex-wrap items-center gap-3 mb-2">
                    <h2 class="text-2xl font-black text-slate-900">{{ $vendor->business_name ?? ($vendor->first_name . ' ' . $vendor->last_name) }}</h2>
                    @if($vendor->status === 'active')
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 rounded-full text-[10px] font-bold uppercase tracking-widest border border-emerald-100 flex items-center gap-1.5">
                            <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i> Verified Vendor
                        </span>
                    @else
                        <span class="px-3 py-1 bg-rose-50 text-rose-700 rounded-full text-[10px] font-bold uppercase tracking-widest border border-rose-100 flex items-center gap-1.5">
                            <i data-lucide="alert-circle" class="w-3.5 h-3.5"></i> Action Required
                        </span>
                    @endif
                </div>
                <div class="flex flex-wrap items-center gap-x-6 gap-y-2 text-sm font-medium text-slate-500">
                    <span class="flex items-center gap-1.5"><i data-lucide="hash" class="w-4 h-4 text-slate-400"></i> {{ $vendor->vendor_number ?? 'Auto-Generated' }}</span>
                    <span class="hidden sm:block w-1 h-1 rounded-full bg-slate-300"></span>
                    <span class="flex items-center gap-1.5"><i data-lucide="calendar" class="w-4 h-4 text-slate-400"></i> Member since {{ $vendor->created_at ? $vendor->created_at->format('M Y') : 'Unknown' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100 my-8">

        <!-- Contact Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-8 gap-y-8">
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                    <i data-lucide="mail" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Email Address</p>
                    <p class="text-sm font-bold text-slate-900">{{ $vendor->gmail_id ?? 'Not provided' }}</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                    <i data-lucide="phone" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Phone Number</p>
                    <p class="text-sm font-bold text-slate-900">+{{ $vendor->mobile_country_code ?? '91' }} {{ $vendor->mobile_no ?? 'Not provided' }}</p>
                </div>
            </div>
            
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                    <i data-lucide="map-pin" class="w-5 h-5"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Business Address</p>
                    <p class="text-sm font-bold text-slate-900 leading-relaxed">{{ $vendor->business_full_address ?? 'Not provided' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
        <!-- Business Documents -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <h3 class="text-xl font-bold text-slate-900">Registration Documents</h3>
                <button onclick="alert('Document upload capability is centralized in the Settings portal.')" class="text-[11px] inline-block text-center font-bold text-indigo-600 tracking-wide hover:text-indigo-700 bg-indigo-50 hover:bg-indigo-100 px-3.5 py-1.5 rounded-lg transition-colors border border-indigo-100/50">
                    UPLOAD NEW
                </button>
            </div>
            
            <div class="bg-white rounded-[2rem] border border-slate-100 card-shadow p-3">
                @php
                    $docs = [
                        ['title' => 'Goods and Services Tax (GST)', 'icon' => 'file-text', 'value' => $vendor->business_gst_no, 'color' => 'blue'],
                        ['title' => 'Permanent Account Number (PAN)', 'icon' => 'credit-card', 'value' => $vendor->pan_no, 'color' => 'purple'],
                        ['title' => 'Aadhar ID Check', 'icon' => 'fingerprint', 'value' => $vendor->aadhar_no, 'color' => 'amber'],
                        ['title' => 'Industry License', 'icon' => 'file-badge-2', 'value' => $vendor->business_registration_category, 'color' => 'emerald'],
                    ];
                @endphp

                @foreach($docs as $doc)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 hover:bg-slate-50/80 rounded-2xl transition-all duration-300 group gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-{{ $doc['color'] }}-50 text-{{ $doc['color'] }}-600 flex items-center justify-center shrink-0 border border-{{ $doc['color'] }}-100 group-hover:scale-105 transition-transform duration-300">
                            <i data-lucide="{{ $doc['icon'] }}" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900 mb-0.5">{{ $doc['title'] }}</p>
                            <p class="text-xs font-semibold text-slate-500">
                                {{ $doc['value'] ? 'Stored Securely' : 'Action Required' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        @if(!empty($doc['value']))
                            <span class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-700 rounded-md border border-emerald-100 text-[10px] font-bold uppercase tracking-widest shadow-sm">Verified</span>
                        @else
                            <span class="inline-flex px-3 py-1 bg-amber-50 text-amber-700 rounded-md border border-amber-100 text-[10px] font-bold uppercase tracking-widest shadow-sm">Pending</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Settlement Account -->
        <div>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <h3 class="text-xl font-bold text-slate-900">Settlement Account</h3>
                <a href="{{ route('vendor.settings') }}" class="text-[11px] inline-block text-center font-bold text-slate-600 tracking-wide hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3.5 py-1.5 rounded-lg transition-colors border border-slate-200/50">
                    MANAGE BANK
                </a>
            </div>
            
            <div class="bg-slate-900 rounded-[2rem] p-8 text-white card-shadow relative overflow-hidden group">
                <!-- Decorative elements simulating a premium bank card -->
                <div class="absolute -right-20 -top-20 w-80 h-80 bg-slate-800 rounded-full blur-3xl opacity-50 group-hover:bg-indigo-900/40 transition-colors duration-1000"></div>
                <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-indigo-600/30 rounded-full blur-2xl"></div>
                <!-- Chip decoration -->
                <div class="absolute right-8 top-1/2 -translate-y-1/2 opacity-10">
                    <i data-lucide="nfc" class="w-24 h-24"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-12">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">Banking Partner</p>
                            <p class="text-xl font-black text-white tracking-widest">{{ strtoupper($vendor->bank_name ?? 'NOT CONFIGURED') }}</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <i data-lucide="landmark" class="w-6 h-6 text-slate-200"></i>
                        </div>
                    </div>
                    
                    <div class="mb-10">
                        @php
                            $acc = $vendor->account_no ?? '';
                            $masked = !empty($acc) ? str_repeat('•', max(0, strlen($acc) - 4)) . ' ' . substr($acc, -4) : '•••• •••• •••• ••••';
                        @endphp
                        <p class="font-mono text-[1.65rem] tracking-[0.3em] font-medium text-slate-100/90 drop-shadow-md">{{ $masked }}</p>
                    </div>
                    
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">Account Holder</p>
                            <p class="text-sm font-bold text-white tracking-widest uppercase">{{ $vendor->account_holder_name ?? 'N/A' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] mb-1.5">IFSC / Routing</p>
                            <p class="text-sm font-bold text-white tracking-widest font-mono uppercase">{{ $vendor->ifsc_code ?? 'XX0000X' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Profile Modal -->
<div id="editProfileModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="document.getElementById('editProfileModal').classList.add('hidden')"></div>
    <div class="bg-white rounded-[2rem] p-8 max-w-2xl w-full mx-4 relative z-10 card-shadow border border-slate-100 max-h-[90vh] overflow-y-auto transform transition-all">
        <h2 class="text-2xl font-extrabold text-slate-900 mb-6">Edit Profile</h2>
        <form method="POST" action="{{ route('vendor.profile.update') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">First Name</label>
                    <input type="text" name="first_name" value="{{ old('first_name', $vendor->first_name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Last Name</label>
                    <input type="text" name="last_name" value="{{ old('last_name', $vendor->last_name) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Business Name</label>
                    <input type="text" name="business_name" value="{{ old('business_name', $vendor->business_name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Phone Number</label>
                    <input type="text" name="mobile_no" value="{{ old('mobile_no', $vendor->mobile_no) }}" required class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Email Address (Read-only)</label>
                    <input type="text" value="{{ $vendor->gmail_id }}" readonly class="w-full bg-slate-100 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-500 cursor-not-allowed">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Business Address</label>
                    <textarea name="business_full_address" rows="3" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold text-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('business_full_address', $vendor->business_full_address) }}</textarea>
                </div>
            </div>
            
            <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-slate-100">
                <button type="button" onclick="document.getElementById('editProfileModal').classList.add('hidden')" class="px-6 py-2.5 text-sm font-bold text-slate-600 hover:text-slate-900 bg-slate-50 hover:bg-slate-100 rounded-xl transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold rounded-xl transition-colors shadow-md shadow-indigo-600/20">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
