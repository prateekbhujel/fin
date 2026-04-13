<?php

namespace App\Modules\Categories\Services;

use App\Models\Category;
use App\Modules\Categories\DTOs\CategoryData;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

class CategoryService
{
    public function paginate(array $filters = [], int $perPage = 10): LengthAwarePaginator
    {
        $query = Category::query()->latest();

        if ($search = $filters['search'] ?? null) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($type = $filters['type'] ?? null) {
            $query->where('type', $type);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function types(): array
    {
        return config('finance.transaction_types');
    }

    public function store(CategoryData $data): Category
    {
        return Category::create($data->toArray());
    }

    public function update(Category $category, CategoryData $data): Category
    {
        $category->update($data->toArray());

        return $category->refresh();
    }

    public function delete(Category $category): void
    {
        if ($category->transactions()->exists()) {
            throw ValidationException::withMessages([
                'category' => 'This category is already linked to transactions.',
            ]);
        }

        $category->delete();
    }
}
