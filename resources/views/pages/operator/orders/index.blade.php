@extends('layouts.app')

@section('main-class', 'admin-orders-main')

@section('content')
    <section class="admin-orders-page">
        <div class="admin-orders-heading">
            <p class="admin-orders-eyebrow">Operatora panelis</p>
            <h1>Pasūtījumu apstrāde</h1>
            <p class="admin-orders-description">Pārskatiet pasūtījumus, uzņemieties to apstrādi un piešķiriet kurjerus.</p>
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

        @error('delivery_person_id')
            <div class="admin-error-message" role="alert">
                {{ $message }}
            </div>
        @enderror

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

                @if ($order->status === 'new')
                    <form class="admin-order-form" action="{{ route('operator.orders.start-processing', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="admin-order-form-controls">
                            <button type="submit">Paņemt apstrādē</button>
                        </div>
                    </form>
                @endif

                <form class="admin-order-form" action="{{ route('operator.orders.select-courier', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <label for="courier-{{ $order->id }}">Izvēlēties kurjeru</label>
                    <div class="admin-order-form-controls">
                        <select name="delivery_person_id" id="courier-{{ $order->id }}" required>
                            @foreach ($couriers as $courier)
                                <option value="{{ $courier->id }}">{{ $courier->name }} {{ $courier->surname }}</option>
                            @endforeach
                        </select>
                        <button type="submit">Piešķirt kurjeru</button>
                    </div>
                </form>
            </article>
        @empty
            <div class="admin-orders-empty">
                <span class="bi bi-receipt" aria-hidden="true"></span>
                <h2>Pasūtījumu vēl nav</h2>
                <p>Jauni klientu pasūtījumi būs redzami šeit.</p>
            </div>
        @endforelse
    </section>
@endsection
