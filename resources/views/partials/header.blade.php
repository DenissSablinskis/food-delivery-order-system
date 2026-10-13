    <header>
            <!-- Burgera izvēlne -->
            <button class="burger-menu" id="burgerBtn" aria-label="Atvērt navigācijas izvēlni">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <!-- Uzņēmuma logotips -->
            <div class="logo">GAISMA</div>

            <!-- Navigācijas saites -->
                @auth
                    @include('partials.nav.auth')
                @else
                    @include('partials.nav.guest')
                @endauth

                <!-- Valodas un tēmas parslēgšana paradas tikai parastājiem lietotājiem -->

                @if (auth()->guest() || auth()->user()->role === 'customer')
                    <div class="langAndTheme">

                        <!-- Valodas izvēlne -->
                        <div class="language-switcher">
                            <form method='post' action="{{ route('locale.switch', ['locale' => 'lv']) }}">
                                @csrf
                                <button type="submit" class="toggleLanguage {{ app()->getLocale() === 'lv' ? 'active' : '' }}">
                                    LV
                                </button>
                            </form>
                            <span class="lang-divider">|</span>
                            <form method='post' action="{{ route('locale.switch', ['locale' => 'en']) }}">
                                @csrf
                                <button type="submit" class="toggleLanguage {{ app()->getLocale() === 'en' ? 'active' : '' }}">
                                    EN
                                </button>
                            </form>
                        </div>
                        
                        <!-- Tēmas pārslēgšanas poga -->
                        <button id="themeToggle"></button>
                
                    </div>
                @endif
            
        </header>