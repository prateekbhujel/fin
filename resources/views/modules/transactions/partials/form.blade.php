<div class="grid gap-5 md:grid-cols-2">
    <div class="md:col-span-2">
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" class="mt-1" :value="old('title', $transaction->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Type" />
        <select id="type" name="type" class="form-select mt-1">
            @foreach ($types as $key => $label)
                <option value="{{ $key }}" @selected(old('type', $transaction->type ?? request('type')) === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="category_id" value="Category" />
        <select id="category_id" name="category_id" class="form-select mt-1">
            <option value="">Select category</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $transaction->category_id ?? request('category_id')) === (string) $category->id)>
                    {{ $category->name }} ({{ ucfirst($category->type) }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="amount" value="Amount" />
        <x-text-input id="amount" name="amount" type="number" step="0.01" class="mt-1" :value="old('amount', $transaction->amount ?? '')" required />
        <x-input-error :messages="$errors->get('amount')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="payment_method" value="Payment Method" />
        <select id="payment_method" name="payment_method" class="form-select mt-1">
            @foreach ($paymentMethods as $key => $label)
                <option value="{{ $key }}" @selected(old('payment_method', $transaction->payment_method ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('payment_method')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="transaction_date" value="Transaction Date (AD)" />
        <input id="transaction_date" name="transaction_date" type="text" data-flatpickr value="{{ old('transaction_date', optional($transaction->transaction_date ?? null)->format('Y-m-d')) }}" class="form-input mt-1" placeholder="YYYY-MM-DD">
        <x-input-error :messages="$errors->get('transaction_date')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="transaction_date_bs" value="Transaction Date (BS)" />
        <x-text-input id="transaction_date_bs" name="transaction_date_bs" type="text" class="mt-1" :value="old('transaction_date_bs', $transaction->transaction_bs ?? '')" placeholder="2082-01-15" />
        <x-input-error :messages="$errors->get('transaction_date_bs')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="reference_no" value="Reference No" />
        <x-text-input id="reference_no" name="reference_no" type="text" class="mt-1" :value="old('reference_no', $transaction->reference_no ?? '')" />
        <x-input-error :messages="$errors->get('reference_no')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="notes" value="Notes" />
        <textarea id="notes" name="notes" class="form-textarea mt-1">{{ old('notes', $transaction->notes ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('notes')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="documents" value="Attach Documents" />
        <input id="documents" name="documents[]" type="file" multiple class="form-input mt-1">
        <x-input-error :messages="$errors->get('documents')" class="mt-2" />
        <x-input-error :messages="$errors->get('documents.*')" class="mt-2" />
    </div>

    @if (! empty($transaction?->documents?->count()))
        <div class="md:col-span-2 rounded-2xl border border-gray-200 p-4 dark:border-gray-800">
            <div class="mb-3 text-sm font-semibold text-gray-900 dark:text-white">Existing Documents</div>
            <div class="flex flex-wrap gap-3">
                @foreach ($transaction->documents as $document)
                    <a href="{{ route('documents.download', $document) }}" class="btn-secondary">{{ $document->original_name }}</a>
                @endforeach
            </div>
        </div>
    @endif
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('transactions.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary" type="submit">{{ $submitLabel }}</button>
</div>
