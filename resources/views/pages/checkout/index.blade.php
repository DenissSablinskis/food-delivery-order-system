@extends('layouts.app')

@section('content')
    <script>
        const user = @json(auth()->user());  // Saņem autentificētā lietotāja datus un pārvērš tos JSON formātā
    </script>

    <div id="checkout"></div>

    @viteReactRefresh
    @vite('resources/js/react/checkout.jsx')

@endsection
