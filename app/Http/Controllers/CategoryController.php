<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    private function cid(): int { return Auth::user()->company_id; }

    public function index(Request $request)
    {
        $query = Category::withCount('parts')->where('company_id', $this->cid());
        if ($request->filled('search'))
            $query->where('name', 'like', '%' . $request->search . '%');

        $categories      = $query->paginate(12)->withQueryString();
        $totalCategories = Category::where('company_id', $this->cid())->count();

        return view('categories.index', compact('categories', 'totalCategories'));
    }

    public function store(Request $request)
    {
        $cid = $this->cid();

        $validated = $request->validate([
            'name'        => ['required','string','max:255',
                               Rule::unique('categories','name')->where('company_id', $cid)],
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Category name is required.',
            'name.unique'   => 'A category with this name already exists in your company.',
            'name.max'      => 'Category name cannot exceed 255 characters.',
        ]);

        $validated['company_id'] = $cid;
        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function update(Request $request, Category $category)
    {
        abort_if($category->company_id !== $this->cid(), 403, 'Unauthorized.');
        $cid = $this->cid();

        $validated = $request->validate([
            'name'        => ['required','string','max:255',
                               Rule::unique('categories','name')->where('company_id', $cid)->ignore($category->id)],
            'description' => 'nullable|string|max:500',
        ], [
            'name.required' => 'Category name is required.',
            'name.unique'   => 'Another category with this name already exists in your company.',
        ]);

        $category->update($validated);
        return redirect()->route('categories.index')->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->company_id !== $this->cid(), 403, 'Unauthorized.');

        $partCount = $category->parts()->count();
        if ($partCount > 0) {
            return redirect()->route('categories.index')
                ->with('error', "Cannot delete \"{$category->name}\" — it contains {$partCount} part(s). Reassign the parts to another category first.");
        }

        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Category deleted.');
    }
}
