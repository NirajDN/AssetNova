@extends('layouts.app')

@section('content')
<!-- Flash messages -->
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl font-medium text-sm">
    <span class="material-symbols-outlined text-green-600">check_circle</span>
    {{ session('success') }}
</div>
@endif

<!-- Header Section -->
<div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-10">
    <div>
        <h1 class="text-4xl font-extrabold tracking-tight text-primary mb-2">Transactions Log</h1>
        <p class="text-on-surface-variant max-w-2xl">Real-time ledger of industrial asset movements. Monitor stock ingress, egress, and personnel accountability.</p>
    </div>
    <a href="{{ route('transactions.create') }}" class="flex items-center gap-2 px-5 py-2.5 bg-primary text-white font-semibold rounded-lg shadow-lg transition-all text-sm hover:opacity-90">
        <span class="material-symbols-outlined">add</span>
        New Transaction
    </a>
</div>

<!-- Filter & Metrics Grid -->
<form method="GET" action="{{ route('transactions.index') }}" id="filterForm">
<div class="grid grid-cols-12 gap-6 mb-8">
    <!-- Filter Panel -->
    <div class="col-span-12 lg:col-span-9 p-6 bg-surface-container rounded-xl space-y-4">
        <!-- Row 1: Date Range + Movement Type -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-on-surface-variant mb-2">Date Range</label>
                <div class="flex items-center gap-2 bg-surface-container-lowest p-2 rounded-lg">
                    <input name="date_from" class="flex-1 bg-transparent border-none text-sm focus:ring-0 min-w-0" type="date" value="{{ request('date_from') }}"/>
                    <span class="text-outline-variant text-xs shrink-0">to</span>
                    <input name="date_to" class="flex-1 bg-transparent border-none text-sm focus:ring-0 min-w-0" type="date" value="{{ request('date_to') }}"/>
                </div>
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase tracking-wider text-on-surface-variant mb-2">Movement Type</label>
                <div class="flex gap-2 flex-wrap">
                    <a href="{{ route('transactions.index', array_merge(request()->except('type','page'), [])) }}"
                       class="px-4 py-2 rounded-lg text-xs font-bold {{ !request('type') ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-white' }} transition-all">All</a>
                    <a href="{{ route('transactions.index', array_merge(request()->except('page'), ['type' => 'in'])) }}"
                       class="px-4 py-2 rounded-lg text-xs font-bold {{ request('type') === 'in' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-white' }} transition-all">Stock In</a>
                    <a href="{{ route('transactions.index', array_merge(request()->except('page'), ['type' => 'out'])) }}"
                       class="px-4 py-2 rounded-lg text-xs font-bold {{ request('type') === 'out' ? 'bg-primary text-white' : 'bg-surface-container-lowest text-on-surface-variant hover:bg-white' }} transition-all">Stock Out</a>
                </div>
            </div>
        </div>
        <!-- Row 2: Search + Actions -->
        <div class="flex flex-wrap items-end gap-3">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-bold uppercase tracking-wider text-on-surface-variant mb-2">Search Part / SKU</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
                    <input name="search" class="w-full bg-surface-container-lowest border-none rounded-lg py-2 pl-9 pr-4 text-sm focus:ring-2 focus:ring-primary/20" placeholder="e.g. Valve, AX-902..." value="{{ request('search') }}"/>
                </div>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg text-xs font-bold hover:opacity-90 transition-all flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">filter_alt</span> Apply
                </button>
                <a href="{{ route('transactions.index') }}" class="px-4 py-2.5 bg-surface-container-highest text-primary rounded-lg text-xs font-bold hover:bg-surface-variant transition-all">Clear</a>
            </div>
        </div>
    </div>
    <!-- Quick Stats -->
    <div class="col-span-12 lg:col-span-3 grid grid-cols-2 gap-4">
        <div class="bg-secondary-container/30 p-4 rounded-xl flex flex-col justify-center">
            <span class="text-[10px] font-bold text-on-secondary-container uppercase">Total In</span>
            <span class="text-2xl font-extrabold text-primary">{{ number_format($totalIn) }}</span>
            <span class="text-[10px] text-on-surface-variant">units received</span>
        </div>
        <div class="bg-error-container/20 p-4 rounded-xl flex flex-col justify-center">
            <span class="text-[10px] font-bold text-on-error-container uppercase">Total Out</span>
            <span class="text-2xl font-extrabold text-error">{{ number_format($totalOut) }}</span>
            <span class="text-[10px] text-on-surface-variant">units dispatched</span>
        </div>
    </div>
</div>
</form>

<!-- Transactions Table -->
<div class="bg-surface-container-lowest rounded-xl shadow-sm overflow-hidden">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-surface-container-low border-b border-outline-variant/10">
                <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Date &amp; Time</th>
                <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Part Name / SKU</th>
                <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Type</th>
                <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Quantity</th>
                <th class="px-6 py-4 text-[11px] font-bold text-on-surface-variant uppercase tracking-widest">Notes</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-outline-variant/5">
            @forelse($transactions as $transaction)
            <tr class="hover:bg-surface-container-low/50 transition-colors group">
                <td class="px-6 py-5">
                    <div class="text-sm font-semibold text-primary">{{ $transaction->created_at->format('M d, Y') }}</div>
                    <div class="text-[10px] text-on-surface-variant">{{ $transaction->created_at->format('h:i A') }}</div>
                </td>
                <td class="px-6 py-5">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary">settings_input_component</span>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-on-surface">{{ $transaction->part->name ?? 'Unknown Part' }}</div>
                            <div class="text-[10px] font-mono text-on-surface-variant">SKU: {{ $transaction->part->sku ?? 'N/A' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5">
                    @if($transaction->type === 'in')
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-secondary-fixed text-on-secondary-fixed text-[10px] font-extrabold uppercase">
                        <span class="material-symbols-outlined text-xs">south_east</span> Stock In
                    </span>
                    @else
                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-error-container text-on-error-container text-[10px] font-extrabold uppercase">
                        <span class="material-symbols-outlined text-xs">north_east</span> Stock Out
                    </span>
                    @endif
                </td>
                <td class="px-6 py-5">
                    @if($transaction->type === 'in')
                    <div class="text-sm font-black text-primary">+{{ number_format($transaction->quantity) }} <span class="text-[10px] font-normal text-on-surface-variant">Units</span></div>
                    @else
                    <div class="text-sm font-black text-error">-{{ number_format($transaction->quantity) }} <span class="text-[10px] font-normal text-on-surface-variant">Units</span></div>
                    @endif
                </td>
                <td class="px-6 py-5 text-xs text-on-surface-variant max-w-[200px] truncate">
                    {{ $transaction->notes ?? '—' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-14 text-center">
                    <span class="material-symbols-outlined text-4xl text-on-surface-variant block mb-2">receipt_long</span>
                    <p class="text-slate-500 font-medium">No transactions match your filters.</p>
                    <a href="{{ route('transactions.index') }}" class="text-primary text-xs font-bold mt-1 inline-block hover:underline">Clear filters</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($transactions->hasPages())
    <div class="bg-surface-container-low px-8 py-4 flex items-center justify-between">
        <span class="text-xs text-on-surface-variant">Showing {{ $transactions->firstItem() }}–{{ $transactions->lastItem() }} of {{ $transactions->total() }} transactions</span>
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
