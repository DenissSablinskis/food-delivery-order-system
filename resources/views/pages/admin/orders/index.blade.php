@extends('layouts.app')

@section('content')

    @if (session('success'))
        <div class="admin-success-message" role="status">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="admin-error-message" role="status">
            {{ session('error') }}
        </div>
    @endif

    @foreach ($orders as $order)

        <div>Pasūtījums Nr. {{ $order->id }}</div>
        <div>Pasūtīja: {{ $order->user->name }} {{ $order->user->surname }} </div>
        <div>Pasūtījuma datums {{ $order->created_at }}</div>
        <div>Statuss {{ __('messages.order_status.' . $order->status) }}</div>
        
        @foreach ($order->orderedProducts as $orderedProduct) 

            <div>{{ $orderedProduct->product->name }} {{ $orderedProduct->product_count }} x {{ $orderedProduct->unit_price_at_purchase }} €</div>

        @endforeach

        <div>Kopējā summa: {{ number_format($order->total, 2, ',', ' ') }} €</div>

        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
            @csrf
            @method('PATCH')
            <select name="status">
                <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>{{ __('messages.order_status.new') }}</option>
                <option value="in_process" {{ $order->status === 'in_process' ? 'selected' : '' }}>{{ __('messages.order_status.in_process') }}</option>
                <option value="delivering" {{ $order->status === 'delivering' ? 'selected' : '' }}>{{ __('messages.order_status.delivering') }}</option>
                <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>{{ __('messages.order_status.delivered') }}</option>
                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>{{ __('messages.order_status.cancelled') }}</option>
            </select>
            <button type="submit">Saglabāt statusu</button>
        </form>

    @endforeach

@endsection