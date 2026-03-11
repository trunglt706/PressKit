@extends('admin.layouts.app')

@section('title', 'Edit Tag')

@section('content')
    <h1>Edit Tag</h1>
    <p><a href="{{ route('admin.tags.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @method('PUT')
        @include('admin.tags._form')
    </form>
@endsection
