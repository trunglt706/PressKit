@extends('admin.layouts.app')

@section('title', 'Edit Article')

@section('content')
    <h1>Edit Article</h1>
    <p><a href="{{ route('admin.articles.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
        @method('PUT')
        @include('admin.articles._form')
    </form>
@endsection
