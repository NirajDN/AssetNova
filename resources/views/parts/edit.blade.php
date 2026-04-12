@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('parts.index') }}" class="text-xs font-bold text-on-surface-variant hover:text-primary flex items-center gap-1 mb-4 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Parts Directory
        </a>
        <h2 class="text-3xl font-black text-primary tracking-tight">Edit Part</h2>
        <p class="text-on-surface-variant mt-1 font-mono text-sm">{{ $part->sku }}</p>
    </div>

    <form method="POST" action="{{ route('parts.update', $part) }}" class="space-y-6" enctype="multipart/form-data">
        @csrf @method('PUT')
        @if($errors->any())
        <div class="bg-error-container/30 border border-error/20 text-on-error-container px-5 py-4 rounded-xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2"><span class="material-symbols-outlined text-sm text-error">error</span>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/20">Identification</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">SKU <span class="text-error">*</span></label>
                    <input name="sku" value="{{ old('sku', $part->sku) }}" required class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm font-mono focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Part Name <span class="text-error">*</span></label>
                    <input name="name" value="{{ old('name', $part->name) }}" required class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Description</label>
                <textarea name="description" rows="2" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20 resize-none">{{ old('description', $part->description) }}</textarea>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/20">Classification</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Category</label>
                    <select name="category_id" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                        <option value="">— None —</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('category_id', $part->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Supplier</label>
                    <select name="supplier_id" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                        <option value="">— None —</option>
                        @foreach($suppliers as $sup)
                        <option value="{{ $sup->id }}" {{ old('supplier_id', $part->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Warehouse Location</label>
                <input name="location" value="{{ old('location', $part->location) }}" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
            </div>
        </div>

        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-6">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/20">Stock & Pricing</h3>
            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Unit Cost (₹)</label>
                    <input name="cost" value="{{ old('cost', $part->cost) }}" type="number" min="0" step="0.01" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Stock Qty</label>
                    <input name="stock_quantity" value="{{ old('stock_quantity', $part->stock_quantity) }}" type="number" min="0" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
                <div>
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Min. Threshold</label>
                    <input name="min_threshold" value="{{ old('min_threshold', $part->min_threshold) }}" type="number" min="0" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
                </div>
            </div>
        </div>


        <!-- Part Image -->
        <div class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-4">
            <h3 class="text-xs font-black uppercase tracking-widest text-on-surface-variant pb-3 border-b border-outline-variant/20">Part Image</h3>
            <div class="flex items-start gap-6">
                <!-- Current / Preview box -->
                <div id="imagePreview" class="w-28 h-28 rounded-xl bg-surface-container border border-outline-variant/20 overflow-hidden flex-shrink-0">
                    @if($part->image_url)
                        <img src="{{ asset($part->image_url) }}" class="w-full h-full object-cover" />
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-3xl text-on-surface-variant">add_photo_alternate</span>
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">{{ $part->image_url ? 'Replace Image' : 'Upload Image' }}</label>
                    <label for="part_image" class="flex items-center gap-3 bg-surface-container-low rounded-xl px-4 py-3 cursor-pointer hover:bg-surface-container transition-colors border border-dashed border-outline-variant/30 hover:border-primary/30">
                        <span class="material-symbols-outlined text-primary">upload_file</span>
                        <div>
                            <p class="text-sm font-bold text-on-surface">Click to {{ $part->image_url ? 'replace' : 'upload' }} image</p>
                            <p class="text-[11px] text-on-surface-variant">PNG, JPG up to 2MB</p>
                        </div>
                    </label>
                    <input id="part_image" name="part_image" type="file" accept="image/*" class="hidden" onchange="previewPartImage(this)"/>
                    <p id="fileName" class="text-[11px] text-on-surface-variant mt-2 hidden"></p>
                </div>
            </div>
        </div>

        <div class="flex gap-4 justify-end">
            <a href="{{ route('parts.index') }}" class="px-6 py-3 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm hover:bg-surface-container-high transition-all">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span> Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function previewPartImage(input) {
    const preview = document.getElementById('imagePreview');
    const label   = document.getElementById('fileName');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover" />`;
        };
        reader.readAsDataURL(input.files[0]);
        label.textContent = '✓ ' + input.files[0].name;
        label.classList.remove('hidden');
    }
}
</script>
@endpush
@endsection
