@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <!-- Header -->
    <div class="mb-10">
        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-1 text-primary font-bold text-xs mb-4 hover:underline">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back to Tower
        </a>
        <h2 class="text-3xl font-extrabold tracking-tighter text-primary">Register New Enterprise</h2>
        <p class="text-on-surface-variant font-medium tracking-wide">Onboard a new client into the AssetNova ecosystem.</p>
    </div>

    <form method="POST" action="{{ route('super-admin.companies.store') }}" class="space-y-6">
        @csrf
        
        <!-- Company Section -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-outline-variant/10">
            <h3 class="text-lg font-bold text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-700">business</span>
                Company Details
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase mb-2">Enterprise Name</label>
                    <input type="text" name="name" required placeholder="e.g. Tata Motors"
                           class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase mb-2">Industry Sector</label>
                    <select name="industry" class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                        <option>Automotive Manufacturing</option>
                        <option>Aerospace & Defense</option>
                        <option>Mining & Heavy Equipment</option>
                        <option>Oil & Gas</option>
                        <option>Logistics & Supply Chain</option>
                        <option>Infrastructure & Construction</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Admin Section -->
        <div class="bg-white p-8 rounded-2xl shadow-sm border border-outline-variant/10">
            <h3 class="text-lg font-bold text-primary mb-6 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-700">admin_panel_settings</span>
                Initial Admin Account
            </h3>
            
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase mb-2">Full Name</label>
                    <input type="text" name="admin_name" required placeholder="e.g. Rajesh Kumar"
                           class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase mb-2">Corporate Email</label>
                    <input type="email" name="admin_email" required placeholder="r.kumar@industry.com"
                           class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-on-surface-variant uppercase mb-2">Temporary Password</label>
                    <input type="password" name="admin_password" required placeholder="Min 8 characters"
                           class="w-full bg-surface-container-low border-none rounded-lg py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                    <p class="mt-2 text-[10px] text-on-surface-variant italic">User will be prompted to change this upon first login.</p>
                </div>
            </div>
        </div>

        <button type="submit" class="w-full bg-primary text-white py-4 rounded-xl font-black text-sm shadow-xl shadow-primary/20 hover:scale-[1.01] active:scale-95 transition-all flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">rocket_launch</span>
            Launch Enterprise Instance
        </button>
    </form>
</div>
@endsection
