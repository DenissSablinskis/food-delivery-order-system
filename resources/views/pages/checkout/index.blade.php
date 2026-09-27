@extends('layouts.app')

@section('content')
    <script>
        const user = @json(auth()->user());  // Saņem autentificētā lietotāja datus un pārvērš tos JSON formātā
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Pievieno CSRF tokenu, lai aizsargātu pret CSRF uzbrukumiem -->
    <div id="checkout"></div>

    @viteReactRefresh
    @vite('resources/js/react/checkout.jsx')

@endsection
