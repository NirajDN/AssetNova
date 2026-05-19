@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    {{-- ── Page Header ─────────────────────────────────────────── --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-indigo-600 flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined text-white text-xl">auto_graph</span>
                </div>
                <div>
                    <h1 class="text-2xl font-black text-primary font-['Manrope'] leading-tight">AI Demand Forecast</h1>
                    <p class="text-xs text-on-surface-variant">Powered by Google Gemini · Based on last 6 months of transactions</p>
                </div>
            </div>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('ai.cache.clear') }}">
                @csrf
                <button type="submit"
                    class="flex items-center gap-2 px-4 py-2.5 bg-surface-container rounded-xl text-sm font-semibold text-on-surface-variant hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-sm">refresh</span>
                    Refresh AI
                </button>
            </form>
            <a href="{{ route('transactions.index') }}"
                class="flex items-center gap-2 px-4 py-2.5 bg-primary text-white rounded-xl text-sm font-bold hover:bg-primary-container transition-colors shadow-sm">
                <span class="material-symbols-outlined text-sm">add</span>
                Log Transaction
            </a>
        </div>
    </div>

    {{-- ── Flash messages ───────────────────────────────────────── --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm">
        <span class="material-symbols-outlined text-emerald-500 text-sm">check_circle</span>
        {{ session('success') }}
    </div>
    @endif

    {{-- ── AI Narrative Summary Card ────────────────────────────── --}}
    <div class="relative bg-gradient-to-br from-[#0d1b3e] to-[#1a2e6e] rounded-2xl p-6 shadow-xl overflow-hidden">
        {{-- Background shimmer --}}
        <div class="absolute inset-0 opacity-10"
             style="background: radial-gradient(ellipse at 70% 50%, #7c3aed 0%, transparent 60%)"></div>

        <div class="relative flex items-start gap-4">
            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-violet-500/20 border border-violet-400/30 flex items-center justify-center">
                <span class="material-symbols-outlined text-violet-300 text-xl">smart_toy</span>
            </div>
            <div class="flex-1">
                <p class="text-violet-300 text-xs font-bold uppercase tracking-widest mb-2">Nova AI · Executive Summary</p>
                <p class="text-white/90 text-sm leading-relaxed">{{ $forecastSummary }}</p>
            </div>
        </div>
    </div>

    {{-- ── No Data State ────────────────────────────────────────── --}}
    @if($partData->isEmpty())
    <div class="text-center py-20 bg-white rounded-2xl border border-outline-variant/20">
        <span class="material-symbols-outlined text-5xl text-outline-variant mb-4 block">bar_chart</span>
        <h3 class="font-bold text-primary text-lg mb-2">No Forecast Data Yet</h3>
        <p class="text-on-surface-variant text-sm max-w-sm mx-auto">
            Start logging stock-out transactions to enable AI demand predictions for your parts.
        </p>
        <a href="{{ route('transactions.create') }}"
            class="inline-flex items-center gap-2 mt-6 px-5 py-2.5 bg-primary text-white text-sm font-bold rounded-xl hover:bg-primary-container transition-colors">
            <span class="material-symbols-outlined text-sm">add</span>
            Add First Transaction
        </a>
    </div>
    @else

    {{-- ── KPI Bar ──────────────────────────────────────────────── --}}
    @php
        $highRisk   = $partData->where('risk','high')->count();
        $mediumRisk = $partData->where('risk','medium')->count();
        $trending   = $partData->where('trend','up')->count();
        $topPart    = $partData->first();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-outline-variant/20 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-1">Parts Analysed</p>
            <p class="text-3xl font-black text-primary font-['Manrope']">{{ $partData->count() }}</p>
            <p class="text-xs text-on-surface-variant mt-1">with transaction history</p>
        </div>
        <div class="bg-red-50 rounded-2xl p-5 border border-red-100 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-red-500 mb-1">High Risk</p>
            <p class="text-3xl font-black text-red-600 font-['Manrope']">{{ $highRisk }}</p>
            <p class="text-xs text-red-400 mt-1">may stock-out next month</p>
        </div>
        <div class="bg-amber-50 rounded-2xl p-5 border border-amber-100 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-amber-600 mb-1">Medium Risk</p>
            <p class="text-3xl font-black text-amber-600 font-['Manrope']">{{ $mediumRisk }}</p>
            <p class="text-xs text-amber-500 mt-1">monitor closely</p>
        </div>
        <div class="bg-violet-50 rounded-2xl p-5 border border-violet-100 shadow-sm">
            <p class="text-xs font-bold uppercase tracking-widest text-violet-600 mb-1">Trending Up</p>
            <p class="text-3xl font-black text-violet-600 font-['Manrope']">{{ $trending }}</p>
            <p class="text-xs text-violet-400 mt-1">rising demand parts</p>
        </div>
    </div>

    {{-- ── Forecast Chart ───────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl p-6 border border-outline-variant/20 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="font-black text-primary font-['Manrope'] text-lg">Consumption vs. Prediction</h2>
                <p class="text-xs text-on-surface-variant">Top 8 parts by forecasted demand next month</p>
            </div>
            <span class="text-xs bg-violet-100 text-violet-700 font-bold px-3 py-1 rounded-full">AI Forecast</span>
        </div>
        <div class="relative h-72">
            <canvas id="forecastChart"></canvas>
        </div>
    </div>

    {{-- ── Part Forecast Table ──────────────────────────────────── --}}
    <div class="bg-white rounded-2xl border border-outline-variant/20 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-outline-variant/10 flex items-center justify-between">
            <h2 class="font-black text-primary font-['Manrope']">Part-by-Part Forecast</h2>
            <span class="text-xs text-on-surface-variant">Sorted by predicted demand ↓</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-surface-container text-on-surface-variant text-xs font-bold uppercase tracking-widest">
                        <th class="px-6 py-3 text-left">Part</th>
                        <th class="px-6 py-3 text-center">6-Mo Avg/mo</th>
                        <th class="px-6 py-3 text-center">6-Mo Total Out</th>
                        <th class="px-6 py-3 text-center">Current Stock</th>
                        <th class="px-6 py-3 text-center">Predicted Next Mo</th>
                        <th class="px-6 py-3 text-center">Trend</th>
                        <th class="px-6 py-3 text-center">Risk</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    @foreach($partData as $d)
                    <tr class="hover:bg-surface-container/40 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-primary">{{ $d['part']->name }}</div>
                            <div class="text-xs text-on-surface-variant">{{ $d['part']->sku }} · {{ $d['part']->category?->name ?? '—' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center font-mono text-on-surface-variant">{{ $d['avg'] }}</td>
                        <td class="px-6 py-4 text-center font-mono text-on-surface-variant">{{ $d['total6m'] }}</td>
                        <td class="px-6 py-4 text-center font-mono font-bold
                            {{ $d['part']->stock_quantity <= $d['part']->min_threshold ? 'text-error' : 'text-primary' }}">
                            {{ $d['part']->stock_quantity }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="font-black text-lg font-['Manrope']
                                {{ $d['risk'] === 'high' ? 'text-red-600' : ($d['risk'] === 'medium' ? 'text-amber-600' : 'text-emerald-600') }}">
                                {{ $d['predicted'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($d['trend'] === 'up')
                                <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs">
                                    <span class="material-symbols-outlined text-sm">trending_up</span> Rising
                                </span>
                            @elseif($d['trend'] === 'down')
                                <span class="inline-flex items-center gap-1 text-blue-500 font-bold text-xs">
                                    <span class="material-symbols-outlined text-sm">trending_down</span> Falling
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-on-surface-variant font-bold text-xs">
                                    <span class="material-symbols-outlined text-sm">trending_flat</span> Stable
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($d['risk'] === 'high')
                                <span class="inline-block bg-red-100 text-red-700 text-xs font-black px-2.5 py-1 rounded-full">HIGH</span>
                            @elseif($d['risk'] === 'medium')
                                <span class="inline-block bg-amber-100 text-amber-700 text-xs font-black px-2.5 py-1 rounded-full">MEDIUM</span>
                            @else
                                <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-black px-2.5 py-1 rounded-full">LOW</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @endif {{-- end $partData not empty --}}
</div>
@endsection

@push('scripts')
@if(!$partData->isEmpty())
<script>
const labels  = @json($monthLabels->values());
const partData = @json($partData->take(8)->values());

const datasets = partData.map((d, i) => {
    const hues = [220, 262, 142, 38, 0, 188, 310, 60];
    const h = hues[i % hues.length];
    return {
        label:           d.part.name,
        data:            [...d.history, d.predicted],
        borderColor:     `hsl(${h},70%,50%)`,
        backgroundColor: `hsl(${h},70%,50%,0.08)`,
        pointBackgroundColor: [...Array(6).fill(`hsl(${h},70%,50%)`), `hsl(${h},70%,35%)`],
        pointRadius:     [...Array(6).fill(3), 7],
        pointStyle:      [...Array(6).fill('circle'), 'star'],
        borderWidth:     2,
        tension:         0.4,
        fill:            false,
    };
});

const allLabels = [...labels, 'Next Month (AI)'];

new Chart(document.getElementById('forecastChart'), {
    type: 'line',
    data: { labels: allLabels, datasets },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: {
                position: 'bottom',
                labels: { font: { size: 11 }, boxWidth: 12, padding: 16 }
            },
            tooltip: {
                callbacks: {
                    footer: (items) => {
                        const last = items[0];
                        if (last.dataIndex === allLabels.length - 1) {
                            return '★ AI Prediction';
                        }
                    }
                }
            }
        },
        scales: {
            x: {
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { font: { size: 11 } }
            },
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(0,0,0,0.04)' },
                ticks: { font: { size: 11 } }
            }
        }
    }
});
</script>
@endif
@endpush
