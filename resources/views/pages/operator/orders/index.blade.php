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
            <span>
                {{ __('messages.order_status.' . $order->status) }}
            </span>

            @if ($order->status === 'new')

                <form action="{{ route('operator.orders.start-processing', $order->id) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit">Paņemt apstrādē</button>
                </form>
                
            @endif
              
            <div>
                <span>Pasūtītājs</span>
                <span>{{ $order->user->name }} {{ $order->user->surname }}</span>
            </div>

            <div>
                <span>Pasūtījuma datums</span>
                <span>{{ $order->created_at }}</span>
            </div>

            <div>
                <span>Piegādes adrese</span>
                <span>{{ $order->delivery_address }}</span>
            </div>

            <div>

                <form action="{{ route('operator.orders.select-courier', $order->id) }}" method='post'>

                    @csrf
                    @method('patch')

                    <label for="courier-{{ $order->id }}">Izvēleties kurjeru</label>

                    <select name="delivery_person_id" id="courier-{{ $order->id }}" required>

                        @foreach ($couriers as $courier)
                            <option value="{{ $courier->id }}">{{ $courier->name }} {{ $courier->surname }}</option>
                        @endforeach

                    </select>

                    <button type="submit">Piešķirt kurjeru</button>

                </form>

            </div>
               

        @empty

            <span class="bi bi-receipt" aria-hidden="true"></span>
            <h2>Pasūtījumu vēl nav</h2>
            <p>Jauni klientu pasūtījumi būs redzami šeit.</p>

        @endforelse

@endsection