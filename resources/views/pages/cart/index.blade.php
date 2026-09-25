@extends('layouts.app')

@section('content')
    <div id="app"></div>

    @viteReactRefresh
    @vite('resources/js/react/cart.jsx')
@endsection