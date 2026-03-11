@extends('admin.layouts.app')

@section('title', 'Create Category')

@section('content')
    <h1>Create Category</h1>
    <p><a href="{{ route('admin.categories.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.categories.store') }}" method="POST">
        @include('admin.categories._form')
    </form>
@endsection
