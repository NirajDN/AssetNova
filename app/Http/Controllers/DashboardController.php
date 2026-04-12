<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Supplier;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $cid = Auth::user()->company_id;

        $totalStock        = Part::where('company_id', $cid)->sum('stock_quantity');
        $lowStockCount     = Part::where('company_id', $cid)->whereColumn('stock_quantity','<=','min_threshold')->count();
        $totalSuppliers    = Supplier::where('company_id', $cid)->count();
        $totalTransactions = Transaction::where('company_id', $cid)->count();

        $recentTransactions = Transaction::with('part')
                                ->where('company_id', $cid)
                                ->orderBy('created_at', 'desc')
                                ->take(5)->get();

        $topParts = Part::with(['category'])
                        ->where('company_id', $cid)->get()
                        ->sortByDesc(fn($p) => $p->stock_quantity * $p->cost)
                        ->take(3)->values();

        $months      = collect(range(5, 0))->map(fn($i) => Carbon::now()->subMonths($i));
        $chartLabels = $months->map(fn($m) => $m->format('M Y'))->values();

        $chartIn = $months->map(fn($m) => Transaction::where('company_id', $cid)->where('type','in')
            ->whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)->sum('quantity') ?: 0)->values();
        $chartOut = $months->map(fn($m) => Transaction::where('company_id', $cid)->where('type','out')
            ->whereYear('created_at',$m->year)->whereMonth('created_at',$m->month)->sum('quantity') ?: 0)->values();

        return view('dashboard', compact(
            'totalStock','lowStockCount','totalSuppliers','totalTransactions',
            'recentTransactions','chartLabels','chartIn','chartOut','topParts'
        ));
    }
}
