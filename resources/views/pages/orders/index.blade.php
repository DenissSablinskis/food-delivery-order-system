
@extends('layouts.app')

@section('main-class', 'orders-main')

@section('content')
    <div class="orders-grid">
        @foreach ($orders as $order)
            <article class="order-card">
                <div class="order-card-header">
                    <h2>Pasūtījums Nr. {{ $order->id }}</h2>
                    <span class="order-status">{{ $order->status }}</span>
                </div>

                <p class="order-date">
                    <strong>Pasūtīts:</strong> {{ $order->created_at }}
                </p>

                <p class="order-total">
                    <strong>Kopējā summa:</strong>
                    <span>{{ number_format($order->total, 2) }} €</span>
                </p>

                <a class="order-card-link" href="{{ route('orders.show', $order) }}">
                    Skatīt pasūtījumu
                </a>
            </article>
        @endforeach
    </div>
@endsection
