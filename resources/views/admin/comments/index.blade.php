@extends('admin.layouts.app')

@section('title', 'Comments - '.$article->title)

@section('content')
    <h1>Comments for: {{ $article->title }}</h1>

    <p>
        <a href="{{ route('admin.articles.show', $article) }}">Back to article</a>
        |
        <a href="{{ route('admin.articles.comments.create', $article) }}">Create new comment</a>
    </p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Email</th>
                <th>Content</th>
                <th>Approved</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($comments as $comment)
            <tr>
                <td>{{ $comment->id }}</td>
                <td>{{ $comment->author_name ?: ($comment->user?->name ?? 'N/A') }}</td>
                <td>{{ $comment->author_email ?: ($comment->user?->email ?? 'N/A') }}</td>
                <td>{{ \Illuminate\Support\Str::limit($comment->content, 120) }}</td>
                <td>{{ $comment->is_approved ? 'Yes' : 'No' }}</td>
                <td class="actions">
                    <a href="{{ route('admin.articles.comments.edit', ['article' => $article, 'comment' => $comment]) }}">Edit</a>
                    <form action="{{ route('admin.articles.comments.destroy', ['article' => $article, 'comment' => $comment]) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">No comments found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $comments->links() }}
    </div>
@endsection
