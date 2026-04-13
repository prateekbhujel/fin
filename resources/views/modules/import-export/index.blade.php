@php($title = 'Import & Export')
@extends('layouts.app')

@section('content')
    <x-admin.page-header title="Import & Export" description="Bring transaction data in from CSV/Excel or export filtered reports for office use." />

    <div class="grid gap-6 xl:grid-cols-2">
        <form method="POST" action="{{ route('import-export.import') }}" enctype="multipart/form-data" class="card p-6">
            @csrf
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Import Transactions</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Upload CSV, XLS, or XLSX files. Import runs immediately without queues.</p>

            <div class="mt-5">
                <x-input-label for="file" value="Import File" />
                <input id="file" name="file" type="file" class="form-input mt-1" required>
                <x-input-error :messages="$errors->get('file')" class="mt-2" />
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button class="btn-primary" type="submit">Import Now</button>
                <a href="{{ route('import-export.template') }}" class="btn-secondary">Download Template</a>
            </div>
        </form>

        <form method="GET" action="{{ route('import-export.export') }}" class="card p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Export Transactions</h2>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Apply optional filters before downloading the Excel export.</p>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div>
                    <x-input-label for="type" value="Type" />
                    <select id="type" name="type" class="form-select mt-1">
                        <option value="">All types</option>
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="category_id" value="Category" />
                    <select id="category_id" name="category_id" class="form-select mt-1">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="date_from" value="Date From" />
                    <input id="date_from" name="date_from" data-flatpickr class="form-input mt-1">
                </div>
                <div>
                    <x-input-label for="date_to" value="Date To" />
                    <input id="date_to" name="date_to" data-flatpickr class="form-input mt-1">
                </div>
            </div>

            <div class="mt-6">
                <button class="btn-primary" type="submit">Download Export</button>
            </div>
        </form>
    </div>
@endsection
