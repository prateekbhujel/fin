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
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Apply optional A.D. or B.S. filters before downloading the Excel export.</p>

            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <div class="md:col-span-2">
                    <x-input-label for="search" value="Search" />
                    <input id="search" name="search" value="{{ request('search') }}" class="form-input mt-1" placeholder="Title, reference number, or notes">
                </div>
                <div>
                    <x-input-label for="type" value="Type" />
                    <select id="type" name="type" class="form-select mt-1">
                        <option value="">All types</option>
                        @foreach ($types as $key => $label)
                            <option value="{{ $key }}" @selected(request('type') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="category_id" value="Category" />
                    <select id="category_id" name="category_id" class="form-select mt-1">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="date_from" value="Date From (AD)" />
                    <input id="date_from" name="date_from" data-flatpickr value="{{ request('date_from') }}" class="form-input mt-1">
                </div>
                <div>
                    <x-input-label for="date_to" value="Date To (AD)" />
                    <input id="date_to" name="date_to" data-flatpickr value="{{ request('date_to') }}" class="form-input mt-1">
                </div>
                <div>
                    <x-input-label for="from_bs" value="Date From (BS)" />
                    <input id="from_bs" name="from_bs" value="{{ request('from_bs') }}" class="form-input mt-1" placeholder="2082-01-01">
                </div>
                <div>
                    <x-input-label for="to_bs" value="Date To (BS)" />
                    <input id="to_bs" name="to_bs" value="{{ request('to_bs') }}" class="form-input mt-1" placeholder="2082-01-30">
                </div>
            </div>

            <div class="mt-6 flex items-center gap-3">
                <button class="btn-primary" type="submit">Download Export</button>
                <a href="{{ route('import-export.index') }}" class="btn-secondary">Reset</a>
            </div>
        </form>
    </div>
@endsection
