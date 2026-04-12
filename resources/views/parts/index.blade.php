@extends('layouts.app')

@section('content')
<!-- Flash Messages -->
@if(session('success'))
<div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl font-medium text-sm">
    <span class="material-symbols-outlined text-green-600">check_circle</span>
    {{ session('success') }}
</div>
@endif

<!-- Header -->
<section class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-3xl font-black text-on-surface -tracking-wider">Parts Directory</h2>
        <p class="text-on-surface-variant mt-1">Real-time inventory orchestration across the facility.</p>
    </div>
    <!-- Stats pushed to right extreme -->
    <div class="flex gap-3 ml-auto">
        <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm min-w-[120px] text-right">
            <p class="text-[10px] uppercase font-bold tracking-widest text-slate-500">Total Items</p>
            <p class="text-2xl font-black text-primary mt-1">{{ $totalParts }}</p>
        </div>
        <div class="bg-surface-container-lowest p-4 rounded-xl shadow-sm min-w-[120px] border-r-4 border-error text-right">
            <p class="text-[10px] uppercase font-bold tracking-widest text-slate-500">Critical Low</p>
            <p class="text-2xl font-black text-error mt-1">{{ $criticalLow }}</p>
        </div>
    </div>
</section>

<!-- Inventory Valuation Panel -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Asset Value -->
    <div class="md:col-span-2 bg-gradient-to-br from-primary to-primary-container text-white p-6 rounded-2xl shadow-md relative overflow-hidden">
        <div class="absolute -right-6 -bottom-6 opacity-10">
            <span class="material-symbols-outlined text-[8rem]">account_balance</span>
        </div>
        <p class="text-[10px] font-black uppercase tracking-widest text-primary-fixed-dim mb-1">Total Inventory Value</p>
        <p class="text-4xl font-black tracking-tight">₹{{ number_format($totalValue, 0) }}</p>
        <p class="text-xs text-primary-fixed-dim mt-2">Across {{ $totalParts }} parts in stock</p>
        <div class="mt-4 pt-4 border-t border-white/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-sm text-primary-fixed-dim">trending_up</span>
            <p class="text-xs text-primary-fixed-dim">
                Projected 12-month value: <span class="font-black text-white">₹{{ number_format($projectedValue, 0) }}</span>
                <span class="ml-1 text-[10px] bg-white/20 px-1.5 py-0.5 rounded font-bold">+9.5%</span>
            </p>
        </div>
    </div>

    <!-- Highest Value Asset -->
    <div class="bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-outline-variant/15">
        <div class="flex items-center gap-2 mb-3">
            <span class="p-2 bg-tertiary-fixed rounded-lg material-symbols-outlined text-tertiary-container text-sm">emoji_events</span>
            <p class="text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Top Asset</p>
        </div>
        @if($highestValuePart)
        <p class="font-bold text-primary text-sm leading-tight">{{ $highestValuePart->name }}</p>
        <p class="text-[10px] text-on-surface-variant font-mono mt-0.5">{{ $highestValuePart->sku }}</p>
        <p class="text-xl font-black text-on-surface mt-2">
            ₹{{ number_format($highestValuePart->stock_quantity * $highestValuePart->cost, 0) }}
        </p>
        <p class="text-[10px] text-on-surface-variant mt-1">
            {{ number_format($highestValuePart->stock_quantity) }} units × ₹{{ number_format($highestValuePart->cost, 0) }}
        </p>
        @endif
    </div>

    <!-- Capital at Risk -->
    <div class="bg-error-container/20 p-6 rounded-2xl border border-error/15 relative overflow-hidden">
        <div class="absolute -right-4 -bottom-4 opacity-10">
            <span class="material-symbols-outlined text-[6rem] text-error">warning</span>
        </div>
        <div class="flex items-center gap-2 mb-3">
            <span class="p-2 bg-error-container rounded-lg material-symbols-outlined text-error text-sm">shield_locked</span>
            <p class="text-[10px] font-black uppercase tracking-widest text-error">Capital at Risk</p>
        </div>
        <p class="text-xl font-black text-error">₹{{ number_format($valueAtRisk, 0) }}</p>
        <p class="text-[10px] text-on-surface-variant mt-1 leading-relaxed">
            Tied up in {{ $criticalLow }} critical-stock {{ Str::plural('part', $criticalLow) }}. Reorder recommended.
        </p>
    </div>
</div>

<!-- Filters -->
<div class="flex justify-end mb-2">
    <a href="{{ route('parts.create') }}" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all">
        <span class="material-symbols-outlined text-sm">add</span> Add Part
    </a>
