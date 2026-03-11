@csrf

<div style="display: grid; gap: 0.75rem; max-width: 720px;">
    <label>
        User ID (optional)
        <input type="number" name="user_id" value="{{ old('user_id', $comment->user_id ?? '') }}" min="1" style="width: 100%;" />
    </label>

    <label>
        Author name
        <input type="text" name="author_name" value="{{ old('author_name', $comment->author_name ?? '') }}" style="width: 100%;" />
    </label>

    <label>
        Author email
        <input type="email" name="author_email" value="{{ old('author_email', $comment->author_email ?? '') }}" style="width: 100%;" />
    </label>

    <label>
        Content
        <textarea name="content" rows="6" required style="width: 100%;">{{ old('content', $comment->content ?? '') }}</textarea>
    </label>

    <label>
        <input type="checkbox" name="is_approved" value="1" @checked((bool) old('is_approved', $comment->is_approved ?? false)) />
        Approved
    </label>

    <button type="submit">Save comment</button>
</div>
