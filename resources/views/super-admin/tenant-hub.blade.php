@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <!-- Breadcrumb & Header -->
    <div class="mb-10">
        <a href="{{ route('super-admin.dashboard') }}" class="flex items-center gap-1 text-primary font-bold text-xs mb-4 hover:underline">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            Back to Tower
        </a>
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div class="flex items-center gap-6">
                <div class="w-20 h-20 rounded-2xl bg-white p-3 shadow-sm border border-outline-variant/10 flex items-center justify-center">
                    <img src="{{ $company->logo_url ?: '/images/assetnova-logo.png' }}" alt="{{ $company->name }}" class="w-full h-full object-contain">
                </div>
                <div>
                    <h2 class="text-4xl font-extrabold tracking-tighter text-primary">{{ $company->name }} Hub</h2>
                    <p class="text-on-surface-variant font-medium tracking-wide italic">Sector: {{ $company->industry }}</p>
                </div>
            </div>
            
            @if($admin)
            <a href="{{ route('super-admin.impersonate', $admin->id) }}" class="flex items-center gap-2 bg-blue-700 text-white px-8 py-4 rounded-xl font-black text-sm shadow-xl shadow-blue-700/20 hover:scale-[1.05] active:scale-95 transition-all">
                <span class="material-symbols-outlined">auto_fix_high</span>
                Log in as {{ explode(' ', $admin->name)[0] }}
            </a>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar: Client Profile -->
        <div class="lg:col-span-1 space-y-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-outline-variant/10">
                <h3 class="text-xs font-black text-on-surface-variant uppercase tracking-widest mb-6">Enterprise Profile</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase">Primary Administrator</p>
                        <p class="font-bold text-primary">{{ $admin ? $admin->name : 'No Admin Assigned' }}</p>
                        <p class="text-xs text-on-surface-variant">{{ $admin ? $admin->email : 'N/A' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase">Suppliers</p>
                            <p class="text-xl font-black text-primary">{{ $company->suppliers_count }}</p>
                        </div>
                        <div>
                            <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase">Stock Items</p>
                            <p class="text-xl font-black text-primary">{{ $company->parts_count }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[9px] font-bold text-on-surface-variant/50 uppercase">Onboarding Date</p>
                        <p class="text-xs font-bold text-primary">{{ $company->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-primary/5 p-6 rounded-2xl border border-primary/10">
                <h4 class="font-bold text-primary mb-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-sm">info</span>
                    Support Note
                </h4>
                <p class="text-xs text-primary/70 leading-relaxed">
                    This tenant is currently operating on the <strong>Premium Fleet Tier</strong>. All data displayed here is live and reflects the current state of their industrial inventory.
                </p>
            </div>
        </div>

        <!-- Main Content: Data Snapshot -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Latest Parts -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="p-6 border-b border-outline-variant/5 flex justify-between items-center">
                    <h3 class="font-bold text-primary">Critical Inventory Snapshot</h3>
                    <span class="text-[10px] font-bold text-on-surface-variant uppercase">Recent Additions</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-surface-container-low text-on-surface-variant text-[10px] uppercase font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-3">Part Details</th>
                                <th class="px-6 py-3">Current Stock</th>
                                <th class="px-6 py-3 text-right">Valuation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant/5">
                            @forelse($topParts as $part)
                            <tr class="hover:bg-surface-container-lowest transition-colors">
                                <td class="px-6 py-4">
                                    <p class="font-bold text-primary">{{ $part->name }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ $part->sku }}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-1 rounded bg-surface-container text-primary font-bold text-[10px]">
                                        {{ $part->stock_quantity }} Units
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-mono font-bold text-primary">
                                    @indianNumber($part->cost)
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-6 py-10 text-center text-on-surface-variant italic">No parts found for this tenant yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-outline-variant/10 overflow-hidden">
                <div class="p-6 border-b border-outline-variant/5">
                    <h3 class="font-bold text-primary">Live Transaction Stream</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-6">
                        @forelse($recentTransactions as $tx)
                        <div class="flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full {{ $tx->type == 'in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} flex items-center justify-center flex-shrink-0">
                                    <span class="material-symbols-outlined text-lg">{{ $tx->type == 'in' ? 'south_west' : 'north_east' }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-primary">{{ $tx->part->name }}</p>
                                    <p class="text-[10px] text-on-surface-variant">{{ $tx->created_at->diffForHumans() }} • {{ $tx->notes ?: 'No notes' }}</p>
                                </div>
                            </div>
                            <p class="font-black {{ $tx->type == 'in' ? 'text-green-600' : 'text-red-600' }}">
                                {{ $tx->type == 'in' ? '+' : '-' }}{{ $tx->quantity }}
                            </p>
                        </div>
                        @empty
                        <p class="text-center text-on-surface-variant italic py-4">No recent activity detected.</p>
                        @endforelse
                    </div>
                </div>
                <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/5 text-center">
                    <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">End of Tenant Stream</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
