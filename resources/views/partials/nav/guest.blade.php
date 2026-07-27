<nav class="nav-links" id="navLinks">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active-link' : '' }}">{{ __('messages.navHome') }}</a>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active-link' : '' }}">{{ __('messages.navProducts') }}</a>
    @unless (request()->routeIs('login', 'register'))
        <a href="{{ route('login') }} ">{{ __('messages.navLogin') }}</a>
        <a href="{{ route('register') }}">{{ __('messages.navRegister') }}</a>
    @endunless
</nav>