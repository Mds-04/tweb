<header class="top-navbar">
    <div class="logo-container">
        <img src="{{ asset('img/noBgLogo.png') }}" alt="Logo" class="logo">
        <a href="{{ url('/') }}" class="redirectLink"> EventTicket</a>
    </div>
    
    <div class="search-bar">
        <input type="text" placeholder="Ricerca evento con parole chiave">
        <i class="fa-solid fa-search"></i>
    </div>

    <div class="top-nav-buttons">
        @guest
            <button class="btn loginBtn btn-apri-login">Accedi</button>
        @endguest
        @auth
            @if(Auth::user()->livello == 2)
                @php
                    $cart = session()->get('cart', []);
                    $cartCount = array_sum(array_column($cart, 'quantity'));
                @endphp
                <a href="{{ route('cart.index') }}" class="btn" style="background-color: transparent; color: var(--primary-color); padding: 10px 15px; font-size: 20px; position: relative;" title="Carrello">
                    <i class="fa-solid fa-cart-shopping"></i>
                    @if($cartCount > 0)
                        <span class="badge" style="position: absolute; top: 0; right: 0; background-color: #ff4d4d; color: white; border-radius: 50%; padding: 2px 6px; font-size: 12px;">{{ $cartCount }}</span>
                    @endif
                </a>
            @endif
            <a href="{{ route('profile') }}" class="btn" style="background-color: transparent; color: var(--primary-color); padding: 10px 15px; font-size: 20px;" title="Profilo">
                <i class="fa-solid fa-user"></i>
            </a>
            <button class="btn" id="btn-apri-logout" style="background-color: transparent; color: #ff4d4d; padding: 10px 15px; font-size: 20px; border: none; cursor: pointer;" title="Esci">
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