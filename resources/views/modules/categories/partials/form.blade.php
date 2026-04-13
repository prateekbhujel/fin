<div class="grid gap-5 md:grid-cols-2">
    <div>
        <x-input-label for="name" value="Category Name" />
        <x-text-input id="name" name="name" type="text" class="mt-1" :value="old('name', $category->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="type" value="Type" />
        <select id="type" name="type" class="form-select mt-1">
            @foreach ($types as $key => $label)
                <option value="{{ $key }}" @selected(old('type', $category->type ?? '') === $key)>{{ $label }}</option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <x-input-label for="description" value="Description" />
        <textarea id="description" name="description" class="form-textarea mt-1">{{ old('description', $category->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <label class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
            <input type="checkbox" name="is_active" value="1" class="form-checkbox" @checked(old('is_active', $category->is_active ?? true))>
            Keep this category active
        </label>
    </div>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('categories.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary" type="submit">{{ $submitLabel }}</button>
</div>
