@extends('admin.layouts.app')

@section('title', 'Create Tag')

@section('content')
    <h1>Create Tag</h1>
    <p><a href="{{ route('admin.tags.index') }}">Back to list</a></p>

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form action="{{ route('admin.tags.store') }}" method="POST">
        @include('admin.tags._form')
    </form>
@endsection
