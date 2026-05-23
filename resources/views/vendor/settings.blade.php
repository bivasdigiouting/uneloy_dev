@extends('vendor.layout')

@section('title', 'System Controls')

@section('content')
<div class="max-w-6xl xl:max-w-7xl mx-auto pb-12">
    <!-- Header -->
    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">System Controls</h1>
            <p class="text-sm font-medium text-slate-500 mt-1.5">Manage enterprise modules, administration, and operational metrics natively.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-8 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900 card-shadow flex items-center justify-between shadow-[0_4px_20px_-4px_rgba(16,185,129,0.1)]">
            <div class="flex items-center gap-3">
                <i data-lucide="check-circle-2" class="w-5 h-5 text-emerald-600"></i>
                <span class="font-bold">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-emerald-700 hover:text-emerald-900 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif
    @if(session('error'))
        <div class="mb-8 rounded-2xl border border-rose-100 bg-rose-50 px-6 py-4 text-rose-900 card-shadow flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-rose-600"></i>
                <span class="font-bold">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-rose-700 hover:text-rose-900 transition-colors"><i data-lucide="x" class="w-4 h-4"></i></button>
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:w-72 xl:w-80 shrink-0">
            <div class="bg-slate-950 rounded-[2rem] p-4 xl:p-5 card-shadow border border-slate-900/50 sticky top-24 shadow-xl shadow-slate-900/10">
                <h3 class="text-[10.5px] font-black text-slate-500 uppercase tracking-widest px-4 mb-4 mt-2 flex items-center gap-2">
                    <i data-lucide="layout-grid" class="w-3.5 h-3.5"></i> ADMIN HUB
                </h3>
                <nav class="space-y-1">
                    <!-- 1. QR Code Management -->
                    <button onclick="switchTab('qr_code')" id="tab-qr_code" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="qr-code" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        QR Code Management
                    </button>
                    <!-- 2. Wallet Management -->
                    <button onclick="switchTab('wallet')" id="tab-wallet" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="wallet-cards" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Wallet Management
                    </button>
                    <!-- 3. Card Management -->
                    <button onclick="switchTab('card')" id="tab-card" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="credit-card" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Card Management
                    </button>
                    <!-- 4. My Management (Admin) -->
                    <button onclick="switchTab('admin')" id="tab-admin" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl bg-indigo-600 text-white font-bold transition-all text-sm group shadow-lg shadow-indigo-600/20 active-tab">
                        <i data-lucide="user-cog" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        My Management (Admin)
                    </button>
                    <!-- 5. Transaction Management -->
                    <button onclick="switchTab('transaction')" id="tab-transaction" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="arrow-right-left" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Transaction Management
                    </button>
                    <!-- 6. Device & Permissions -->
                    <button onclick="switchTab('devices')" id="tab-devices" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="monitor-smartphone" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Device & Permissions
                    </button>
                    <!-- 7. Login History -->
                    <button onclick="switchTab('login_history')" id="tab-login_history" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="history" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Login History
                    </button>
                    <!-- 8. Security Management -->
                    <button onclick="switchTab('security')" id="tab-security" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="shield-alert" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Security Management
                    </button>
                    <!-- 9. Change Password -->
                    <button onclick="switchTab('password')" id="tab-password" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="key-round" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        Change Password
                    </button>
                    <!-- 10. General Settings -->
                    <button onclick="switchTab('general')" id="tab-general" class="settings-tab w-full flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-slate-400 hover:text-white hover:bg-white/5 font-bold transition-all text-sm group">
                        <i data-lucide="settings" class="w-5 h-5 opacity-90 group-hover:scale-110 transition-transform"></i>
                        General Settings
                    </button>
                </nav>
            </div>
        </div>

        <!-- Main Content Core -->
        <div class="flex-1 min-w-0">
        
            <!-- 4. MY MANAGEMENT (ADMIN) TAB -->
            <div id="panel-admin" class="settings-panel bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100">
                        <i data-lucide="user-cog" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">My Management (Admin)</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Manage your identity and operational metrics securely</p>
                    </div>
                </div>

                <form action="{{ route('vendor.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab_source" value="general">
                    
                    <div class="space-y-10">
                        <!-- Branding Block -->
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4">Branding & Identity</label>
                            <div class="flex flex-col sm:flex-row sm:items-center gap-6">
                                <button type="button" class="w-20 h-20 rounded-full border-2 border-dashed border-slate-300 hover:border-indigo-400 hover:bg-indigo-50 flex items-center justify-center text-slate-400 hover:text-indigo-600 transition-colors group shrink-0">
                                    <i data-lucide="plus" class="w-6 h-6 group-hover:scale-110 transition-transform"></i>
                                </button>
                                <div class="flex-1 w-full">
                                    <input type="text" name="business_name" value="{{ old('business_name', $vendor->business_name) }}" class="w-full bg-slate-50 border border-slate-200 rounded-2xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all placeholder:text-slate-400" placeholder="e.g. Royal Plaza Delicacies">
                                </div>
                            </div>
                        </div>

                        <!-- Op Hours & Rules -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            @php $settings = $vendor->settings ?? []; @endphp
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Operating Hours</label>
                                <input type="text" name="operating_hours" value="{{ $settings['operating_hours'] ?? '09:00 AM - 10:00 PM' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all" placeholder="09:00 AM - 10:00 PM">
                            </div>
                            <div>
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Commission Rule Config</label>
                                <input type="text" name="commission_rule" value="{{ $settings['commission_rule'] ?? 'Fixed 5% + ₹2 Transaction Fee' }}" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                            </div>
                        </div>
                        
                        <!-- Shifts -->
                        <div>
                            <div class="flex items-center justify-between mb-4">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest">Operational Shifts</label>
                                <button type="button" class="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-lg border border-indigo-100 transition-colors hover:bg-indigo-100 hover:border-indigo-200 flex items-center gap-1.5 shadow-sm">
                                    <i data-lucide="plus" class="w-3 h-3"></i> CREATE SHIFT
                                </button>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                                <div class="border border-slate-200 bg-white p-5 rounded-2xl flex justify-between items-center group shadow-sm hover:shadow-md hover:border-indigo-200 transition-all hover:-translate-y-0.5 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-700 transition-colors">Morning Team</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 font-mono tracking-wide">08:00 AM - 04:00 PM</p>
                                    </div>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]"></span>
                                </div>
                                <div class="border border-slate-200 bg-white p-5 rounded-2xl flex justify-between items-center group shadow-sm hover:shadow-md hover:border-indigo-200 transition-all hover:-translate-y-0.5 cursor-pointer">
                                    <div>
                                        <p class="text-sm font-bold text-slate-900 group-hover:text-indigo-700 transition-colors">Evening Shift</p>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 font-mono tracking-wide">04:00 PM - 12:00 AM</p>
                                    </div>
                                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.6)]"></span>
                                </div>
                                <div class="border-2 border-dashed border-slate-200 bg-slate-50/50 p-5 rounded-2xl flex flex-col items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50/50 hover:border-indigo-300 transition-all cursor-pointer text-center group">
                                    <i data-lucide="clock" class="w-5 h-5 mb-1.5 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-widest group-hover:font-extrabold">Define Custom</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12 pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition-all shadow-lg shadow-indigo-600/20 active:scale-95 flex items-center gap-2">
                            <i data-lucide="save" class="w-4 h-4"></i> Save Configurations
                        </button>
                    </div>
                </form>
            </div>

            <!-- 6. DEVICE & PERMISSIONS TAB -->
            <div id="panel-devices" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center shrink-0 border border-emerald-100">
                        <i data-lucide="monitor-smartphone" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Device Permissions</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Manage which POS hardware systems have native API access</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-2xl border border-slate-100 hover:border-indigo-200 bg-white hover:shadow-lg hover:shadow-indigo-50/50 transition-all group gap-4">
                        <div class="flex items-center gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-600 shadow-md shadow-indigo-600/30 flex items-center justify-center text-white shrink-0 group-hover:scale-110 transition-transform">
                                <i data-lucide="monitor" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <div class="flex items-center gap-3">
                                    <p class="text-[15px] font-extrabold text-slate-900">Master POS Terminal</p>
                                    <span class="px-2.5 py-0.5 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-md text-[9px] font-black tracking-widest uppercase">Current Machine</span>
                                </div>
                                <div class="flex items-center gap-3 mt-1.5">
                                    <p class="text-xs font-semibold text-slate-400">Windows 11 • Edge Browser</p>
                                    <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                    <p class="text-[11px] font-bold text-indigo-500 font-mono bg-indigo-50 px-2 rounded tracking-wide">192.168.1.104</p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity self-start sm:self-center">
                            <button type="button" class="w-10 h-10 rounded-xl bg-slate-50 hover:bg-slate-100 border border-transparent hover:border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-all">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button type="button" class="w-10 h-10 rounded-xl bg-rose-50 hover:bg-rose-100 border border-transparent hover:border-rose-200 flex items-center justify-center text-rose-400 hover:text-rose-600 transition-all cursor-not-allowed opacity-50">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 8. SECURITY MANAGEMENT TAB -->
            <div id="panel-security" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center shrink-0 border border-rose-100">
                        <i data-lucide="shield-alert" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Security Management</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Defend your vendor account against unauthorized access dynamically</p>
                    </div>
                </div>

                <form action="{{ route('vendor.settings.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="tab_source" value="security">
                    @php $settings = $vendor->settings ?? []; @endphp

                    <div class="space-y-6">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-2xl border {{ !empty($settings['two_factor_auth']) ? 'border-indigo-200 bg-indigo-50/40 shadow-sm shadow-indigo-100/50' : 'border-slate-100 bg-slate-50/50' }} transition-colors gap-4">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900 flex items-center gap-2">Two-Factor Authentication (2FA) @if(!empty($settings['two_factor_auth'])) <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> @endif</p>
                                <p class="text-xs font-medium text-slate-500 mt-1 max-w-md leading-relaxed">Require a secure code sent via SMS every time a terminal performs a critical destructive action.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="two_factor_auth" class="sr-only peer" {{ !empty($settings['two_factor_auth']) ? 'checked' : '' }}>
                                <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-5.5 after:shadow-sm after:transition-all peer-checked:bg-indigo-600 overflow-hidden"></div>
                            </label>
                        </div>
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-6 rounded-2xl border {{ !empty($settings['ip_restricted']) ? 'border-indigo-200 bg-indigo-50/40 shadow-sm shadow-indigo-100/50' : 'border-slate-100 bg-slate-50/50' }} transition-colors gap-4">
                            <div>
                                <p class="text-sm font-extrabold text-slate-900">IP Restricted Access</p>
                                <p class="text-xs font-medium text-slate-500 mt-1 max-w-md leading-relaxed">Lock the admin panel forcing logins exclusively from your designated mall network IP.</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer shrink-0">
                                <input type="checkbox" name="ip_restricted" class="sr-only peer" {{ !empty($settings['ip_restricted']) ? 'checked' : '' }}>
                                <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-5.5 after:shadow-sm after:transition-all peer-checked:bg-indigo-600"></div>
                            </label>
                        </div>
                    </div>
                    
                    <div class="mt-8 pt-6 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-3.5 bg-slate-950 hover:bg-black text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-900/20 active:scale-95 flex items-center gap-2">
                            <i data-lucide="shield" class="w-4 h-4"></i> Apply Security Protocols
                        </button>
                    </div>
                </form>
            </div>

            <!-- 9. CHANGE PASSWORD TAB -->
            <div id="panel-password" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center shrink-0 border border-amber-100">
                        <i data-lucide="key-round" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Change Password</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Rotate your administrative login keys</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('vendor.password.change') }}" class="space-y-8">
                    @csrf
                    <div class="bg-slate-50/50 p-8 rounded-[2rem] border border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="md:col-span-2">
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Current Password</label>
                            <input type="password" name="current_password" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all @error('current_password') border-rose-400 ring-rose-500/10 @enderror">
                            @error('current_password')<p class="mt-2 text-xs font-bold text-rose-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">New Password Definition</label>
                            <input type="password" name="new_password" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all @error('new_password') border-rose-400 ring-rose-500/10 @enderror">
                            @error('new_password')<p class="mt-2 text-xs font-bold text-rose-500 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> {{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-3">Confirm New Password</label>
                            <input type="password" name="new_password_confirmation" required class="w-full bg-white border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 transition-all">
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-rose-50 text-rose-600 border border-rose-100/50 hover:bg-rose-100 hover:text-rose-700 hover:border-rose-200 rounded-xl font-bold transition-all shadow-sm flex items-center justify-center gap-2 active:scale-95">
                            <i data-lucide="refresh-cw" class="w-4 h-4"></i> Synchronize New Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- 1. QR CODE MANAGEMENT TAB -->
            <div id="panel-qr_code" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100">
                        <i data-lucide="qr-code" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">QR Code Management</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Scan this code to access the complete digital menu and payment portal of Royal Plaza.</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Master QR Code -->
                    <div class="flex flex-col items-center justify-center bg-slate-50 rounded-3xl border border-slate-200 p-10 text-center">
                        <div class="w-48 h-48 bg-white border border-slate-200 rounded-2xl shadow-sm mb-6 flex items-center justify-center opacity-70">
                            <i data-lucide="qr-code" class="w-24 h-24 text-slate-300"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">Master QR Code</h3>
                        <p class="text-xs font-medium text-slate-500 mb-6">Generated on Oct 12, 2023</p>
                        <div class="flex gap-3 w-full max-w-xs">
                            <button class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-2.5 rounded-xl font-bold transition-all text-sm shadow-sm flex items-center justify-center gap-2">
                                <i data-lucide="download" class="w-4 h-4"></i> Download PNG
                            </button>
                            <button class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-2.5 rounded-xl font-bold transition-all text-sm flex items-center justify-center gap-2 border border-slate-200">
                                <i data-lucide="refresh-cw" class="w-4 h-4"></i> Re-generate
                            </button>
                        </div>
                    </div>
                    <!-- Analytics -->
                    <div>
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">QR Mapping Analytics</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-white shadow-sm hover:border-indigo-100 transition-colors">
                                <div><p class="text-sm font-bold text-slate-900">Main Entrance</p><p class="text-xs font-semibold text-slate-500 mt-0.5">1,240 Total Scans</p></div>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Active</span>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-white shadow-sm hover:border-indigo-100 transition-colors">
                                <div><p class="text-sm font-bold text-slate-900">Table 04 (Express)</p><p class="text-xs font-semibold text-slate-500 mt-0.5">850 Total Scans</p></div>
                                <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Active</span>
                            </div>
                            <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 bg-white shadow-sm transition-colors opacity-70">
                                <div><p class="text-sm font-bold text-slate-900">Counter B</p><p class="text-xs font-semibold text-slate-500 mt-0.5">210 Total Scans</p></div>
                                <span class="px-2.5 py-1 bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest rounded-lg border border-slate-200">Disabled</span>
                            </div>
                        </div>
                        <div class="mt-6 p-5 rounded-2xl bg-amber-50 border border-amber-100">
                            <h4 class="text-[10px] font-black text-amber-600 uppercase tracking-widest mb-1 flex items-center gap-1.5"><i data-lucide="zap" class="w-3.5 h-3.5"></i> Top Performer</h4>
                            <p class="text-sm font-extrabold text-amber-900">Artisan Coffee Collection</p>
                            <p class="text-xs font-semibold text-amber-700 mt-0.5">Responsible for 42% of interactions globally.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. WALLET MANAGEMENT TAB -->
            <div id="panel-wallet" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- Balance Card -->
                    <div class="md:w-1/2">
                        <div class="bg-slate-900 rounded-3xl p-8 relative overflow-hidden shadow-xl shadow-slate-900/20 group h-full flex flex-col justify-between min-h-[300px]">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-indigo-500/20 rounded-full blur-3xl transition-all duration-700"></div>
                            <div>
                                <div class="flex justify-between items-start mb-8 relative z-10">
                                    <p class="text-[11px] font-black text-teal-400 uppercase tracking-widest">Internal Balance</p>
                                    <span class="px-2 py-0.5 bg-white/10 text-white/90 text-[9px] font-bold uppercase tracking-widest rounded border border-white/5">Global Wallet</span>
                                </div>
                                <h2 class="text-4xl font-black text-white mb-10 relative z-10 tracking-tight">₹2,45,000.00</h2>
                            </div>
                            <div class="flex gap-4 relative z-10 mt-auto">
                                <button type="button" class="flex-1 bg-white hover:bg-slate-50 text-slate-900 py-3.5 rounded-xl font-bold transition-all text-sm shadow-lg active:scale-95">Settle Now</button>
                                <button type="button" class="flex-1 bg-transparent hover:bg-white/5 text-white border border-white/20 py-3.5 rounded-xl font-bold transition-all text-sm active:scale-95">Ledger Report</button>
                            </div>
                        </div>
                    </div>
                    <!-- Permissions -->
                    <div class="md:w-1/2">
                        <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest mb-5">Permission Management Section</label>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-5 rounded-2xl border border-emerald-100 bg-emerald-50/30">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i data-lucide="lock" class="w-5 h-5"></i></div>
                                    <p class="text-sm font-bold text-slate-900">Staff Payouts</p>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            </div>
                            <div class="flex items-center justify-between p-5 rounded-2xl border border-emerald-100 bg-emerald-50/30">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><i data-lucide="lock" class="w-5 h-5"></i></div>
                                    <p class="text-sm font-bold text-slate-900">Refund Authority</p>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span>
                            </div>
                            <div class="flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-slate-50 opacity-70">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-slate-200 text-slate-500 flex items-center justify-center shrink-0"><i data-lucide="lock" class="w-5 h-5"></i></div>
                                    <p class="text-sm font-bold text-slate-900">Instant Credits</p>
                                </div>
                                <span class="w-2.5 h-2.5 rounded-full bg-slate-300"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 3. CARD MANAGEMENT TAB -->
            <div id="panel-card" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center justify-between mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0 border border-indigo-100">
                            <i data-lucide="credit-card" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900">Building Card Registry</h2>
                            <p class="text-xs font-semibold text-slate-500 mt-1">Manage activated NFC/RFID mall cards natively</p>
                        </div>
                    </div>
                    <button type="button" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-bold transition-all text-sm flex items-center gap-2 shadow-sm">
                        <i data-lucide="plus" class="w-4 h-4"></i> Activate New Card
                    </button>
                </div>
                
                <div class="overflow-x-auto border border-slate-200 rounded-2xl shadow-sm">
                    <table class="w-full text-left border-collapse min-w-[700px]">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-200">
                                <th class="py-4 px-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Card Reference</th>
                                <th class="py-4 px-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Status</th>
                                <th class="py-4 px-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Linked Profile</th>
                                <th class="py-4 px-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Txn Count</th>
                                <th class="py-4 px-5 text-[10px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5 text-sm font-extrabold text-slate-900 font-mono tracking-wide">MALL-8821</td>
                                <td class="py-4 px-5"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Active</span></td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-700">Premium Member #01</td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-500">142</td>
                                <td class="py-4 px-5 text-right text-slate-400 hover:text-indigo-600 cursor-pointer"><i data-lucide="more-vertical" class="w-5 h-5 inline-block"></i></td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-5 text-sm font-extrabold text-slate-900 font-mono tracking-wide">MALL-9942</td>
                                <td class="py-4 px-5"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-emerald-100">Active</span></td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-700">Staff Card (Internal)</td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-500">28</td>
                                <td class="py-4 px-5 text-right text-slate-400 hover:text-indigo-600 cursor-pointer"><i data-lucide="more-vertical" class="w-5 h-5 inline-block"></i></td>
                            </tr>
                            <tr class="hover:bg-slate-50/50 transition-colors opacity-60">
                                <td class="py-4 px-5 text-sm font-extrabold text-slate-900 font-mono tracking-wide">MALL-1024</td>
                                <td class="py-4 px-5"><span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest rounded-lg border border-rose-100">Blocked</span></td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-700">Suspended Account</td>
                                <td class="py-4 px-5 text-sm font-bold text-slate-500">0</td>
                                <td class="py-4 px-5 text-right text-slate-400 hover:text-indigo-600 cursor-pointer"><i data-lucide="more-vertical" class="w-5 h-5 inline-block"></i></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- 5. TRANSACTION MANAGEMENT TAB -->
            <div id="panel-transaction" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-cyan-50 text-cyan-600 rounded-2xl flex items-center justify-center shrink-0 border border-cyan-100">
                            <i data-lucide="arrow-right-left" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-extrabold text-slate-900">Global Transaction Log</h2>
                            <p class="text-xs font-semibold text-slate-500 mt-1">Audit and intercept all portal money movements natively</p>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <div class="relative">
                            <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2"></i>
                            <input type="text" class="w-full sm:w-64 bg-slate-50 border border-slate-200 rounded-xl pl-10 pr-4 py-2.5 text-sm font-bold text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500 placeholder:text-slate-400" placeholder="TXN ID, Member ID...">
                        </div>
                        <button type="button" class="bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 px-4 py-2.5 rounded-xl font-bold transition-all text-sm flex items-center gap-2">
                            <i data-lucide="filter" class="w-4 h-4"></i> Filter
                        </button>
                    </div>
                </div>

                <div class="space-y-4">
                    <!-- Success Txn -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-2xl border border-slate-100 hover:border-indigo-100 bg-white shadow-sm transition-colors gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100"><i data-lucide="arrow-down-left" class="w-5 h-5"></i></div>
                            <div>
                                <p class="text-[15px] font-extrabold text-slate-900 font-mono tracking-wide">TXN-44921</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="user" class="w-3 h-3"></i> By Staff Priya • 10 mins ago</p>
                            </div>
                        </div>
                        <div class="text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 w-full sm:w-auto">
                            <p class="text-lg font-black text-slate-900">₹1,250.00</p>
                            <span class="text-[10px] font-black text-emerald-600 uppercase tracking-widest flex items-center gap-1 bg-emerald-50 px-2 py-0.5 rounded border border-emerald-100"><i data-lucide="check" class="w-3 h-3"></i> Success</span>
                        </div>
                    </div>
                    <!-- Failed Txn -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-2xl border border-rose-100 bg-rose-50/40 shadow-sm transition-colors gap-4 opacity-90">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white text-rose-600 flex items-center justify-center shrink-0 border border-rose-200"><i data-lucide="x" class="w-5 h-5"></i></div>
                            <div>
                                <p class="text-[15px] font-extrabold text-slate-900 font-mono tracking-wide">TXN-44920</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="shield-alert" class="w-3 h-3"></i> By Staff Admin • 14 mins ago</p>
                            </div>
                        </div>
                        <div class="text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 w-full sm:w-auto">
                            <p class="text-lg font-black text-slate-900">₹4,500.00</p>
                            <span class="text-[10px] font-black text-rose-600 uppercase tracking-widest flex items-center gap-1 bg-rose-100 px-2 py-0.5 rounded border border-rose-200"><i data-lucide="alert-circle" class="w-3 h-3"></i> Failed</span>
                        </div>
                    </div>
                    <!-- Refunded Txn -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between p-5 rounded-2xl border border-blue-100 bg-blue-50/40 shadow-sm transition-colors gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-white text-blue-600 flex items-center justify-center shrink-0 border border-blue-200"><i data-lucide="corner-up-left" class="w-5 h-5"></i></div>
                            <div>
                                <p class="text-[15px] font-extrabold text-slate-900 font-mono tracking-wide">TXN-44919</p>
                                <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-widest flex items-center gap-1.5"><i data-lucide="cpu" class="w-3 h-3"></i> System Auto • 45 mins ago</p>
                            </div>
                        </div>
                        <div class="text-right flex sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-2 w-full sm:w-auto">
                            <p class="text-lg font-black text-slate-900">₹85.00</p>
                            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest flex items-center gap-1 bg-blue-100 px-2 py-0.5 rounded border border-blue-200"><i data-lucide="info" class="w-3 h-3"></i> Refunded</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- 7. LOGIN HISTORY TAB -->
            <div id="panel-login_history" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-8">
                    <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center shrink-0 border border-sky-100">
                        <i data-lucide="history" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">Access Logs</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Audit trail of temporal access to your master portal exclusively</p>
                    </div>
                </div>

                <div class="relative pl-6 border-l-2 border-slate-100 space-y-8 mt-6">
                    <!-- Log 1 -->
                    <div class="relative">
                        <span class="absolute -left-[35px] top-1 w-4 h-4 rounded-full bg-sky-500 border-4 border-white shadow-sm"></span>
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl hover:border-sky-200 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-[15px] font-extrabold text-slate-900">Vendor Admin</p>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Today, 09:12 AM</span>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-500 mt-1">Authorized Entry</p>
                            <p class="text-[11px] font-bold text-slate-400 mt-2 flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> MALL CYBER CITY</p>
                            <p class="text-[10px] font-mono font-bold text-slate-400 mt-1">IP: 192.168.1.42</p>
                        </div>
                    </div>
                    <!-- Log 2 -->
                    <div class="relative">
                        <span class="absolute -left-[35px] top-1 w-4 h-4 rounded-full bg-slate-200 border-4 border-white shadow-sm"></span>
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl hover:border-slate-200 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-[15px] font-extrabold text-slate-900">Staff Priya</p>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Today, 08:58 AM</span>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-500 mt-1">Point of Sale Login</p>
                            <p class="text-[11px] font-bold text-slate-400 mt-2 flex items-center gap-1.5"><i data-lucide="map-pin" class="w-3.5 h-3.5"></i> MALL COUNTER B</p>
                            <p class="text-[10px] font-mono font-bold text-slate-400 mt-1">IP: 192.168.1.102</p>
                        </div>
                    </div>
                    <!-- Log 3 -->
                    <div class="relative">
                        <span class="absolute -left-[35px] top-1 w-4 h-4 rounded-full bg-slate-200 border-4 border-white shadow-sm"></span>
                        <div class="bg-slate-50 border border-slate-100 p-4 rounded-2xl hover:border-slate-200 transition-colors">
                            <div class="flex justify-between items-start mb-1">
                                <p class="text-[15px] font-extrabold text-slate-900">Vendor Admin</p>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Yesterday, 07:15 PM</span>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-500 mt-1">Remote Web Access</p>
                            <p class="text-[11px] font-bold text-rose-500 mt-2 flex items-center gap-1.5"><i data-lucide="globe" class="w-3.5 h-3.5"></i> EXTERNAL (REMOTE)</p>
                            <p class="text-[10px] font-mono font-bold text-slate-400 mt-1">IP: 102.44.12.8</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 10. GENERAL SETTINGS TAB -->
            <div id="panel-general" class="settings-panel hidden bg-white rounded-[2.5rem] p-8 lg:p-10 border border-slate-100 card-shadow transition-all duration-300">
                <div class="flex items-center gap-4 mb-10">
                    <div class="w-12 h-12 bg-slate-100 text-slate-600 rounded-2xl flex items-center justify-center shrink-0 border border-slate-200">
                        <i data-lucide="settings" class="w-6 h-6"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-extrabold text-slate-900">General Settings</h2>
                        <p class="text-xs font-semibold text-slate-500 mt-1">Global logic mapping, currencies, and notification channels</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    <!-- Localization Column -->
                    <div>
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-5 border-b border-slate-100 pb-3">Localization Array</h3>
                        <div class="space-y-6">
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Display Language</label>
                                <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                                    <option>English (Universal)</option>
                                    <option>Hindi (Local)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2">Primary Currency</label>
                                <select class="w-full bg-slate-50 border border-slate-200 rounded-xl px-5 py-3.5 text-sm font-bold text-slate-900 outline-none focus:ring-2 focus:ring-indigo-500 appearance-none">
                                    <option>Indian Rupee (INR)</option>
                                    <option>US Dollar (USD)</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Notification Channels Column -->
                    <div>
                        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-5 border-b border-slate-100 pb-3">Notification Logic</h3>
                        <div class="space-y-5">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Critical Stock SMS</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-1">Notify manager immediately on low inventory drops.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Daily E-Reports</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-1">Broadcast PDF digest summaries sequentially to emails.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer" checked>
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                            <div class="flex items-start justify-between opacity-70">
                                <div>
                                    <p class="text-sm font-extrabold text-slate-900">Sound Alerts (POS)</p>
                                    <p class="text-[11px] font-medium text-slate-500 mt-1">Fire audible transaction clicks at terminals.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" class="sr-only peer">
                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    function switchTab(tabId) {
        // Reset sidebar buttons active styles
        document.querySelectorAll('.settings-tab').forEach(btn => {
            btn.classList.remove('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-600/20');
            btn.classList.add('text-slate-400', 'hover:text-white', 'hover:bg-white/5');
            // Reset active-tab marker class
            btn.classList.remove('active-tab');
        });
        
        // Hide all main panels gracefully
        document.querySelectorAll('.settings-panel').forEach(panel => {
            panel.classList.add('hidden');
        });
        
        // Apply active accent style to clicked Tab
        const targetBtn = document.getElementById('tab-' + tabId);
        if(targetBtn){
            targetBtn.classList.remove('text-slate-400', 'hover:text-white', 'hover:bg-white/5');
            targetBtn.classList.add('bg-indigo-600', 'text-white', 'shadow-lg', 'shadow-indigo-600/20', 'active-tab');
        }
        
        // Reveal target main panel
        const targetPanel = document.getElementById('panel-' + tabId);
        if(targetPanel){
            targetPanel.classList.remove('hidden');
        }

        // Keep URL tidy for deep linking
        window.history.replaceState(null, null, '#' + tabId);
    }
    
    // Auto-mount active hash on load
    document.addEventListener('DOMContentLoaded', () => {
        if(window.location.hash) {
            const hash = window.location.hash.substring(1);
            if(document.getElementById('tab-' + hash)) {
                switchTab(hash);
            }
        }
    });

    // Support for server-side validation failure reloading (if URL hash is lost)
    @if($errors->has('current_password') || $errors->has('new_password'))
        document.addEventListener('DOMContentLoaded', () => switchTab('password'));
    @endif
</script>
@endsection
