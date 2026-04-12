@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Platform Pulse Header -->
    <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-extrabold tracking-tighter text-primary mb-2">AssetNova Control Tower</h2>
            <p class="text-on-surface-variant font-medium tracking-wide">Managing <span class="text-primary font-bold">{{ $companies->count() }} Client Ecosystems</span> across the global network.</p>
        </div>
        <a href="{{ route('super-admin.companies.create') }}" class="flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-lg font-bold text-sm shadow-xl shadow-primary/20 hover:scale-[1.05] active:scale-95 transition-all">
            <span class="material-symbols-outlined text-sm">add_business</span>
            Register New Enterprise
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg font-bold text-sm flex items-center gap-2 animate-bounce">
            <span class="material-symbols-outlined">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <!-- Enterprise Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($companies as $company)
        <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden flex flex-col hover:shadow-xl transition-all duration-500 group">
            <!-- Header -->
            <div class="p-6 border-b border-outline-variant/5 bg-surface-container-low flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-white p-2 shadow-sm flex items-center justify-center">
                    <img src="{{ $company->logo_url ?: '/images/assetnova-logo.png' }}" alt="{{ $company->name }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <h3 class="font-bold text-primary group-hover:text-blue-700 transition-colors">{{ $company->name }}</h3>
                    <p class="text-[10px] uppercase font-bold text-on-surface-variant tracking-widest">{{ $company->industry }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="p-6 grid grid-cols-2 gap-4 flex-1">
                <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/5">
                    <p class="text-[9px] font-black text-on-surface-variant uppercase mb-1">Total Assets</p>
                    <p class="text-2xl font-black text-primary">{{ $company->parts_count }}</p>
                </div>
                <div class="bg-surface-container-lowest p-4 rounded-xl border border-outline-variant/5">
                    <p class="text-[9px] font-black text-on-surface-variant uppercase mb-1">Active Users</p>
                    <p class="text-2xl font-black text-primary">{{ $company->users_count }}</p>
                </div>
            </div>

            <!-- Footer Action -->
            <div class="px-6 py-4 bg-surface-container-lowest border-t border-outline-variant/5 flex justify-between items-center">
                <span class="text-[10px] font-bold text-on-surface-variant italic">Tenant ID: #{{ substr(md5($company->id), 0, 8) }}</span>
                <button class="text-xs font-bold text-primary hover:underline flex items-center gap-1">
                    Manage Tenant
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
