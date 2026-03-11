@extends('admin.layouts.app')

@section('title', 'Edit Comment')

@section('content')
    <h1>Edit Comment #{{ $comment->id }} for: {{ $article->title }}</h1>
    <p><a href="{{ route('admin.articles.comments.index', $article) }}">Back to comments list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.articles.comments.update', ['article' => $article, 'comment' => $comment]) }}" method="POST">
        @method('PUT')
        @include('admin.comments._form')
    </form>
@endsection
