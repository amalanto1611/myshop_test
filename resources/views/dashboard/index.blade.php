@extends('layout.app')

@section('content')
<div class="container">
@if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@if(auth()->check() && auth()->user()->role == 'admin')
        <div class="mb-4">
            <a href="{{ route('product.addview') }}" class="btn btn-success">Add Products</a>
        </div>
    @endif
@if($products->isEmpty())
        <div class="alert alert-info">No products available.</div>
    @else
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4">
                <div class="card mb-4" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}" data-product-description="{{ $product->description }}">
                <img src="{{ asset('images/products/' . $product->image) }}" class="card-img-top uniform-image" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->category->name }}</p>
                        <button class="btn btn-primary" onclick="showProductDetails('{{ $product->id }}')">Details</button>
                        @if(auth()->check() && auth()->user()->role == 'admin')
                        <a href="{{ route('products.edit', ['id' => $product->id]) }}" class="btn btn-success">Edit</a>

                        <form action="{{ route('products.destroy', ['id' => $product->id]) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?')">Delete</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Product Details Modal -->
<div id="productModal" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="productDescription"></p>
                <p><strong>ID: </strong><span id="productId"></span></p>
                <form id="addToCartForm" method="POST" action="{{ route('cart.add', ['productId' => $product->id]) }}">
                    @csrf
                    <input type="hidden" name="productId" id="modalProductId">
                    <button type="submit" class="btn btn-success">Add to Cart</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.uniform-image {
    width: 100%; /* Make the image take the full width of the container */
    height: 200px; /* Set a fixed height */
    object-fit: cover; /* Scale the image to cover the container, cropping if necessary */
}
</style>
<script>
function showProductDetails(productId) {
    var productCard = document.querySelector('[data-product-id="' + productId + '"]');
    
    if (productCard) {
        var productName = productCard.getAttribute('data-product-name');
        var productDescription = productCard.getAttribute('data-product-description');

        document.getElementById('productTitle').innerText = productName;
        document.getElementById('productDescription').innerText = productDescription;
        document.getElementById('productId').innerText = productId;

        // Update the hidden input field value for the add to cart form
        document.getElementById('modalProductId').value = productId;

        // Show the modal
        $('#productModal').modal('show');
    }
}

document.getElementById('addToCartForm').addEventListener('submit', function(event) {
    var form = event.target;
    var productId = document.getElementById('modalProductId').value;

    form.action = "{{ url('cart/add') }}" + '/' + productId;
});
</script>
@endsection
