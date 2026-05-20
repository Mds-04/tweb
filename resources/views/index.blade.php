<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - Il tuo pass esclusivo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
</head>
<body>

    @include('partials.header')

    <section class="motto-section">
        <div class="logo-container">
            <img src="{{ asset('img/noBgLogo.png') }}" alt="Logo" class="logo">
            <span>EventTicket</span>
        </div>
        <span class="motto-divider">|</span>
        <span>Il tuo pass esclusivo</span>
    </section>

    <section class="highlighted-section">
        
        <div class="carousel-wrapper">
            <h2>Prossimi Eventi</h2>
            <div class="carousel-container">
                <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
                <div class="carousel-track">
                    <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div> <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div> <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div>
                    <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div> <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div> <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div>
                    <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div> <div class="card" onclick="window.location.href='{{ route('evento.show', ['id' => 2]) }}'"></div>
                </div>
                <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
            </div>
        </div>



    </section>

    <nav class="sticky-navbar">
        <div class="sticky-links">
            <div class="logo-container" style="margin-right: 15px;">
                <img src="img/noBgLogo.png" alt="Logo" class="logo">
            </div>
            <a href="#eventi-musicali">Eventi musicali</a> <span>|</span>
            <a href="#eventi-teatrali">Eventi teatrali</a> <span>|</span>
            <a href="#manifestazioni-letterarie">Manifestazioni letterarie</a> <span>|</span>
            <a href="#mostre">Mostre</a> <span>|</span>
            <a href="#convegni">Convegni</a>
        </div>
        <button class="btn btn-gray btn-apri-login">Accedi</button>
    </nav>

    <section class="standard-section">
        
        <div class="standard-header">
            <h2 id="eventi-musicali">Eventi musicali</h2>
            <a href="{{ route('eventi.musicali') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="standard-header">
            <h2 id="eventi-teatrali">Eventi teatrali</h2>
            <a href="{{ route('eventi.teatrali') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="standard-header">
            <h2 id="manifestazioni-letterarie">Manifestazioni letterarie</h2>
            <a href="{{ route('eventi.letterarie') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="standard-header">
            <h2 id="mostre">Mostre</h2>
            <a href="{{ route('eventi.mostre') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <div class="standard-header">
            <h2 id="convegni">Convegni</h2>
            <a href="{{ route('eventi.convegni') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
                <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div> <div class="card events" onclick="window.location.href='{{ route('evento.show', ['id' => 1]) }}'"></div>
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

    </section>

    @include('partials.footer')
    @include('partials.login')
    @include('partials.register')

    <button id="scrollToTopBtn" onclick="scrollToTop()" title="Torna su">
        <i class="fa-solid fa-arrow-up"></i>
    </button>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>