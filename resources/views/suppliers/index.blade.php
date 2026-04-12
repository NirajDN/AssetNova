@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Flash Messages -->
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl font-medium text-sm">
        <span class="material-symbols-outlined text-green-600">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <section class="flex flex-col md:flex-row justify-between items-end gap-8">
        <div class="max-w-2xl">
            <span class="text-on-primary-container font-black tracking-widest text-[10px] uppercase block mb-2">Vendor Ecosystem</span>
            <h2 class="font-headline text-4xl font-extrabold text-primary tracking-tighter leading-none">Global Suppliers</h2>
            <p class="mt-3 text-on-surface-variant leading-relaxed max-w-lg">Manage and monitor your procurement network. Track performance metrics and lead times.</p>
        </div>
        <div class="flex gap-3 shrink-0">
            <a href="{{ route('suppliers.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg flex items-center gap-2 hover:opacity-90 transition-all text-sm">
                <span class="material-symbols-outlined">add</span> Onboard Supplier
            </a>
        </div>
    </section>

    <!-- Search -->
    <form method="GET" action="{{ route('suppliers.index') }}" class="flex gap-3">
        <div class="relative flex-1 max-w-md">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-on-surface-variant text-sm">search</span>
            <input name="search" value="{{ request('search') }}" placeholder="Search name, city, or email..." class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-xl pl-9 pr-4 py-2.5 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>
        <button type="submit" class="px-5 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">Search</button>
        @if(request('search'))<a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm hover:bg-surface-container-high transition-all">Clear</a>@endif
    </form>

    <!-- Metrics -->
    <section class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/15 shadow-sm">
            <span class="p-2 bg-secondary-container text-primary rounded-lg material-symbols-outlined block w-fit mb-3">public</span>
            <div class="text-on-surface-variant text-xs font-medium mb-1">Active Suppliers</div>
            <div class="text-3xl font-extrabold text-primary">{{ $totalSuppliers }}</div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/15 shadow-sm">
            <span class="p-2 bg-tertiary-fixed text-tertiary-container rounded-lg material-symbols-outlined block w-fit mb-3">local_shipping</span>
            <div class="text-on-surface-variant text-xs font-medium mb-1">Avg. Lead Time</div>
            <div class="text-3xl font-extrabold text-primary">12 <span class="text-sm font-medium">Days</span></div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/15 shadow-sm">
            <span class="p-2 bg-primary-fixed text-primary rounded-lg material-symbols-outlined block w-fit mb-3">star_rate</span>
            <div class="text-on-surface-variant text-xs font-medium mb-1">Network Rating</div>
            @php $avgRating = \App\Models\Supplier::avg('rating'); @endphp
            <div class="text-3xl font-extrabold text-primary">{{ number_format($avgRating, 1) }}</div>
        </div>
        <div class="bg-surface-container-lowest p-5 rounded-xl border border-outline-variant/15 shadow-sm">
            <span class="p-2 bg-error-container text-error rounded-lg material-symbols-outlined block w-fit mb-3">warning</span>
            <div class="text-on-surface-variant text-xs font-medium mb-1">Low Rated</div>
            <div class="text-3xl font-extrabold text-error">{{ \App\Models\Supplier::where('rating', '<', 3)->count() }}</div>
        </div>
    </section>

    <!-- Supplier Table -->
    <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-sm border border-outline-variant/10">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low">
                        <th class="px-8 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest">Supplier Name</th>
                        <th class="px-6 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest">Contact</th>
                        <th class="px-6 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest">Location</th>
                        <th class="px-6 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest">Rating</th>
                        <th class="px-6 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest text-center">Parts Supplied</th>
                        <th class="px-8 py-5 text-on-surface-variant font-black text-[10px] uppercase tracking-widest text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container">
                    @forelse($suppliers as $supplier)
                    <tr class="hover:bg-surface-container-low transition-colors group">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                                    <span class="material-symbols-outlined">factory</span>
                                </div>
                                <div>
                                    <div class="font-bold text-primary">{{ $supplier->name }}</div>
                                    <div class="text-[11px] text-on-surface-variant">{{ $supplier->contact_email ?? '—' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="text-sm font-medium text-on-surface">{{ $supplier->phone ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1 text-sm text-on-surface-variant">
                                <span class="material-symbols-outlined text-[14px]">location_on</span>
                                {{ $supplier->address ?? '—' }}
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-1 text-tertiary-fixed-dim">
                                @for($i=1; $i<=5; $i++)
                                    @if($i <= floor($supplier->rating ?? 0))
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">star</span>
                                    @elseif($i <= ceil($supplier->rating ?? 0))
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">star_half</span>
                                    @else
                                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 0">star</span>
                                    @endif
                                @endfor
                                <span class="text-xs text-on-surface-variant ml-1">{{ number_format($supplier->rating, 1) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="text-sm font-black text-primary">{{ $supplier->parts_count }}</span>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('suppliers.edit', $supplier) }}" class="p-2 rounded-lg hover:bg-surface-container text-slate-500 hover:text-primary transition-all">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <form action="{{ route('suppliers.destroy', $supplier) }}" method="POST" onsubmit="return confirm('Remove {{ $supplier->name }}?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg hover:bg-error-container text-slate-500 hover:text-error transition-all">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-14 text-center">
                            <span class="material-symbols-outlined text-4xl text-on-surface-variant block mb-2">conveyor_belt</span>
                            <p class="text-slate-500 font-medium">No suppliers found.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($suppliers->hasPages())
        <div class="bg-surface-container-low px-8 py-4 flex items-center justify-between border-t border-outline-variant/10">
            <span class="text-xs text-on-surface-variant">Showing {{ $suppliers->firstItem() }}–{{ $suppliers->lastItem() }} of {{ $suppliers->total() }}</span>
            {{ $suppliers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
