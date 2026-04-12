@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('suppliers.index') }}" class="text-xs font-bold text-on-surface-variant hover:text-primary flex items-center gap-1 mb-4 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Suppliers
        </a>
        <h2 class="text-3xl font-black text-primary tracking-tight">Edit Supplier</h2>
        <p class="text-on-surface-variant mt-1 font-medium">{{ $supplier->name }}</p>
    </div>

    <form method="POST" action="{{ route('suppliers.update', $supplier) }}" class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-6">
        @csrf @method('PUT')
        @if($errors->any())
        <div class="bg-error-container/30 border border-error/20 text-on-error-container px-5 py-4 rounded-xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2"><span class="material-symbols-outlined text-sm text-error">error</span>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Company Name <span class="text-error">*</span></label>
            <input name="name" value="{{ old('name', $supplier->name) }}" required class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Email</label>
                <input name="contact_email" value="{{ old('contact_email', $supplier->contact_email) }}" type="email" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
            </div>
            <div>
                <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Phone</label>
                <input name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
            </div>
        </div>
        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">City / Address</label>
            <input name="address" value="{{ old('address', $supplier->address) }}" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>
        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Performance Rating (0–5)</label>
            <input name="rating" value="{{ old('rating', $supplier->rating) }}" type="number" min="0" max="5" step="0.1" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>
        <div class="flex gap-4 justify-end">
            <a href="{{ route('suppliers.index') }}" class="px-6 py-3 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm hover:bg-surface-container-high transition-all">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">save</span> Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
