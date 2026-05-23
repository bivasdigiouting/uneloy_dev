@extends('vendor.layout')

@section('title', $title ?? 'Vendor')

@section('content')
    <div class="bg-white p-10 rounded-[3rem] border border-slate-100 card-shadow">
        <div class="flex items-center justify-between gap-6 flex-wrap">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Vendor Portal</p>
                <h2 class="text-3xl font-extrabold text-slate-900 mt-2">{{ $title ?? 'Page' }}</h2>
                <p class="text-sm text-slate-500 font-medium mt-2">This section is available in the new vendor UI. Backend features can be integrated next.</p>
            </div>
            <a href="{{ route('vendor.dashboard') }}" class="bg-slate-900 text-white px-6 py-3.5 rounded-2xl font-bold shadow-xl shadow-slate-900/20 flex items-center space-x-3 transition-all active:scale-95 hover:bg-indigo-600 group">
                <i data-lucide="arrow-left" class="w-5 h-5"></i>
                <span>Back to Dashboard</span>
            </a>
        </div>
    </div>
@endsection
