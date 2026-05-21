<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - Il tuo pass esclusivo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
    <style>
        .card.has-image::before { content: none !important; }
        .card-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            background: linear-gradient(transparent, rgba(0,0,0,0.9));
            padding: 20px 10px 10px 10px;
            color: white;
            text-align: center;
            border-radius: 0 0 10px 10px;
            box-sizing: border-box;
        }
        .card-overlay h4 { margin: 0; font-size: 15px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .card-overlay p { margin: 5px 0 0; font-size: 13px; color: #a3cc4e; font-weight: bold; }
    </style>
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
                    @forelse($prossimiEventi as $evento)
                        <div class="card {{ $evento->immagine ? 'has-image' : '' }}" 
                             onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                             @if($evento->immagine)
                                 style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                             @endif>
                            <div class="card-overlay">
                                <h4>{{ $evento->titolo }}</h4>
                                <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    @empty
                        <p style="text-align: center; width: 100%; color: white;">Nessun evento in programma.</p>
                    @endforelse
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
        @guest
            <button class="btn btn-gray btn-apri-login">Accedi</button>
        @endguest
    </nav>

    <section class="standard-section">
        
        <!-- EVENTI MUSICALI -->
        <div class="standard-header">
            <h2 id="eventi-musicali">Eventi musicali</h2>
            <a href="{{ route('eventi.musicali') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                @forelse($eventiPerCategoria['Eventi Musicali'] as $evento)
                    <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                         onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                         @if($evento->immagine)
                             style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                         @endif>
                        <div class="card-overlay">
                            <h4>{{ $evento->titolo }}</h4>
                            <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; width: 100%;">Nessun evento musicale disponibile.</p>
                @endforelse
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- EVENTI TEATRALI -->
        <div class="standard-header">
            <h2 id="eventi-teatrali">Eventi teatrali</h2>
            <a href="{{ route('eventi.teatrali') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                @forelse($eventiPerCategoria['Eventi Teatrali'] as $evento)
                    <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                         onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                         @if($evento->immagine)
                             style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                         @endif>
                        <div class="card-overlay">
                            <h4>{{ $evento->titolo }}</h4>
                            <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; width: 100%;">Nessun evento teatrale disponibile.</p>
                @endforelse
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- MANIFESTAZIONI LETTERARIE -->
        <div class="standard-header">
            <h2 id="manifestazioni-letterarie">Manifestazioni letterarie</h2>
            <a href="{{ route('eventi.letterarie') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                @forelse($eventiPerCategoria['Manifestazioni Letterarie'] as $evento)
                    <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                         onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                         @if($evento->immagine)
                             style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                         @endif>
                        <div class="card-overlay">
                            <h4>{{ $evento->titolo }}</h4>
                            <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; width: 100%;">Nessuna manifestazione letteraria disponibile.</p>
                @endforelse
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- MOSTRE -->
        <div class="standard-header">
            <h2 id="mostre">Mostre</h2>
            <a href="{{ route('eventi.mostre') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                @forelse($eventiPerCategoria['Mostre'] as $evento)
                    <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                         onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                         @if($evento->immagine)
                             style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                         @endif>
                        <div class="card-overlay">
                            <h4>{{ $evento->titolo }}</h4>
                            <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; width: 100%;">Nessuna mostra disponibile.</p>
                @endforelse
            </div>
            <button class="carousel-btn next" onclick="scrollCarousel(this, 200)"><i class="fa-solid fa-chevron-right"></i></button>
        </div>

        <!-- CONVEGNI -->
        <div class="standard-header">
            <h2 id="convegni">Convegni</h2>
            <a href="{{ route('eventi.convegni') }}" class="vedi-tutto">Vedi tutto ></a>
        </div>
        <div class="carousel-container">
            <button class="carousel-btn prev" onclick="scrollCarousel(this, -200)"><i class="fa-solid fa-chevron-left"></i></button>
            <div class="carousel-track">
                @forelse($eventiPerCategoria['Convegni'] as $evento)
                    <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                         onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                         @if($evento->immagine)
                             style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                         @endif>
                        <div class="card-overlay">
                            <h4>{{ $evento->titolo }}</h4>
                            <p>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                        </div>
                    </div>
                @empty
                    <p style="text-align: center; width: 100%;">Nessun convegno disponibile.</p>
                @endforelse
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