<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Portal - Login</title>
    <link rel="icon" type="image/x-icon" href="{{ $settings && $settings->estore_app_favicon ? asset('storage/'.$settings->estore_app_favicon) : ($settings && $settings->favicon ? asset('storage/'.$settings->favicon) : asset('frontend-assets/design_img/favicon.ico')) }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            background-image:
                radial-gradient(at 0% 0%, rgba(99, 102, 241, 0.08) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(168, 85, 247, 0.08) 0px, transparent 50%);
        }
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.06), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body>
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-xl">
            <div class="bg-white rounded-[3rem] border border-slate-100 card-shadow overflow-hidden">
                <div class="p-10 bg-gradient-to-br from-indigo-600 to-violet-700 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-widest text-white/80">Vendor Portal</p>
                            <h1 class="text-3xl font-extrabold tracking-tight mt-2">E-Service Mall</h1>
                            <p class="text-sm font-medium text-white/80 mt-2">Access your vendor dashboard</p>
                        </div>
                        <div class="w-14 h-14 rounded-3xl bg-white/15 flex items-center justify-center text-2xl font-extrabold">
                            V
                        </div>
                    </div>
                </div>

                <div class="p-10">
                    @if(session('success'))
                        <div class="mb-6 rounded-2xl border border-emerald-100 bg-emerald-50 px-6 py-4 text-emerald-900">
                            <div class="font-semibold">{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 rounded-2xl border border-rose-100 bg-rose-50 px-6 py-4 text-rose-900">
                            <div class="font-semibold">{{ session('error') }}</div>
                        </div>
                    @endif

                    <div class="mb-8 rounded-2xl border border-slate-100 bg-slate-50 px-6 py-4 text-slate-700 text-sm font-semibold">
                        Use your registered email and password. A 6-digit OTP will be sent to your registered email for verification.
                    </div>

                    <form method="POST" action="{{ route('vendor.login') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="email">Email Address</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autocomplete="email"
                                autofocus
                                class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 text-sm font-semibold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 @error('email') border-rose-400 @enderror"
                                placeholder="name@example.com"
                            >
                            @error('email')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2" for="password">Password</label>
                            <div class="relative">
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    required
                                    autocomplete="current-password"
                                    class="w-full rounded-2xl border border-slate-200 bg-white px-5 py-4 pr-12 text-sm font-semibold text-slate-900 outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 @error('password') border-rose-400 @enderror"
                                    placeholder="Your password"
                                >
                                <button type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600" onclick="togglePassword()">
                                    <span id="toggleLabel" class="text-xs font-bold uppercase tracking-widest">Show</span>
                                </button>
                            </div>
                            @error('password')<p class="mt-2 text-sm font-semibold text-rose-600">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="w-full bg-slate-900 text-white px-8 py-4 rounded-2xl font-extrabold shadow-xl shadow-slate-900/20 hover:bg-indigo-600 transition-all active:scale-95">
                            Login to Portal
                        </button>
                    </form>

                    <div class="mt-10 text-center">
                        <a href="{{ url('/') }}" class="text-sm font-bold text-indigo-600 hover:text-slate-900 transition-colors">
                            Back to Homepage
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleLabel = document.getElementById('toggleLabel');
            if (!passwordInput || !toggleLabel) return;
            const isPassword = passwordInput.type === 'password';
            passwordInput.type = isPassword ? 'text' : 'password';
            toggleLabel.textContent = isPassword ? 'Hide' : 'Show';
        }
    </script>
</body>
</html>
