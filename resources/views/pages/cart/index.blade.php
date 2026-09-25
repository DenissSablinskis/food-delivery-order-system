@extends('layouts.app')

@section('main-class', 'cart-main')

@section('content')
    <script>
        const products = @json($products);
    </script>
    
    <div id="app"></div>

    @viteReactRefresh
    @vite('resources/js/react/cart.jsx')
@endsection
