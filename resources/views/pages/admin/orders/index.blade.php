@extends('layouts.app')

@section('content')

    @foreach ($orders as $order)

        <div>Pasūtījums Nr. {{ $order->id }}</div>
        <div>Pasūtījuma datums {{ $order->created_at }}</div>
        <div>Statuss {{ __('messages.order_status.' . $order->status) }}</div>
        
        @foreach ($order->orderedProducts as $orderedProduct) 

            <div>{{ $orderedProduct->product->name }} {{ $orderedProduct->product_count }} x {{ $orderedProduct->unit_price_at_purchase }} €</div>

        @endforeach

        <div>Kopējā summa: {{ number_format($order->total, 2, ',', ' ') }} €</div>

    @endforeach

@endsection