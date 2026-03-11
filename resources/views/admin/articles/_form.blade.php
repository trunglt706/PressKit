@csrf

<div style="display: grid; gap: 0.75rem; max-width: 720px;">
    <label>
        Title
        <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required style="width: 100%;" />
    </label>

    <label>
        Category
        <select name="category_id" style="width: 100%;">
            <option value="">-- Select category --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected((string) old('category_id', $article->category_id ?? '') === (string) $category->id)>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Excerpt
        <textarea name="excerpt" rows="3" style="width: 100%;">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
    </label>

    <label>
        Content
        <textarea name="content" rows="8" style="width: 100%;" required>{{ old('content', $article->content ?? '') }}</textarea>
    </label>

    <label>
        Status
        @php
            $selectedStatus = old('status', $article->status?->value ?? \App\Enums\ArticleStatus::DRAFT->value);
        @endphp
        <select name="status" style="width: 100%;">
            <option value="{{ \App\Enums\ArticleStatus::DRAFT->value }}" @selected($selectedStatus === \App\Enums\ArticleStatus::DRAFT->value)>Draft</option>
            <option value="{{ \App\Enums\ArticleStatus::PUBLISHED->value }}" @selected($selectedStatus === \App\Enums\ArticleStatus::PUBLISHED->value)>Published</option>
        </select>
    </label>

    <label>
        Published at
        <input
            type="datetime-local"
            name="published_at"
            value="{{ old('published_at', isset($article?->published_at) ? $article->published_at->format('Y-m-d\\TH:i') : '') }}"
            style="width: 100%;"
        />
    </label>

    <label>
        SEO title
        <input type="text" name="seo_title" value="{{ old('seo_title', $article->seo?->title ?? '') }}" style="width: 100%;" />
    </label>

    <label>
        SEO description
        <textarea name="seo_description" rows="3" style="width: 100%;">{{ old('seo_description', $article->seo?->description ?? '') }}</textarea>
    </label>

    <label>
        SEO keywords (comma separated)
        <input type="text" name="seo_keywords" value="{{ old('seo_keywords', $article->seo?->keywords ?? '') }}" style="width: 100%;" />
    </label>

    <label>
        SEO OG image URL
        <input type="url" name="seo_og_image" value="{{ old('seo_og_image', $article->seo?->og_image ?? '') }}" style="width: 100%;" />
    </label>

    <label>
        Featured image
        <input type="file" name="featured_image" accept="image/*" style="width: 100%;" />
    </label>

    <label>
        Gallery images
        <input type="file" name="gallery[]" accept="image/*" multiple style="width: 100%;" />
    </label>

    <label>
        Attachments
        <input type="file" name="attachments[]" multiple style="width: 100%;" />
    </label>

    <label>
        Version files (for ArticleVersion)
        <input type="file" name="version_files[]" multiple style="width: 100%;" />
    </label>

    <button type="submit">Save article</button>
</div>
