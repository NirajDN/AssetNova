<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Part;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    private function cid(): int { return Auth::user()->company_id; }

    public function index(Request $request)
    {
        $cid   = $this->cid();
        $query = Transaction::with('part')->where('company_id', $cid)->orderBy('created_at', 'desc');

        if ($request->filled('type') && in_array($request->type, ['in', 'out']))
            $query->where('type', $request->type);
        if ($request->filled('date_from'))
            $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))
            $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('search')) {
            $s = $request->search;
            $query->whereHas('part', fn($q) => $q->where('name','like',"%{$s}%")->orWhere('sku','like',"%{$s}%"));
        }

        // Validate date range logic
        if ($request->filled('date_from') && $request->filled('date_to') && $request->date_from > $request->date_to) {
            return back()->withErrors(['date_from' => '"From" date cannot be after the "To" date.'])->withInput();
        }

        $transactions = $query->paginate(20)->withQueryString();
        $totalIn      = Transaction::where('company_id', $cid)->where('type','in')->sum('quantity');
        $totalOut     = Transaction::where('company_id', $cid)->where('type','out')->sum('quantity');

        return view('transactions.index', compact('transactions', 'totalIn', 'totalOut'));
    }

    public function create()
    {
        $parts = Part::where('company_id', $this->cid())->orderBy('name')->get();
        return view('transactions.create', compact('parts'));
    }

    public function store(Request $request)
    {
        $cid = $this->cid();

        $validated = $request->validate([
            'part_id'  => ['required', Rule::exists('parts','id')->where('company_id', $cid)],
            'type'     => 'required|in:in,out',
            'quantity' => 'required|integer|min:1|max:999999',
            'notes'    => 'nullable|string|max:500',
        ], [
            'part_id.required'  => 'Please select a part.',
            'part_id.exists'    => 'Selected part does not belong to your company.',
            'type.required'     => 'Please select a movement type (Stock In / Stock Out).',
            'type.in'           => 'Movement type must be either Stock In or Stock Out.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min'      => 'Quantity must be at least 1.',
            'quantity.max'      => 'Quantity cannot exceed 999,999 units per transaction.',
            'notes.max'         => 'Notes cannot exceed 500 characters.',
        ]);

        $part = Part::where('id', $validated['part_id'])
                    ->where('company_id', $cid)
                    ->firstOrFail();

        if ($validated['type'] === 'in') {
            // Cap total stock from going unrealistically high
            $newStock = $part->stock_quantity + $validated['quantity'];
            if ($newStock > 9999999) {
                return back()->withErrors(['quantity' => "Stock In would exceed the maximum allowed stock (9,999,999 units). Current: {$part->stock_quantity}"])->withInput();
            }
            $part->increment('stock_quantity', $validated['quantity']);
        } else {
            // Insufficient stock check
            if ($part->stock_quantity <= 0) {
                return back()->withErrors(['quantity' => "No stock available for \"{$part->name}\". Current stock: 0 units."])->withInput();
            }
            if ($validated['quantity'] > $part->stock_quantity) {
                return back()->withErrors(['quantity' => "Insufficient stock. You are trying to take out {$validated['quantity']} units but only {$part->stock_quantity} units are available for \"{$part->name}\"."]) ->withInput();
            }
            $part->decrement('stock_quantity', $validated['quantity']);
        }

        $validated['company_id'] = $cid;
        Transaction::create($validated);

        return redirect()->route('transactions.index')->with('success', 'Transaction recorded successfully.');
    }
}
