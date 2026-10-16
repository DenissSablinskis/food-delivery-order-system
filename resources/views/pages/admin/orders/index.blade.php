@extends('layouts.app')

@section('main-class', 'admin-orders-main')

@section('content')
    <section class="admin-orders-page">
        <div class="admin-orders-heading">
            <p class="admin-orders-eyebrow">Administrēšana</p>
            <h1>Pasūtījumu pārvaldība</h1>
            <p class="admin-orders-description">Pārskatiet klientu pasūtījumus un atjauniniet to statusu.</p>
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

        @error('delivery_address')
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
                </div>

                <div class="admin-order-products">
                    <h3>Pasūtītās preces</h3>
                    <ul>
                        @foreach ($order->orderedProducts as $orderedProduct)
                            <li>
                                <span class="admin-order-product-name">{{ $orderedProduct->product->name }}</span>
                                <span class="admin-order-product-quantity">
                                    {{ $orderedProduct->product_count }} × {{ number_format($orderedProduct->unit_price_at_purchase, 2, ',', ' ') }} €
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="admin-order-total">
                    <span>Kopējā summa</span>
                    <strong>{{ number_format($order->total, 2, ',', ' ') }} €</strong>
                </div>

                <!-- Pasūtījuma piegādes adreses maiņa -->

                <div class="admin-order-address">
                    <form action="{{ route('admin.orders.update-address', $order->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <label for="delivery-address-{{ $order->id }}">Piegādes adrese</label>

                        <input type="text" id="delivery-address-{{ $order->id }}" name="delivery_address" value="{{ $order->delivery_address }}" required
                            @disabled($order->status === 'delivered' || $order->status === 'cancelled')>

                        <button type="submit" @disabled($order->status === 'delivered' || $order->status === 'cancelled')>Saglabāt adresi</button>
                    </form>
                 </div>

                 <!-- Pasūtījuma statusa maiņas saraksts -->

                <form class="admin-order-form" action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <!-- for="order-status-{{ $order->id }}" un id="order-status-{{ $order->id }}" nodrošina to ka nospiežot uz uzraksta "Mainīt pasūtījuma statusu" 
                     tiek izcelta attiecīgs select saraksts. Tas uzlabo pieejamību un ērtību.-->

                    <label for="order-status-{{ $order->id }}">Mainīt pasūtījuma statusu</label>
                    <div class="admin-order-form-controls">
                        <select id="order-status-{{ $order->id }}" name="status" @disabled($order->status === 'delivered')>
                            <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>{{ __('messages.order_status.new') }}</option>
                            <option value="in_process" {{ $order->status === 'in_process' ? 'selected' : '' }}>{{ __('messages.order_status.in_process') }}</option>
                            <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>{{ __('messages.order_status.delivering') }}</option>
                            <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('messages.order_status.delivered') }}</option>
                            <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('messages.order_status.cancelled') }}</option>
                        </select>
                        <button type="submit" @disabled($order->status === 'delivered')>Saglabāt statusu</button>
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