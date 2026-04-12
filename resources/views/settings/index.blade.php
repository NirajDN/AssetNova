@extends('layouts.app')

@section('content')
<div class="space-y-8 max-w-4xl">

    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl font-medium text-sm">
        <span class="material-symbols-outlined text-green-600">check_circle</span>{{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <div>
        <h2 class="text-3xl font-black text-primary tracking-tight">Settings</h2>
        <p class="text-on-surface-variant mt-1">System configuration and inventory overview for AssetNova.</p>
    </div>

    <!-- System Stats -->
    <section class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant/10 shadow-sm">
        <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant mb-6 pb-3 border-b border-outline-variant/15">System Overview</h3>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="text-center p-4 bg-surface-container rounded-xl">
                <span class="material-symbols-outlined text-primary text-2xl block mb-1">inventory_2</span>
                <p class="text-2xl font-black text-primary">{{ $stats['parts'] }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mt-0.5">Parts</p>
            </div>
            <div class="text-center p-4 bg-surface-container rounded-xl">
                <span class="material-symbols-outlined text-primary text-2xl block mb-1">conveyor_belt</span>
                <p class="text-2xl font-black text-primary">{{ $stats['suppliers'] }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mt-0.5">Suppliers</p>
            </div>
            <div class="text-center p-4 bg-surface-container rounded-xl">
                <span class="material-symbols-outlined text-primary text-2xl block mb-1">category</span>
                <p class="text-2xl font-black text-primary">{{ $stats['categories'] }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mt-0.5">Categories</p>
            </div>
            <div class="text-center p-4 bg-surface-container rounded-xl">
                <span class="material-symbols-outlined text-primary text-2xl block mb-1">swap_horiz</span>
                <p class="text-2xl font-black text-primary">{{ $stats['transactions'] }}</p>
                <p class="text-[10px] text-on-surface-variant uppercase tracking-wider font-bold mt-0.5">Transactions</p>
            </div>
            <div class="text-center p-4 bg-gradient-to-br from-primary to-primary-container rounded-xl">
                <span class="material-symbols-outlined text-white text-2xl block mb-1">account_balance</span>
                <p class="text-xl font-black text-white">₹{{ number_format($stats['total_value'], 0) }}</p>
                <p class="text-[10px] text-primary-fixed-dim uppercase tracking-wider font-bold mt-0.5">Total Value</p>
            </div>
        </div>
    </section>

    <!-- General Settings Form -->
    <form method="POST" action="{{ route('settings.update') }}" class="space-y-6">
        @csrf

        <!-- Company Info -->
        <section class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant/10 shadow-sm space-y-5">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/15">Company Information</h3>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Company Name</label>
                    <input name="company_name" value="{{ auth()->user()->company->name ?? '' }}" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">GST Number</label>
                    <input name="gst" placeholder="e.g. 27AAPFU0939F1ZV" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Contact Email</label>
                    <input name="email" type="email" value="{{ auth()->user()->email }}" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Phone</label>
                    <input name="phone" placeholder="+91 98765 43210" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Warehouse Address</label>
                <textarea name="address" rows="2" placeholder="Facility address..." class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
            </div>
        </section>

        <!-- Inventory Settings -->
        <section class="bg-surface-container-lowest rounded-2xl p-8 border border-outline-variant/10 shadow-sm space-y-5">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/15">Inventory Preferences</h3>
            <div class="grid grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Currency Symbol</label>
                    <input name="currency" value="₹" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Low Stock Alert Multiplier</label>
                    <input name="low_stock_multiplier" value="1" type="number" min="1" max="5" step="0.5" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                    <p class="text-[11px] text-on-surface-variant mt-1">Alert when stock ≤ threshold × multiplier</p>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Timezone</label>
                    <input name="timezone" value="Asia/Kolkata (IST)" readonly class="w-full bg-surface-container-low/50 border-none rounded-xl py-3 px-4 text-sm text-on-surface-variant cursor-not-allowed"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Financial Year Start</label>
                    <select name="fy_start" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                        <option selected>April (Indian FY)</option>
                        <option>January</option>
                    </select>
                </div>
            </div>
        </section>

        <!-- About -->
        <section class="bg-surface-container-lowest rounded-2xl p-6 border border-outline-variant/10 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-xs font-black uppercase tracking-widest text-on-surface-variant mb-1">Application</p>
                <p class="font-bold text-primary">AssetNova — Digital Foreman</p>
                <p class="text-xs text-on-surface-variant mt-0.5">Version 1.0.0 · Laravel · MySQL · India</p>
            </div>
                <img src="/images/assetnova-logo.png" alt="AssetNova" class="w-14 h-14 object-contain opacity-60"/>
        </section>

        <div class="flex justify-end">
            <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span> Save Settings
            </button>
        </div>
    </form>
</div>
@endsection
