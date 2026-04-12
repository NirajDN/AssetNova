@extends('layouts.app')

@section('content')
    <!-- Header & Top KPIs -->
    <section class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-extrabold tracking-tighter text-primary mb-2">Operational Pulse</h2>
            <p class="text-on-surface-variant font-medium tracking-wide">Facility: Northern Logistics Terminal — <span class="text-primary font-bold">Status: Optimal</span></p>
        </div>
        <div class="flex gap-4">
            <a href="{{ route('parts.index') }}" class="flex items-center gap-2 bg-primary text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-lg shadow-primary/20 hover:scale-[1.02] transition-transform">
                <span class="material-symbols-outlined text-sm">add</span>
                Register New Part
            </a>
            <a href="{{ route('export.manifest') }}" class="flex items-center gap-2 bg-surface-container-highest text-primary px-5 py-2.5 rounded-lg font-bold text-sm hover:bg-surface-variant transition-colors">
                <span class="material-symbols-outlined text-sm">file_download</span>
                Export Manifest
            </a>
        </div>
    </section>

    <!-- Bento Grid Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Stock -->
        <div class="col-span-1 bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-primary">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-primary-container/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined">inventory</span>
                </div>
            </div>
            <p class="text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[10px]">Total Active Stock</p>
            <h3 class="text-4xl font-extrabold text-primary tracking-tighter mt-1">{{ number_format($totalStock ?? 14208) }}</h3>
            <p class="text-xs text-on-surface-variant mt-2 italic">Units across all sectors</p>
        </div>

        <!-- Low Stock - Alert State -->
        <div class="col-span-1 bg-error-container/20 p-6 rounded-xl border border-error/10 relative overflow-hidden">
            <div class="absolute -right-4 -top-4 text-error/5 rotate-12">
                <span class="material-symbols-outlined text-9xl">warning</span>
            </div>
            <div class="flex justify-between items-start mb-4 relative z-10">
                <div class="w-10 h-10 rounded-lg bg-error/10 flex items-center justify-center text-error">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">warning</span>
                </div>
                <span class="text-[10px] font-black text-error px-2 py-1 bg-error/10 rounded-md">CRITICAL</span>
            </div>
            <p class="text-label-md font-bold text-error uppercase tracking-widest text-[10px] relative z-10">Low Stock Alerts</p>
            <h3 class="text-4xl font-extrabold text-error tracking-tighter mt-1 relative z-10">{{ $lowStockCount ?? 24 }}</h3>
            <p class="text-xs text-on-error-container mt-2 font-medium relative z-10">Immediate reorder required</p>
        </div>

        <!-- Active Suppliers -->
        <div class="col-span-1 bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-secondary">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-secondary-container/30 flex items-center justify-center text-secondary">
                    <span class="material-symbols-outlined">factory</span>
                </div>
            </div>
            <p class="text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[10px]">Active Suppliers</p>
            <h3 class="text-4xl font-extrabold text-primary tracking-tighter mt-1">{{ $totalSuppliers ?? 86 }}</h3>
            <p class="text-xs text-on-surface-variant mt-2 italic">Operating globally</p>
        </div>

        <!-- Global Efficiency (Transactions) -->
        <div class="col-span-1 bg-surface-container-lowest p-6 rounded-xl shadow-sm border-l-4 border-tertiary-container">
            <div class="flex justify-between items-start mb-4">
                <div class="w-10 h-10 rounded-lg bg-tertiary-fixed/30 flex items-center justify-center text-tertiary-container">
                    <span class="material-symbols-outlined">swap_horiz</span>
                </div>
            </div>
            <p class="text-label-md font-bold text-on-surface-variant uppercase tracking-widest text-[10px]">Total Transactions</p>
            <h3 class="text-4xl font-extrabold text-primary tracking-tighter mt-1">{{ $totalTransactions ?? 984 }}</h3>
            <p class="text-xs text-on-surface-variant mt-2 italic">All time activity log</p>
        </div>
    </div>

    <!-- Main Content Area: Chart and Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Inventory Trajectory — Live Chart.js -->
        <div class="lg:col-span-2 bg-surface-container-lowest p-8 rounded-2xl shadow-sm border border-outline-variant/10">
            <!-- Header -->
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h4 class="text-xl font-bold text-primary tracking-tight">Inventory Trajectory</h4>
                    <p class="text-sm text-on-surface-variant">Stock In vs Stock Out — Last 6 months (live)</p>
                </div>
                <div class="flex gap-3 items-center">
                    <span class="flex items-center gap-1.5 text-xs font-bold text-primary">
                        <span class="w-3 h-3 rounded-full bg-primary inline-block"></span> Stock In
                    </span>
                    <span class="flex items-center gap-1.5 text-xs font-bold text-error">
                        <span class="w-3 h-3 rounded-full bg-error inline-block"></span> Stock Out
                    </span>
                </div>
            </div>

            <!-- Chart Canvas -->
            <div class="relative" style="height: 240px;">
                <canvas id="inventoryChart"></canvas>
            </div>

            <!-- Bottom KPIs -->
            <div class="mt-6 pt-5 border-t border-outline-variant/10 grid grid-cols-3 gap-4">
                <div class="text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Total In (period)</p>
                    <p class="text-xl font-black text-primary">{{ number_format($chartIn->sum()) }}</p>
                    <p class="text-[10px] text-on-surface-variant">units received</p>
                </div>
                <div class="text-center border-x border-outline-variant/10">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Total Out (period)</p>
                    <p class="text-xl font-black text-error">{{ number_format($chartOut->sum()) }}</p>
                    <p class="text-[10px] text-on-surface-variant">units dispatched</p>
                </div>
                <div class="text-center">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-1">Net Movement</p>
                    @php $net = $chartIn->sum() - $chartOut->sum(); @endphp
                    <p class="text-xl font-black {{ $net >= 0 ? 'text-primary' : 'text-error' }}">
                        {{ $net >= 0 ? '+' : '' }}{{ number_format($net) }}
                    </p>
                    <p class="text-[10px] text-on-surface-variant">net units</p>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="bg-surface-container p-8 rounded-2xl">
            <div class="flex justify-between items-center mb-8">
                <h4 class="text-xl font-bold text-primary tracking-tight">Recent Activity</h4>
                <a href="{{ route('transactions.index') }}" class="material-symbols-outlined text-primary/40 cursor-pointer hover:text-primary">arrow_forward</a>
            </div>
            <div class="space-y-6 relative before:absolute before:left-3 before:top-2 before:bottom-2 before:w-[2px] before:bg-outline-variant/30">
                
                @forelse($recentTransactions ?? [] as $transaction)
                <div class="relative pl-10">
                    <div class="absolute left-0 top-1 w-6 h-6 rounded-full {{ $transaction->type === 'in' ? 'bg-primary' : 'bg-secondary' }} flex items-center justify-center ring-4 ring-surface-container">
                        <span class="material-symbols-outlined text-white text-xs">{{ $transaction->type === 'in' ? 'arrow_downward' : 'arrow_upward' }}</span>
                    </div>
                    <p class="text-xs font-bold {{ $transaction->type === 'in' ? 'text-primary' : 'text-secondary' }}">
                        Stock {{ ucfirst($transaction->type) }}: {{ $transaction->part->name ?? 'Unknown Part' }}
                    </p>
                    <p class="text-[11px] text-on-surface-variant mb-1">{{ Str::limit($transaction->notes, 40) }}</p>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] text-on-surface-variant/70 font-medium">{{ $transaction->created_at->diffForHumans() }}</span>
                        <span class="text-[10px] font-bold px-1.5 py-0.5 rounded {{ $transaction->type === 'in' ? 'bg-secondary-container text-primary-container' : 'bg-surface-container-highest text-on-surface-variant' }}">
                            {{ $transaction->type === 'in' ? '+' : '-' }}{{ $transaction->quantity }} Units
                        </span>
                    </div>
                </div>
                @empty
                    <p class="text-sm text-on-surface-variant italic">No recent transactions to strictly display.</p>
                @endforelse

            </div>
            <a href="{{ route('transactions.index') }}" class="w-full block text-center mt-10 py-3 rounded-lg border border-primary/20 text-xs font-bold text-primary hover:bg-white transition-colors">
                View Comprehensive Log
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
    (function() {
        const labels  = @json($chartLabels);
        const dataIn  = @json($chartIn);
        const dataOut = @json($chartOut);

        const ctx = document.getElementById('inventoryChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Stock In',
                        data: dataIn,
                        borderColor: '#002045',
                        backgroundColor: 'rgba(0,32,69,0.10)',
                        pointBackgroundColor: '#002045',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2.5,
                    },
                    {
                        label: 'Stock Out',
                        data: dataOut,
                        borderColor: '#ba1a1a',
                        backgroundColor: 'rgba(186,26,26,0.07)',
                        pointBackgroundColor: '#ba1a1a',
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        fill: true,
                        tension: 0.45,
                        borderWidth: 2.5,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: { duration: 900, easing: 'easeInOutQuart' },
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#002045',
                        titleColor: '#adc7f7',
                        bodyColor: '#ffffff',
                        padding: 12,
                        cornerRadius: 10,
                        callbacks: {
                            label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y.toLocaleString()} units`
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: { color: '#43474e', font: { size: 11, weight: '600' } }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: {
                            color: '#43474e',
                            font: { size: 11 },
                            callback: v => v.toLocaleString()
                        }
                    }
                }
            }
        });
    })();
    </script>
    @endpush

    <!-- Featured Parts / High Value Assets Section -->
    <section class="mt-12">
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-2xl font-extrabold text-primary tracking-tighter">Priority Asset Overview</h4>
            <a class="text-sm font-bold text-primary flex items-center gap-1 hover:underline" href="{{ route('parts.index') }}">
                View Full Directory
                <span class="material-symbols-outlined text-sm">trending_flat</span>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($topParts as $part)
            @php
                $totalVal = $part->stock_quantity * $part->cost;
                $isLow    = $part->stock_quantity <= $part->min_threshold;
                $bgGradients = [
                    'from-blue-900 to-slate-800',
                    'from-slate-800 to-primary',
                    'from-primary to-blue-800',
                ];
                $grad = $bgGradients[$loop->index % 3];
            @endphp
            <div class="bg-surface-container-lowest rounded-xl overflow-hidden flex flex-col group border border-transparent hover:border-primary/10 transition-all shadow-sm">
                <!-- Image / Gradient Header -->
                <div class="h-40 overflow-hidden relative">
                    @if($part->image_url)
                        <img class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                             src="{{ asset($part->image_url) }}" alt="{{ $part->name }}"/>
                    @else
                        <div class="w-full h-full bg-gradient-to-br {{ $grad }} flex items-center justify-center">
                            <span class="material-symbols-outlined text-white/30 text-[5rem]">settings_input_component</span>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-md rounded-full text-[10px] font-bold text-primary">
                        {{ $part->sku }}
                    </div>
                    @if($isLow)
                    <div class="absolute top-3 right-3 px-2 py-1 bg-error/90 rounded-full text-[10px] font-bold text-white flex items-center gap-1">
                        <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse inline-block"></span> Critical
                    </div>
                    @endif
                </div>
                <!-- Body -->
                <div class="p-6 flex flex-col flex-1">
                    <div class="flex justify-between items-start mb-2">
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase tracking-wider mb-0.5">
                                {{ $part->category->name ?? 'Uncategorized' }}
                            </p>
                            <h5 class="font-bold text-primary">{{ $part->name }}</h5>
                        </div>
                        <span class="material-symbols-outlined {{ $isLow ? 'text-error' : 'text-green-600' }}">
                            {{ $isLow ? 'warning' : 'verified' }}
                        </span>
                    </div>
                    <p class="text-xs text-on-surface-variant mb-4 leading-relaxed flex-1">
                        {{ $part->description ?: 'Industrial component in the ' . ($part->category->name ?? 'general') . ' category. Stored at ' . ($part->location ?? 'warehouse') . '.' }}
                    </p>
                    <div class="flex justify-between items-center pt-4 border-t border-outline-variant/10">
                        <div>
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase">Stock</p>
                            <p class="text-lg font-black {{ $isLow ? 'text-error' : 'text-primary' }}">
                                {{ number_format($part->stock_quantity) }}
                                <span class="text-[10px] font-normal text-on-surface-variant">units</span>
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] font-bold text-on-surface-variant uppercase">Total Value</p>
                            <p class="text-lg font-black text-primary">₹{{ number_format($totalVal, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-on-surface-variant col-span-3 text-sm">No parts in inventory yet.</p>
            @endforelse
        </div>
    </section>

@endsection


