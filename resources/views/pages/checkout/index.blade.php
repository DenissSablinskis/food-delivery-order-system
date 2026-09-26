@extends('layouts.app')

@section('content')
   
    <div id="checkout"></div>

    @viteReactRefresh
    @vite('resources/js/react/checkout.jsx')

@endsection
