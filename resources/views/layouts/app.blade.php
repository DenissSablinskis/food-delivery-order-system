<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            const userId = @json(auth()->id());
        </script>
        <title>SIA "GAISMA"</title>
    </head>
    <body>
        @include('partials.header')

        <!-- Galvenā satura sadaļa -->
        <main class="@yield('main-class')">
            @yield('content')
        </main>

        <!-- Kājene -->
        <footer>SIA "GAISMA" 2026</footer>

    </body>
</html>
