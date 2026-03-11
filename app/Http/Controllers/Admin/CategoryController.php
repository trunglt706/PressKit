<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CategoryStatus;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly CategoryService $categoryService
    ) {
    }

    /**
     * Display a paginated list of categories.
     */
    public function index(): View
    {
        $categories = $this->categoryService->paginateLatest();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $category = new Category();

        return view('admin.categories.create', compact('category'));
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(CategoryStatus::class)],
        ]);

        $this->categoryService->create($data);

        return redirect()->route('admin.categories.index')->with('status', 'Category created.');
    }

    /**
     * Show the form for editing a category.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update a category.
     */
    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,'.$category->id],
            'description' => ['nullable', 'string'],
            'status' => ['required', Rule::enum(CategoryStatus::class)],
        ]);

        $this->categoryService->update($category, $data);

        return redirect()->route('admin.categories.index')->with('status', 'Category updated.');
    }

    /**
     * Remove a category.
     */
    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->delete($category);

        return redirect()->route('admin.categories.index')->with('status', 'Category deleted.');
    }
}
