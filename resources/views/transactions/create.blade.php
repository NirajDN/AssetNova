@extends('layouts.app')

@section('content')
<div class="max-w-xl mx-auto">
    <div class="mb-8">
        <a href="{{ route('transactions.index') }}" class="text-xs font-bold text-on-surface-variant hover:text-primary flex items-center gap-1 mb-4 transition-colors">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Back to Transactions
        </a>
        <h2 class="text-3xl font-black text-primary tracking-tight">Record Transaction</h2>
        <p class="text-on-surface-variant mt-1">Log a stock in or stock out movement. Stock levels will update automatically.</p>
    </div>

    <form method="POST" action="{{ route('transactions.store') }}" class="bg-surface-container-lowest rounded-2xl p-8 shadow-sm border border-outline-variant/10 space-y-6">
        @csrf
        @if($errors->any())
        <div class="bg-error-container/30 border border-error/20 text-on-error-container px-5 py-4 rounded-xl text-sm space-y-1">
            @foreach($errors->all() as $error)
                <p class="flex items-center gap-2"><span class="material-symbols-outlined text-sm text-error">error</span>{{ $error }}</p>
            @endforeach
        </div>
        @endif

        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Part <span class="text-error">*</span></label>
            <select name="part_id" required class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20">
                <option value="">— Select Part —</option>
                @foreach($parts as $part)
                <option value="{{ $part->id }}" {{ old('part_id') == $part->id ? 'selected' : '' }}>
                    {{ $part->name }} ({{ $part->sku }}) — Stock: {{ number_format($part->stock_quantity) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Transaction Type <span class="text-error">*</span></label>
            <div class="flex gap-3">
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="in" class="sr-only peer" {{ old('type') === 'in' ? 'checked' : '' }}>
                    <div class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 border-outline-variant/20 peer-checked:border-primary peer-checked:bg-primary/5 transition-all">
                        <span class="material-symbols-outlined text-primary">south_east</span>
                        <span class="font-bold text-sm text-primary">Stock In</span>
                    </div>
                </label>
                <label class="flex-1 cursor-pointer">
                    <input type="radio" name="type" value="out" class="sr-only peer" {{ old('type') === 'out' ? 'checked' : '' }}>
                    <div class="flex items-center justify-center gap-2 p-4 rounded-xl border-2 border-outline-variant/20 peer-checked:border-error peer-checked:bg-error/5 transition-all">
                        <span class="material-symbols-outlined text-error">north_east</span>
                        <span class="font-bold text-sm text-error">Stock Out</span>
                    </div>
                </label>
            </div>
        </div>

        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Quantity <span class="text-error">*</span></label>
            <input name="quantity" value="{{ old('quantity') }}" required type="number" min="1" placeholder="e.g. 100" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20"/>
        </div>

        <div>
            <label class="block text-xs font-bold text-on-surface-variant mb-2 uppercase tracking-wider">Notes / Reference</label>
            <textarea name="notes" rows="3" placeholder="e.g. Received from Tata Steel, PO #1234..." class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-primary/20 resize-none">{{ old('notes') }}</textarea>
        </div>

        <div class="flex gap-4 justify-end pt-2">
            <a href="{{ route('transactions.index') }}" class="px-6 py-3 bg-surface-container text-on-surface-variant rounded-xl font-bold text-sm hover:bg-surface-container-high transition-all">Cancel</a>
            <button type="submit" class="px-8 py-3 bg-primary text-white rounded-xl font-bold text-sm shadow-lg hover:opacity-90 transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">receipt_long</span> Record Transaction
            </button>
        </div>
    </form>
</div>
@endsection
