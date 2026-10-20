<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" data-role="{{ auth()->user()->role ?? 'guest' }}">
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
        <footer>
            <div class="footer-inner">
                <span class="footer-brand">
                    <span class="bi bi-basket2-fill" aria-hidden="true"></span>
                    SIA “GAISMA”
                </span>
                <span class="footer-year">© 2026</span>
            </div>
        </footer>

    </body>
</html>
