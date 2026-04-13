<?php

namespace App\Modules\Categories\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Modules\Categories\DTOs\CategoryData;
use App\Modules\Categories\Http\Requests\StoreCategoryRequest;
use App\Modules\Categories\Http\Requests\UpdateCategoryRequest;
use App\Modules\Categories\Services\CategoryService;
use Illuminate\Validation\ValidationException;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categories,
    ) {
    }

    public function index()
    {
        return view('modules.categories.index', [
            'categories' => $this->categories->paginate(request()->only(['search', 'type'])),
            'types' => $this->categories->types(),
        ]);
    }

    public function create()
    {
        return view('modules.categories.create', [
            'types' => $this->categories->types(),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $this->categories->store(CategoryData::fromArray(
            data: $request->validated(),
            isActive: $request->boolean('is_active', true),
        ));

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
            'types' => $this->categories->types(),
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $this->categories->update($category, CategoryData::fromArray(
            data: $request->validated(),
            isActive: $request->boolean('is_active'),
        ));

        return redirect()->route('categories.index')->with('status', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        try {
            $this->categories->delete($category);
        } catch (ValidationException $exception) {
            return back()->with('error', $exception->errors()['category'][0] ?? 'Unable to delete this category.');
        }

        return redirect()->route('categories.index')->with('status', 'Category deleted successfully.');
    }
}
