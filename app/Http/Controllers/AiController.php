<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use App\Models\Part;
use App\Models\Supplier;
use App\Models\Transaction;
use App\Services\GeminiService;

class AiController extends Controller
{
    public function __construct(private GeminiService $gemini) {}

    // ─────────────────────────────────────────────────────────────
    //  AI CHAT  — POST /ai/chat
    // ─────────────────────────────────────────────────────────────
    public function chat(Request $request)
    {
        $request->validate(['message' => 'required|string|max:500']);

        $cid  = Auth::user()->company_id;
        $user = Auth::user()->name;

        // Build a rich context snapshot (cached 5 min to save DB calls)
        $context = Cache::remember("ai_context_{$cid}", 300, function () use ($cid) {
            $parts     = Part::with(['category', 'supplier'])
                             ->where('company_id', $cid)->get();
            $suppliers = Supplier::where('company_id', $cid)->get();
            $recent    = Transaction::with('part')
                             ->where('company_id', $cid)
                             ->orderByDesc('created_at')
                             ->limit(20)->get();

            $lowStock = $parts->filter(fn($p) => $p->stock_quantity <= $p->min_threshold);

            $partsText = $parts->map(fn($p) =>
                "- {$p->name} (SKU:{$p->sku}) | Stock:{$p->stock_quantity} | Min:{$p->min_threshold} | Cost:₹{$p->cost} | Category:{$p->category?->name} | Supplier:{$p->supplier?->name}"
            )->join("\n");

            $suppliersText = $suppliers->map(fn($s) =>
                "- {$s->name} | Email:{$s->contact_email} | Rating:{$s->rating}/5"
            )->join("\n");

            $recentText = $recent->map(fn($t) =>
                "- [{$t->created_at->format('d M')}] {$t->type}: {$t->quantity} x {$t->part?->name}"
            )->join("\n");

            $lowStockText = $lowStock->map(fn($p) =>
                "- {$p->name} (Stock:{$p->stock_quantity}, Min:{$p->min_threshold})"
            )->join("\n");

            return <<<CTX
INVENTORY SNAPSHOT (Company ID: {$cid}):

PARTS ({$parts->count()} total):
{$partsText}

LOW-STOCK ALERTS ({$lowStock->count()} parts):
{$lowStockText}

SUPPLIERS:
{$suppliersText}

RECENT TRANSACTIONS (last 20):
{$recentText}
CTX;
        });

        $systemPrompt = <<<PROMPT
You are Nova, a friendly and expert AI inventory assistant for AssetNova — an industrial inventory management platform.
You have access to the company's live inventory data below. Answer concisely, be specific with numbers, and always be helpful.
Format your response using plain text. Use bullet points when listing items. Be direct and professional.
The user's name is: {$user}

{$context}

User question: {$request->message}
PROMPT;

        $reply = $this->gemini->ask($systemPrompt);

        return response()->json(['reply' => $reply]);
    }

    // ─────────────────────────────────────────────────────────────
    //  DEMAND FORECASTING  — GET /ai/forecast
    // ─────────────────────────────────────────────────────────────
    public function forecast()
    {
        $cid = Auth::user()->company_id;

        // Gather last 6 months of 'out' transactions per part
        $parts = Part::with(['category', 'supplier'])
                     ->where('company_id', $cid)
                     ->get();

        $months = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        $monthLabels = $months->map(fn($m) => $m->format('M Y'));

        // Build per-part history matrix
        $partData = $parts->map(function ($part) use ($months, $cid) {
            $history = $months->map(fn($m) =>
                Transaction::where('company_id', $cid)
                    ->where('part_id', $part->id)
                    ->where('type', 'out')
                    ->whereYear('created_at', $m->year)
                    ->whereMonth('created_at', $m->month)
                    ->sum('quantity') ?: 0
            )->values()->toArray();

            $avg       = array_sum($history) / max(count(array_filter($history, fn($v) => $v > 0)), 1);
            $total6m   = array_sum($history);
            // Simple linear regression for trend
            $n         = count($history);
            $indices   = range(1, $n);
            $sumX      = array_sum($indices);
            $sumY      = array_sum($history);
            $sumXY     = array_sum(array_map(fn($x, $y) => $x * $y, $indices, $history));
            $sumX2     = array_sum(array_map(fn($x) => $x * $x, $indices));
            $slope     = $n > 0 ? ($n * $sumXY - $sumX * $sumY) / max($n * $sumX2 - $sumX * $sumX, 1) : 0;
            $intercept = ($sumY - $slope * $sumX) / max($n, 1);
            $predicted = max(0, round($intercept + $slope * ($n + 1)));

            return [
                'part'      => $part,
                'history'   => $history,
                'avg'       => round($avg, 1),
                'total6m'   => $total6m,
                'predicted' => $predicted,
                'trend'     => $slope > 0.5 ? 'up' : ($slope < -0.5 ? 'down' : 'stable'),
                'slope'     => round($slope, 2),
                'risk'      => $predicted > $part->stock_quantity ? 'high' : ($predicted > $part->stock_quantity * 0.6 ? 'medium' : 'low'),
            ];
        })
        ->filter(fn($d) => $d['total6m'] > 0)          // only parts with activity
        ->sortByDesc('predicted')
        ->values();

        // Ask Gemini for a narrative summary of the forecast
        $forecastSummary = Cache::remember("ai_forecast_{$cid}", 600, function () use ($partData, $cid) {
            if ($partData->isEmpty()) {
                return 'Not enough transaction history to generate an AI forecast summary yet. Add some stock-out transactions first!';
            }
            $lines = $partData->take(10)->map(fn($d) =>
                "- {$d['part']->name}: 6-month avg={$d['avg']}/mo, predicted next month={$d['predicted']}, current stock={$d['part']->stock_quantity}, risk={$d['risk']}"
            )->join("\n");

            $prompt = <<<P
You are Nova, an AI inventory analyst. Analyze this demand forecast data for an industrial company and write a concise executive summary (3–5 sentences max).
Highlight: top parts to watch, reorder urgencies, and any trends worth noting. Be specific with part names and numbers.

Forecast Data:
{$lines}
P;
            return app(GeminiService::class)->ask($prompt);
        });

        return view('ai.forecast', compact(
            'partData', 'monthLabels', 'forecastSummary', 'parts'
        ));
    }

    // ─────────────────────────────────────────────────────────────
    //  CLEAR AI CACHE  — POST /ai/cache/clear
    // ─────────────────────────────────────────────────────────────
    public function clearCache()
    {
        $cid = Auth::user()->company_id;
        Cache::forget("ai_context_{$cid}");
        Cache::forget("ai_forecast_{$cid}");
        return back()->with('success', 'AI cache refreshed — Nova will re-read your latest data.');
    }
}
