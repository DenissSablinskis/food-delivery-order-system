@extends('layouts.app')

@section('content')

    <div class="order-confirmation">

        <h1>{{ __('messages.orderConfirmed') }}</h1>

        <p>{{ __('messages.orderThankYou') }}</p>

        <div class="order-info">
            <p>
                <strong>{{ __('messages.orderNumber') }}:</strong>
                #{{ $order->id }}
            </p>

            <p>
                <strong>{{ __('messages.orderStatus') }}:</strong>
                {{ $order->status }}
            </p>

            <p>
                <strong>{{ __('messages.orderAddress') }}:</strong>
                {{ $order->delivery_address }}
            </p>
        </div>

        <h2>{{ __('messages.orderProducts') }}</h2>

        <div class="order-products">

            @foreach ($order->orderedProducts as $orderedProduct)

                <div class="order-product">

                    <span>
                        {{ $orderedProduct->product->name }}
                    </span>

                    <span>
                        {{ $orderedProduct->product_count }}
                        ×
                        €{{ number_format($orderedProduct->unit_price_at_purchase, 2) }}
                    </span>

                </div>

            @endforeach

        </div>

        <div class="order-total">

            <strong>{{ __('messages.orderTotal') }}:</strong>

            <strong>
                €{{ number_format($order->orderedProducts->sum(function ($orderedProduct) {
                    return $orderedProduct->product_count *
                           $orderedProduct->unit_price_at_purchase;
                }), 2) }}
            </strong>

        </div>

        <div class="order-actions">
            <a href="{{ route('orders.invoice', $order->id) }}">
                {{ __('messages.orderDownloadInvoice') }}
            </a>
        </div>

    </div>

@endsection