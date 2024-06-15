@extends('layout.app')

@section('content')
<div class="container">
    <h2>Shopping Cart</h2>
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if ($cartProducts->isEmpty())
    <div class="alert alert-info">Your cart is empty.</div>
    @else
    <div class="row">
        @foreach($cartProducts as $product)
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top uniform-image" alt="{{ $product->name }}">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $product->name }}</h5>
                    <p class="card-text">Category: {{ $product->category->name }}</p>
                    <p class="card-text">Quantity: {{ $product->quantity }}</p>
                    <form action="{{ route('cart.remove', ['productId' => $product->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger mt-auto">Remove from Cart</button>
                    </form>
                    @if(Auth::user() && Auth::user()->role == 'admin')
                    <a href="{{ route('cart.sendWhatsapp', ['productId' => $product->id, 'mobile' => $userMobile]) }}" class="btn btn-success mt-2">Send WhatsApp to {{ $userMobile }}</a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
<style>
    .uniform-image {
        width: 100%;
        /* Make the image take the full width of the container */
        height: 200px;
        /* Set a fixed height */
        object-fit: cover;
        /* Scale the image to cover the container, cropping if necessary */
    }
</style>
@endsection