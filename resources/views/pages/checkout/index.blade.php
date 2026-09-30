@extends('layouts.app')

@section('content')
    <script>
        const user = @json(auth()->user());  // Saņem autentificētā lietotāja datus un pārvērš tos JSON formātā
        const translations = {
            checkoutTitle: @json(__('messages.checkoutTitle')),
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
    @vite('resources/js/react/checkout.jsx')

@endsection
