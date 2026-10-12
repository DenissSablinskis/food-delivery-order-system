
@extends('layouts.app')

@section('content')

    @foreach ($orders as $order)

        <p>
            <strong> Pasūtījums Nr. {{ $order->id }} </strong>
        </p>
        <p>
            <strong> Pasūtīts: </strong> {{ $order->created_at }}
        </p>
        <p>
            <strong> Pasūtījuma status: </strong> {{ $order->status }}
        </p>
        <p>
            <strong> Kopējā summa: </strong> {{ number_format($order->total, 2) }} €
        </p>
        <p>
            <a href="{{ route('orders.show', $order) }}">Skatīt pasūtījumu</a>
        </p>

    @endforeach

@endsection

