@extends('admin.layouts.app')

@section('title', 'Articles')

@section('content')
    <h1>Articles</h1>

    <p><a href="{{ route('admin.articles.create') }}">Create new article</a></p>

    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Category</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($articles as $article)
            <tr>
                <td>{{ $article->title }}</td>
                <td>{{ $article->category?->name ?? 'N/A' }}</td>
                <td>{{ $article->author?->name ?? 'N/A' }}</td>
                <td>{{ ucfirst($article->status?->value ?? '') }}</td>
                <td class="actions">
                    <a href="{{ route('admin.articles.show', $article) }}">View</a>
                    <a href="{{ route('admin.articles.edit', $article) }}">Edit</a>
                    <a href="{{ route('admin.articles.history', $article) }}">History</a>
                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Delete this article?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No articles found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $articles->links() }}
    </div>
@endsection
