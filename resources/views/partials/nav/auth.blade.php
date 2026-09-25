<nav class="nav-links" id="navLinks">
    <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active-link' : '' }}">{{ __('messages.navHome') }}</a>
    <a href="#">{{ __('messages.navProfile') }}</a>
    <a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.index') ? 'active-link' : '' }}">{{ __('messages.navProducts') }}</a>
    <a href="{{ route('cart.index') }}" class='cart-link'>
        {{ __('messages.navCart') }}
    </a>
    <form method='post' action="{{ route('logout') }}">
        @csrf
        <button type="submit">{{ __('messages.navLogout') }}</button>
    </form>
</nav>

<a href="{{ route('cart.index') }}" class='cart-icon-link'>
    <i class='bi bi-cart'></i>
</a>