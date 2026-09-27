@extends('layouts.app')

@section('content')

    <h1>Order confirmed</h1>

    <p>Thank you for your order!</p>

    <p>Order number: #{{ $order->id }}</p>

    <p>Ordered products:</p>
    <ul>
        @foreach ($order->orderedProducts as $orderedProduct)
            <li>{{ $orderedProduct->product_count }} x {{ $orderedProduct->product->name }} - €{{ number_format($orderedProduct->unit_price_at_purchase, 2) }}</li>
        @endforeach
    </ul>

    <p>Total amount: €{{ number_format($order->orderedProducts->sum(function ($orderedProduct) {
        return $orderedProduct->product_count * $orderedProduct->unit_price_at_purchase;
    }), 2) }}</p>

    <p>Status: {{ $order->status }}</p>

@endsection