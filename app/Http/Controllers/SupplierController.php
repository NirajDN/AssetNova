<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    private function cid(): int { return Auth::user()->company_id; }

    public function index(Request $request)
    {
        $query = Supplier::withCount('parts')->where('company_id', $this->cid());

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%{$s}%")->orWhere('address','like',"%{$s}%")->orWhere('contact_email','like',"%{$s}%"));
        }

        $suppliers      = $query->paginate(10)->withQueryString();
        $totalSuppliers = Supplier::where('company_id', $this->cid())->count();
        $avgLeadDays    = 12; // placeholder
        $avgRating      = Supplier::where('company_id', $this->cid())->avg('rating') ?? 0;
        $lowRated       = Supplier::where('company_id', $this->cid())->where('rating','<',3)->count();

        return view('suppliers.index', compact('suppliers', 'totalSuppliers', 'avgLeadDays', 'avgRating', 'lowRated'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(Request $request)
    {
        $cid = $this->cid();

        $validated = $request->validate([
            'name'          => ['required','string','max:255',
                                 Rule::unique('suppliers','name')->where('company_id', $cid)],
            'contact_email' => 'nullable|email|max:255',
            'phone'         => ['nullable','string','max:30','regex:/^[0-9\+\(\)\-\s]+$/'],
            'address'       => 'nullable|string|max:500',
            'rating'        => 'nullable|numeric|min:0|max:5',
        ], [
            'name.unique'       => 'A supplier with this name already exists in your company.',
            'contact_email.email' => 'Please enter a valid email address.',
            'phone.regex'       => 'Phone number can only contain digits, spaces, +, -, and parentheses.',
            'rating.min'        => 'Rating must be between 0 and 5.',
            'rating.max'        => 'Rating must be between 0 and 5.',
        ]);

        $validated['company_id'] = $cid;
        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier onboarded successfully.');
    }

    public function edit(Supplier $supplier)
    {
        abort_if($supplier->company_id !== $this->cid(), 403, 'Unauthorized.');
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        abort_if($supplier->company_id !== $this->cid(), 403, 'Unauthorized.');
        $cid = $this->cid();

        $validated = $request->validate([
            'name'          => ['required','string','max:255',
                                 Rule::unique('suppliers','name')->where('company_id', $cid)->ignore($supplier->id)],
            'contact_email' => 'nullable|email|max:255',
            'phone'         => ['nullable','string','max:30','regex:/^[0-9\+\(\)\-\s]+$/'],
            'address'       => 'nullable|string|max:500',
            'rating'        => 'nullable|numeric|min:0|max:5',
        ], [
            'name.unique'         => 'Another supplier with this name already exists in your company.',
            'contact_email.email' => 'Please enter a valid email address.',
            'phone.regex'         => 'Phone number can only contain digits, spaces, +, -, and parentheses.',
            'rating.min'          => 'Rating must be between 0 and 5.',
            'rating.max'          => 'Rating must be between 0 and 5.',
        ]);

        $supplier->update($validated);
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        abort_if($supplier->company_id !== $this->cid(), 403, 'Unauthorized.');

        $partCount = $supplier->parts()->count();
        if ($partCount > 0) {
            return redirect()->route('suppliers.index')
                ->with('error', "Cannot remove \"{$supplier->name}\" — they supply {$partCount} part(s). Reassign the parts first.");
        }

        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier removed from network.');
    }
}
