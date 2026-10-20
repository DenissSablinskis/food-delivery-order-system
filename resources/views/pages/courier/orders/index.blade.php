@extends('layouts.app')

@section('main-class', 'admin-orders-main')

@section('content')
    <section class="admin-orders-page">
        <div class="admin-orders-heading">
            <p class="admin-orders-eyebrow">Kurjera panelis</p>
            <h1>Piegāžu pārvaldība</h1>
            <p class="admin-orders-description">Pārskatiet jums piešķirtos pasūtījumus un atjauniniet piegādes statusu.</p>
        </div>

        @if (session('success'))
            <div class="admin-success-message" role="status">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="admin-error-message" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @forelse ($orders as $order)
            <article class="admin-order-card">
                <header class="admin-order-header">
                    <div>
                        <p class="admin-order-label">Pasūtījums</p>
                        <h2>Nr. {{ $order->id }}</h2>
                    </div>
                    <span class="admin-order-status" data-status="{{ $order->status }}">
                        {{ __('messages.order_status.' . $order->status) }}
                    </span>
                </header>

                <div class="admin-order-details">
                    <div>
                        <span class="admin-order-detail-label">Pasūtītājs</span>
                        <span class="admin-order-detail-value">{{ $order->user->name }} {{ $order->user->surname }}</span>
                    </div>
                    <div>
                        <span class="admin-order-detail-label">Pasūtījuma datums</span>
                        <span class="admin-order-detail-value">{{ $order->created_at }}</span>
                    </div>
                    <div>
                        <span class="admin-order-detail-label">Piegādes adrese</span>
                        <span class="admin-order-detail-value">{{ $order->delivery_address }}</span>
                    </div>
                </div>

                @if ($order->status === 'in_process')
                    <form class="admin-order-form" action="{{ route('courier.orders.start-delivery', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="admin-order-form-controls admin-courier-action-controls">
                            <button type="submit">
                                <span class="bi bi-truck" aria-hidden="true"></span>
                                Sākt piegādi
                            </button>
                        </div>
                    </form>
                @endif

                @if ($order->status === 'delivering')
                    <form class="admin-order-form" action="{{ route('courier.orders.complete', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="admin-order-form-controls admin-courier-action-controls">
                            <button type="submit">
                                <span class="bi bi-check-lg" aria-hidden="true"></span>
                                Atzīmēt kā piegādātu
                            </button>
                        </div>
                    </form>
                @endif
            </article>
        @empty
            <div class="admin-orders-empty">
                <span class="bi bi-receipt" aria-hidden="true"></span>
                <h2>Pasūtījumu vēl nav</h2>
                <p>Jums piešķirtie pasūtījumi būs redzami šeit.</p>
            </div>
        @endforelse
    </section>
@endsection
