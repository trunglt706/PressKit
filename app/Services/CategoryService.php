<?php

namespace App\Services;

use App\Enums\CategoryStatus;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class CategoryService
{
    public function __construct(
        private readonly CategoryRepository $categoryRepository
    ) {
    }

    /**
     * Paginate categories ordered by creation date descending (admin listing).
     */
    public function paginateLatest(int $perPage = 15): LengthAwarePaginator
    {
        return $this->categoryRepository->paginateLatest($perPage);
    }

    /**
     * Create a new category with the given name, description, and status.
     */
    public function create(array $data): Category
    {
        return $this->categoryRepository->create([
            'name' => trim((string) ($data['name'] ?? '')),
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? CategoryStatus::ACTIVE->value,
        ]);
    }

    /**
     * Update an existing category with the given name, description, and status.
     */
    public function update(Category $category, array $data): Category
    {
        return $this->categoryRepository->update($category, [
            'name' => trim((string) ($data['name'] ?? '')),
            'description' => $data['description'] ?? null,
            'status' => $data['status'] ?? $category->status?->value,
        ]);
    }

    /**
     * Delete the given category from the database.
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }
}
