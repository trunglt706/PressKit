@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
    <h1>Categories</h1>

    <p><a href="{{ route('admin.categories.create') }}">Create new category</a></p>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->name }}</td>
                <td>{{ $category->slug }}</td>
                <td>{{ ucfirst($category->status?->value ?? '') }}</td>
                <td class="actions">
                    <a href="{{ route('admin.categories.edit', $category) }}">Edit</a>
                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" onsubmit="return confirm('Delete this category?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4">No categories found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div style="margin-top: 1rem;">
        {{ $categories->links() }}
    </div>
@endsection
