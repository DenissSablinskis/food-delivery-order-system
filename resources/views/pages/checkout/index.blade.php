@extends('layouts.app')

@section('content')
    <script>
        const user = @json(auth()->user());  // Saņem autentificētā lietotāja datus un pārvērš tos JSON formātā

    // Pārvēršam tulkojumus JSON formātā, lai tos varētu nodot checkout.jsx React komponentei.
    // Komponentē tulkojumi ir pieejami ar mainīgā translations palīdzību.

        const translations = {
            checkoutTitle: @json(__('messages.checkoutTitle')),
            checkoutName: @json(__('messages.checkoutName')),
            checkoutSurname: @json(__('messages.checkoutSurname')),
            checkoutUsername: @json(__('messages.checkoutUsername')),
            checkoutEmail: @json(__('messages.checkoutEmail')),
            checkoutAddress: @json(__('messages.checkoutAddress')),
            checkoutProducts: @json(__('messages.checkoutProducts')),
            checkoutTotal: @json(__('messages.checkoutTotal')),
            checkoutSubmit: @json(__('messages.checkoutSubmit')),
            checkoutQty: @json(__('messages.checkoutQty')),
            checkoutOrderSummary: @json(__('messages.checkoutOrderSummary')),
            checkoutOrder: @json(__('messages.checkoutOrder'))
        };

    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- Pievieno CSRF tokenu, lai aizsargātu pret CSRF uzbrukumiem -->
    <div id="checkout"></div>

    @viteReactRefresh

    <!-- Pieslēdzam React komponentes JavaScript failu šai Blade veidnei. -->
    @vite('resources/js/react/checkout.jsx')

@endsection
