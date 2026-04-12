@extends('layouts.app')

@section('content')
<div class="space-y-8">
    @if(session('success'))
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-5 py-3 rounded-xl font-medium text-sm">
        <span class="material-symbols-outlined text-green-600">check_circle</span>{{ session('success') }}
    </div>
    @endif

    <!-- Header -->
    <section class="flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h2 class="text-4xl font-extrabold text-primary tracking-tighter">Categories</h2>
            <p class="text-on-surface-variant mt-1 text-lg max-w-2xl">Organize industrial components across your warehouse infrastructure.</p>
        </div>
        <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="bg-primary text-white px-6 py-3 rounded-xl font-bold shadow-lg hover:opacity-90 flex items-center gap-2 transition-all self-start">
            <span class="material-symbols-outlined">add</span> Create Category
        </button>
    </section>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
        <div class="bg-surface-container-lowest border border-outline-variant/15 p-6 rounded-2xl shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-xl bg-surface-container-high flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                    <span class="material-symbols-outlined">category</span>
                </div>
                <div>
                    <h3 class="font-bold text-lg text-on-surface">{{ $category->name }}</h3>
                    <p class="text-xs text-on-surface-variant">CAT-{{ str_pad($category->id, 4, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
            <p class="text-sm text-on-surface-variant mb-6 min-h-[40px]">{{ $category->description ?? 'No description provided.' }}</p>
            <div class="flex justify-between items-center pt-4 border-t border-surface-container">
                <a href="{{ route('parts.index', ['category_id' => $category->id]) }}" class="text-xs font-bold text-primary bg-primary/5 px-3 py-1.5 rounded-lg flex items-center gap-1.5 hover:bg-primary/10 transition-colors">
                    <span class="material-symbols-outlined text-[14px]">inventory_2</span>
                    {{ $category->parts_count }} Parts
                </a>
                <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button onclick="openEditCategory({{ $category->id }}, '{{ addslashes($category->name) }}', '{{ addslashes($category->description ?? '') }}')" class="text-slate-400 hover:text-primary transition-colors p-1">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </button>
                    <form action="{{ route('categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete {{ $category->name }}? This may affect assigned parts.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-slate-400 hover:text-error transition-colors p-1">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full bg-surface-container-low p-14 rounded-2xl text-center border border-dashed border-outline-variant/30">
            <span class="material-symbols-outlined text-5xl text-on-surface-variant block mb-3">inventory</span>
            <h3 class="text-lg font-bold text-primary mb-1">No Categories Found</h3>
            <p class="text-sm text-on-surface-variant mb-4">Create your first category to organize parts.</p>
            <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')" class="bg-primary text-white px-5 py-2 rounded-lg text-sm font-bold hover:opacity-90 transition-all">
                Create Category
            </button>
        </div>
        @endforelse
    </div>

    @if($categories->hasPages())
    <div class="bg-surface-container-lowest px-6 py-4 rounded-xl border border-outline-variant/15">
        {{ $categories->links() }}
    </div>
    @endif
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">
        <h3 class="text-xl font-black text-primary mb-6">Create Category</h3>
        <form method="POST" action="{{ route('categories.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Name <span class="text-error">*</span></label>
                <input name="name" required placeholder="e.g. Pneumatics" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Description</label>
                <textarea name="description" rows="3" placeholder="Brief description of this category..." class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Category Modal -->
<div id="editCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4 p-8">
        <h3 class="text-xl font-black text-primary mb-6">Edit Category</h3>
        <form method="POST" id="editCategoryForm" class="space-y-4">
            @csrf @method('PUT')
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Name <span class="text-error">*</span></label>
                <input id="editCategoryName" name="name" required class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Description</label>
                <textarea id="editCategoryDesc" name="description" rows="3" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20 resize-none"></textarea>
            </div>
            <div class="flex gap-3 justify-end pt-2">
                <button type="button" onclick="document.getElementById('editCategoryModal').classList.add('hidden')" class="px-5 py-2.5 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm">Cancel</button>
                <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-xl font-bold text-sm hover:opacity-90 transition-all">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditCategory(id, name, description) {
    document.getElementById('editCategoryName').value = name;
    document.getElementById('editCategoryDesc').value = description;
    document.getElementById('editCategoryForm').action = '/categories/' + id;
    document.getElementById('editCategoryModal').classList.remove('hidden');
}
</script>
@endsection
