@extends('layouts.app')

@section('content')

    @foreach($products as $product) 
        <div class='product-card'>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            <p>{{ $product->name }}</p>
            <p>Cena: {{ $product->unit_price }} / {{ $product->unit }}</p>
            <form action="#" method="POST">
                @csrf

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                <div class="quantity-control">
                    <button type="button" class="quantity-minus">−</button>

                    <input
                        type="number"
                        name="quantity"
                        value="1"
                        min="1"
                        max="{{ $product->stock_quantity }}"
                        class="quantity-input"
                    >

                    <button type="button" class="quantity-plus">+</button>
                </div>

                <button type="submit">
                    Pievienot grozam
                </button>
            </form>
        </div>
    @endforeach

@endsection