@extends('layouts.app')

@section('main-class', 'cart-main')

@section('content')
    <script>
        // Saņemt tulkojumus no Laravel un parveidot tos par JavaScript objektu
        const translations = {
            cartTitle: @json(__('messages.cartTitle')),
            cartEmpty: @json(__('messages.cartEmpty')),
            cartQuantity: @json(__('messages.cartQuantity')),
            cartTotal: @json(__('messages.cartTotal')),
        };
    </script>
    
    <div id="app"></div>

    @viteReactRefresh
    @vite('resources/js/react/cart.jsx')
@endsection
