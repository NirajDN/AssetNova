<?php

namespace App\Http\Controllers;

use App\Models\Part;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PartController extends Controller
{
    private function cid(): int { return Auth::user()->company_id; }

    public function index(Request $request)
    {
        $cid   = $this->cid();
        $query = Part::with(['category', 'supplier'])->where('company_id', $cid);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn($q) => $q->where('name','like',"%{$s}%")->orWhere('sku','like',"%{$s}%")->orWhere('location','like',"%{$s}%"));
        }
        if ($request->filled('category_id'))
            $query->where('category_id', $request->category_id);
        if ($request->filled('stock_status') && $request->stock_status === 'low')
            $query->whereColumn('stock_quantity', '<=', 'min_threshold');

        $parts      = $query->paginate(25)->withQueryString();
        $totalParts = Part::where('company_id', $cid)->count();
        $criticalLow= Part::where('company_id', $cid)->whereColumn('stock_quantity','<=','min_threshold')->count();
        $categories = Category::where('company_id', $cid)->orderBy('name')->get();

        $allParts         = Part::where('company_id', $cid)->get();
        $totalValue       = $allParts->sum(fn($p) => $p->stock_quantity * $p->cost);
        $projectedValue   = $totalValue * 1.095;
        $highestValuePart = $allParts->sortByDesc(fn($p) => $p->stock_quantity * $p->cost)->first();
        $valueAtRisk      = $allParts->filter(fn($p) => $p->stock_quantity <= $p->min_threshold)->sum(fn($p) => $p->stock_quantity * $p->cost);

        return view('parts.index', compact('parts','totalParts','criticalLow','categories','totalValue','projectedValue','highestValuePart','valueAtRisk'));
    }

    public function create()
    {
        $cid = $this->cid();
        $categories = Category::where('company_id', $cid)->orderBy('name')->get();
        $suppliers  = Supplier::where('company_id', $cid)->orderBy('name')->get();
        return view('parts.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $cid = $this->cid();

        $validated = $request->validate([
            'sku'            => ['required','string','max:100',
                                  Rule::unique('parts','sku')->where('company_id', $cid)],
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => ['nullable', Rule::exists('categories','id')->where('company_id', $cid)],
            'supplier_id'    => ['nullable', Rule::exists('suppliers','id')->where('company_id', $cid)],
            'cost'           => 'required|numeric|min:0|max:99999999',
            'stock_quantity' => 'required|integer|min:0|max:9999999',
            'min_threshold'  => 'required|integer|min:0|max:9999999',
            'location'       => 'nullable|string|max:255',
            'part_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'sku.unique'          => 'This SKU is already used by another part in your company.',
            'cost.max'            => 'Unit cost cannot exceed ₹9,99,99,999.',
            'stock_quantity.max'  => 'Stock quantity cannot exceed 9,999,999 units.',
            'part_image.image'    => 'The file must be a valid image (JPG, PNG, or WebP).',
            'part_image.max'      => 'Image size must not exceed 2MB.',
            'category_id.exists'  => 'Selected category does not belong to your company.',
            'supplier_id.exists'  => 'Selected supplier does not belong to your company.',
        ]);

        if ($request->hasFile('part_image')) {
            $file     = $request->file('part_image');
            $filename = 'part_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/parts'), $filename);
            $validated['image_url'] = '/images/parts/' . $filename;
        }
        unset($validated['part_image']);

        $validated['company_id'] = $cid;
        Part::create($validated);

        return redirect()->route('parts.index')->with('success', 'Part added to directory successfully.');
    }

    public function edit(Part $part)
    {
        // Ensure part belongs to this company
        abort_if($part->company_id !== $this->cid(), 403, 'Unauthorized.');

        $cid = $this->cid();
        $categories = Category::where('company_id', $cid)->orderBy('name')->get();
        $suppliers  = Supplier::where('company_id', $cid)->orderBy('name')->get();
        return view('parts.edit', compact('part', 'categories', 'suppliers'));
    }

    public function update(Request $request, Part $part)
    {
        abort_if($part->company_id !== $this->cid(), 403, 'Unauthorized.');
        $cid = $this->cid();

        $validated = $request->validate([
            'sku'            => ['required','string','max:100',
                                  Rule::unique('parts','sku')->where('company_id', $cid)->ignore($part->id)],
            'name'           => 'required|string|max:255',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => ['nullable', Rule::exists('categories','id')->where('company_id', $cid)],
            'supplier_id'    => ['nullable', Rule::exists('suppliers','id')->where('company_id', $cid)],
            'cost'           => 'required|numeric|min:0|max:99999999',
            'stock_quantity' => 'required|integer|min:0|max:9999999',
            'min_threshold'  => 'required|integer|min:0|max:9999999',
            'location'       => 'nullable|string|max:255',
            'part_image'     => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'sku.unique'         => 'This SKU is already used by another part in your company.',
            'cost.max'           => 'Unit cost cannot exceed ₹9,99,99,999.',
            'part_image.image'   => 'The file must be a valid image (JPG, PNG, or WebP).',
            'part_image.max'     => 'Image size must not exceed 2MB.',
            'category_id.exists' => 'Selected category does not belong to your company.',
            'supplier_id.exists' => 'Selected supplier does not belong to your company.',
        ]);

        if ($request->hasFile('part_image')) {
            if ($part->image_url && str_starts_with($part->image_url, '/images/parts/part_'))
                @unlink(public_path($part->image_url));
            $file     = $request->file('part_image');
            $filename = 'part_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/parts'), $filename);
            $validated['image_url'] = '/images/parts/' . $filename;
        }
        unset($validated['part_image']);
        $part->update($validated);

        return redirect()->route('parts.index')->with('success', 'Part updated successfully.');
    }

    public function destroy(Part $part)
    {
        abort_if($part->company_id !== $this->cid(), 403, 'Unauthorized.');

        // Prevent deleting if it has transaction history
        if ($part->transactions()->count() > 0) {
            return redirect()->route('parts.index')
                ->with('error', "Cannot delete \"{$part->name}\" — it has {$part->transactions()->count()} transaction record(s). Archive it instead.");
        }

        if ($part->image_url && str_starts_with($part->image_url, '/images/parts/part_'))
            @unlink(public_path($part->image_url));

        $part->delete();
        return redirect()->route('parts.index')->with('success', 'Part removed from directory.');
    }
}
