@extends('layout.app')

@section('content')
    <h1>Products in {{ $category['name'] }}</h1>
    <ul>
        @foreach ($products as $product)
            <li>{{ $product['name'] }}: {{ $product['description'] }}</li>
        @endforeach
    </ul>
    <a href="{{ route('categories.list') }}">Back to Categories</a>
@endsection
