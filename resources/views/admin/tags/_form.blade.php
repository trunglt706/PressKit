@csrf

<div style="display: grid; gap: 0.75rem; max-width: 560px;">
    <label>
        Name
        <input type="text" name="name" value="{{ old('name', $tag->name ?? '') }}" required style="width: 100%;" />
    </label>

    <p class="muted">Slug is generated automatically when creating a tag.</p>

    <button type="submit">Save tag</button>
</div>
