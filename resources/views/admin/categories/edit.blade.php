@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
    <h1>Edit Category</h1>
    <p><a href="{{ route('admin.categories.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @method('PUT')
        @include('admin.categories._form')
    </form>
@endsection
