@extends('admin.layouts.app')

@section('title', 'Create Comment')

@section('content')
    <h1>Create Comment for: {{ $article->title }}</h1>
    <p><a href="{{ route('admin.articles.comments.index', $article) }}">Back to comments list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.articles.comments.store', $article) }}" method="POST">
        @include('admin.comments._form')
    </form>
@endsection
