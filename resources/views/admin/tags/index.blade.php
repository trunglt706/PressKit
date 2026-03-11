@extends('admin.layouts.app')

@section('title', 'Tags')

@section('content')
    <h1>Tags</h1>

    <p><a href="{{ route('admin.tags.create') }}">Create new tag</a></p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($tags as $tag)
            <tr>
                <td>{{ $tag->name }}</td>
                <td>{{ $tag->slug }}</td>
                <td class="actions">
                    <a href="{{ route('admin.tags.edit', $tag) }}">Edit</a>
                    <form action="{{ route('admin.tags.destroy', $tag) }}" method="POST" onsubmit="return confirm('Delete this tag?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="3">No tags found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $tags->links() }}
    </div>
@endsection
