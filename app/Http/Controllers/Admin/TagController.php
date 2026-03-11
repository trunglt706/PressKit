<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct(
        private readonly TagService $tagService
    ) {
    }

    /**
     * Display a paginated list of tags.
     */
    public function index(): View
    {
        $tags = $this->tagService->paginateLatest();

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new tag.
     */
    public function create(): View
    {
        $tag = new Tag();

        return view('admin.tags.create', compact('tag'));
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name'],
        ]);

        $this->tagService->create($data);

        return redirect()->route('admin.tags.index')->with('status', 'Tag created.');
    }

    /**
     * Show the form for editing a tag.
     */
    public function edit(Tag $tag): View
    {
        return view('admin.tags.edit', compact('tag'));
    }

    /**
     * Update a tag.
     */
    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:tags,name,'.$tag->id],
        ]);

        $this->tagService->update($tag, $data);

        return redirect()->route('admin.tags.index')->with('status', 'Tag updated.');
    }

    /**
     * Remove a tag.
     */
    public function destroy(Tag $tag): RedirectResponse
    {
        $this->tagService->delete($tag);

        return redirect()->route('admin.tags.index')->with('status', 'Tag deleted.');
    }
}
