@csrf

<div style="display: grid; gap: 0.75rem; max-width: 640px;">
    <label>
        Name
        <input type="text" name="name" value="{{ old('name', $category->name ?? '') }}" required style="width: 100%;" />
    </label>

    <label>
        Description
        <textarea name="description" rows="4" style="width: 100%;">{{ old('description', $category->description ?? '') }}</textarea>
    </label>

    <label>
        Status
        @php
            $selectedStatus = old('status', $category->status?->value ?? \App\Enums\CategoryStatus::ACTIVE->value);
        @endphp
        <select name="status" style="width: 100%;">
            <option value="{{ \App\Enums\CategoryStatus::ACTIVE->value }}" @selected($selectedStatus === \App\Enums\CategoryStatus::ACTIVE->value)>Active</option>
            <option value="{{ \App\Enums\CategoryStatus::INACTIVE->value }}" @selected($selectedStatus === \App\Enums\CategoryStatus::INACTIVE->value)>Inactive</option>
        </select>
    </label>

    <p class="muted">Slug is generated automatically and kept stable after creation.</p>

    <button type="submit">Save category</button>
</div>