</div>
<form method="GET" action="{{ route('parts.index') }}" class="bg-surface-container-lowest p-5 rounded-xl shadow-sm mb-6 flex flex-wrap gap-4 items-end border border-outline-variant/10">
    <div class="flex-1 min-w-[200px]">
        <label class="block text-[10px] uppercase font-bold tracking-widest text-on-surface-variant mb-2">Search</label>
        <div class="relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
            <input name="search" value="{{ request('search') }}" placeholder="Name, SKU, or location..." class="w-full bg-surface-container-low border-none rounded-lg py-2 pl-9 pr-4 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>
    </div>
    <div class="min-w-[180px]">
        <label class="block text-[10px] uppercase font-bold tracking-widest text-on-surface-variant mb-2">Category</label>
        <select name="category_id" class="w-full bg-surface-container-low border-none rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-primary/20">
            <option value="">All Categories</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="min-w-[160px]">
        <label class="block text-[10px] uppercase font-bold tracking-widest text-on-surface-variant mb-2">Stock Status</label>
        <select name="stock_status" class="w-full bg-surface-container-low border-none rounded-lg py-2 px-3 text-sm focus:ring-2 focus:ring-primary/20">
            <option value="">All</option>
            <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>Low Stock Only</option>
        </select>
    </div>
    <div class="flex gap-2">
        <button type="submit" class="px-4 py-2.5 bg-primary text-white rounded-lg text-xs font-bold flex items-center gap-1 hover:opacity-90">
            <span class="material-symbols-outlined text-sm">filter_alt</span> Filter
        </button>
        <a href="{{ route('parts.index') }}" class="px-4 py-2.5 bg-surface-container text-on-surface-variant rounded-lg text-xs font-bold hover:bg-surface-container-high transition-all">Clear</a>
    </div>
</form>

<!-- Table -->
<div class="bg-surface-container-lowest rounded-2xl overflow-hidden shadow-sm border border-outline-variant/10">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low">
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Part Identification</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Category</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Status</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Stock</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Unit Price</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Location</th>
                    <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-slate-500">Total Value</th>
                    <th class="px-4 py-4 w-px whitespace-nowrap"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-surface-container">
                @forelse($parts as $part)
                @php $isLow = $part->stock_quantity <= $part->min_threshold; @endphp
                <tr class="group hover:bg-surface-container-low transition-colors duration-150">
                    <td class="px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-lg overflow-hidden bg-surface-container flex-shrink-0 border border-outline-variant/10">
                                @if($part->image_url)
                                    <img src="{{ asset($part->image_url) }}" alt="{{ $part->name }}" class="w-full h-full object-cover object-center"/>
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined">settings_input_component</span>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-bold text-on-surface">{{ $part->name }}</p>
                                <p class="text-xs text-slate-500 font-mono">{{ $part->sku }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-5">
                        <span class="px-2.5 py-1 rounded-full bg-secondary-container text-on-secondary-container text-[10px] font-bold uppercase tracking-wide">
                            {{ $part->category->name ?? 'Uncategorized' }}
                        </span>
                    </td>
                    <td class="px-6 py-5">
                        @if($isLow)
                        <div class="flex items-center gap-1.5 text-error font-bold text-xs uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-error animate-pulse"></span>
                            Critical
                        </div>
                        @else
                        <div class="flex items-center gap-1.5 text-green-700 font-bold text-xs uppercase">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            Stable
                        </div>
                        @endif
                    </td>
                    <td class="px-6 py-5">
                        @php $pct = $part->min_threshold > 0 ? min(100, ($part->stock_quantity / ($part->min_threshold * 3)) * 100) : 100; @endphp
                        <div class="space-y-1 min-w-[120px]">
                            <p class="text-xs font-bold {{ $isLow ? 'text-error' : 'text-on-surface' }}">{{ number_format($part->stock_quantity) }} units</p>
                            <div class="w-full h-1.5 bg-surface-container rounded-full">
                                <div class="h-full {{ $isLow ? 'bg-error' : 'bg-primary' }} rounded-full" style="width: {{ $pct }}%"></div>
                            </div>
                            <p class="text-[9px] text-slate-400">Min: {{ $part->min_threshold }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-5 font-mono font-bold text-on-surface text-sm">
                        ₹{{ number_format($part->cost, 2) }}
                    </td>
                    <td class="px-6 py-5 text-xs text-on-surface-variant">{{ $part->location ?? '—' }}</td>
                    <!-- Total Value = stock × unit cost -->
                    <td class="px-6 py-5">
                        @php $totalVal = $part->stock_quantity * $part->cost; @endphp
                        <div class="min-w-[110px]">
                            <p class="font-black text-sm {{ $part->stock_quantity <= $part->min_threshold ? 'text-error' : 'text-primary' }}">₹{{ number_format($totalVal, 0) }}</p>
                            <p class="text-[10px] text-slate-400 mt-0.5">{{ number_format($part->stock_quantity) }} × ₹{{ number_format($part->cost, 0) }}</p>
                        </div>
                    </td>
                    <td class="px-4 py-5 w-px whitespace-nowrap">
                        <div class="flex items-center gap-1">
                            <a href="{{ route('parts.edit', $part) }}" class="p-1.5 rounded-lg hover:bg-surface-container text-slate-500 hover:text-primary transition-all" title="Edit">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            <form action="{{ route('parts.destroy', $part) }}" method="POST" onsubmit="return confirm('Delete {{ $part->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="p-1.5 rounded-lg hover:bg-error-container text-slate-500 hover:text-error transition-all" title="Delete">
                                    <span class="material-symbols-outlined text-sm">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-14 text-center">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant block mb-2">inventory_2</span>
                        <p class="text-slate-500 font-medium">No parts match your search.</p>
                        <a href="{{ route('parts.index') }}" class="text-primary text-xs font-bold mt-1 inline-block hover:underline">Clear filters</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($parts->hasPages())
    <div class="px-6 py-4 bg-surface-container-low border-t border-outline-variant/10 flex items-center justify-between">
        <span class="text-xs text-on-surface-variant">Showing {{ $parts->firstItem() }}–{{ $parts->lastItem() }} of {{ $parts->total() }}</span>
        {{ $parts->links() }}
    </div>
    @endif
</div>
@endsection
