<div class="grid gap-5">
    <div>
        <x-input-label for="title" value="Title" />
        <x-text-input id="title" name="title" type="text" class="mt-1" :value="old('title', $announcement->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="content" value="Content" />
        <textarea id="content" name="content" class="form-textarea mt-1" required>{{ old('content', $announcement->content ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('content')" class="mt-2" />
    </div>

    <div class="grid gap-5 md:grid-cols-2">
        <div>
            <x-input-label for="published_at" value="Publish At" />
            <input id="published_at" name="published_at" data-flatpickr value="{{ old('published_at', optional($announcement->published_at ?? null)->format('Y-m-d')) }}" class="form-input mt-1">
            <x-input-error :messages="$errors->get('published_at')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="expires_at" value="Expires At" />
            <input id="expires_at" name="expires_at" data-flatpickr value="{{ old('expires_at', optional($announcement->expires_at ?? null)->format('Y-m-d')) }}" class="form-input mt-1">
            <x-input-error :messages="$errors->get('expires_at')" class="mt-2" />
        </div>
    </div>

    <label class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-300">
        <input type="checkbox" name="is_published" value="1" class="form-checkbox" @checked(old('is_published', $announcement->is_published ?? true))>
        Publish announcement immediately
    </label>
</div>

<div class="mt-6 flex items-center justify-end gap-3">
    <a href="{{ route('announcements.index') }}" class="btn-secondary">Cancel</a>
    <button class="btn-primary" type="submit">{{ $submitLabel }}</button>
</div>
