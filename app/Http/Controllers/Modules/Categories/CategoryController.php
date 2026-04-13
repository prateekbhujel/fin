<?php

namespace App\Http\Controllers\Modules\Categories;

use App\Http\Controllers\Controller;
use App\Http\Requests\Modules\Categories\StoreCategoryRequest;
use App\Http\Requests\Modules\Categories\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function index()
    {
        $query = Category::query()->latest();

        if ($search = request('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type = request('type')) {
            $query->where('type', $type);
        }

        return view('modules.categories.index', [
            'categories' => $query->paginate(10)->withQueryString(),
            'types' => config('finance.transaction_types'),
        ]);
    }

    public function create()
    {
        return view('modules.categories.create', [
            'types' => config('finance.transaction_types'),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        Category::create([
            ...$request->validated(),
            'is_active' => (bool) $request->boolean('is_active', true),
        ]);

        return redirect()->route('categories.index')->with('status', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return redirect()->route('categories.edit', $category);
    }

    public function edit(Category $category)
    {
        return view('modules.categories.edit', [
            'category' => $category,
            'types' => config('finance.transaction_types'),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update([
            ...$request->validated(),
            'is_active' => (bool) $request->boolean('is_active', false),
        ]);

        return redirect()->route('categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->transactions()->exists()) {
            throw ValidationException::withMessages([
                'category' => 'This category is already linked to transactions.',
            ]);
        }

        $category->delete();

        return redirect()->route('categories.index')->with('status', 'Category deleted successfully.');
    }
}
