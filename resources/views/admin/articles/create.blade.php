@extends('admin.layouts.app')

@section('title', 'Create Article')

@section('content')
    <h1>Create Article</h1>
    <p><a href="{{ route('admin.articles.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
        @include('admin.articles._form')
    </form>
@endsection
