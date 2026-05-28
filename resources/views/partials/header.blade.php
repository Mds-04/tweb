<script>window.appBaseUrl = "{{ url('/') }}";</script>
<header class="top-navbar">
    <div class="logo-container">
        <img src="{{ asset('img/noBgLogo.png') }}" alt="Logo" class="logo">
        <a href="{{ url('/') }}" class="redirectLink"> EventTicket</a>
    </div>

    <form action="{{ route('search') }}" method="GET" style="display: contents;">
        <div class="search-bar {{ (Auth::check() && Auth::user()->livello == 2) ? 'double-search' : 'single-search' }}">
            @if (Auth::check() && Auth::user()->livello == 2)
                <input type="text" name="q" value="{{ request('q') }}"
                    placeholder="Cosa cerchi?">
                <input type="text" name="luogo" id="input-citta" value="{{ request('luogo') }}"
                    placeholder="Dove? (es. Roma)" autocomplete="off">
            @else
                <input type="text" name="luogo" id="input-citta" value="{{ request('luogo') }}"
                    placeholder="Ricerca eventi per città (es. Roma)..." autocomplete="off" 
                    style="width: 100%; border-radius: 25px !important; border-right: 1px solid #ccc !important; padding: 10px 40px 10px 20px;">
            @endif
            <button type="submit"
                style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; outline: none;">
                <i class="fa-solid fa-search" style="color: #888; font-size: 16px;"></i>
            </button>
        </div>
    </form>

    <div class="top-nav-buttons">
        @guest
            <button class="btn loginBtn btn-apri-login">Accedi</button>
        @endguest
        @auth
            @if (Auth::user()->livello == 2)
                @php
                    $cart = session()->get('cart', []);
                    $cartCount = array_sum(array_column($cart, 'quantity'));
                @endphp
                <a href="{{ route('cart.index') }}" class="btn"
                    style="background-color: transparent; color: var(--primary-color); padding: 10px 15px; font-size: 20px; position: relative;"
                    title="Carrello">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if ($cartCount > 0)
                        <span class="badge"
                            style="position: absolute; top: 0; right: 0; background-color: #ff4d4d; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">{{ $cartCount }}</span>
                    @endif
                </a>
            @endif
            <a href="{{ route('profile') }}" class="btn"
                style="background-color: transparent; color: var(--primary-color); padding: 10px 15px; font-size: 20px;"
                title="Profilo">
                <i class="fa-solid fa-user"></i>
            </a>
            <button class="btn" id="btn-apri-logout"
                style="background-color: transparent; color: #ff4d4d; padding: 10px 15px; font-size: 20px; border: none; cursor: pointer;"
                title="Esci">
                <i class="fa-solid fa-right-from-bracket"></i>
            </button>
        @endauth
    </div>
</header>

@auth
    @include('partials.logout')
@endauth

<button id="scrollToTopBtn" onclick="scrollToTop()" title="Torna su">
    <i class="fa-solid fa-arrow-up"></i>
</button>

