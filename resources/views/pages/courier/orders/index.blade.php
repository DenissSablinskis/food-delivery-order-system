@extends('layouts.app')


@section('content')

        @if (session('success'))
            {{ session('success') }}
        @endif

        @forelse ($orders as $order)
    
            <div>
                <p>Pasūtījums</p>
                <h2>Nr. {{ $order->id }}</h2>
            </div>
      
             
            <div>
                <span>Pasūtītājs</span>
                <span>{{ $order->user->name }} {{ $order->user->surname }}</span>
            </div>
            <div>
                <span>Piegādes adrese</span>
                <span>{{ $order->delivery_address }}</span>
            </div>
            <div>
                <span>Statuss</span>
                <span>{{ $order->status }}</span>
            </div>
    
            @if ($order->status === 'in_process')
                <form action="{{ route('courier.orders.start-delivery', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Sākt piegādi</button>
                </form>
            @endif

            @if ($order->status === 'delivering')
                <form action="{{ route('courier.orders.complete', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit">Piegādāts</button>
                </form>
            @endif

        @empty
  
                <span class="bi bi-receipt" aria-hidden="true"></span>
                <h2>Pasūtījumu vēl nav</h2>
                <p>Jauni klientu pasūtījumi būs redzami šeit.</p>

        @endforelse

@endsection
