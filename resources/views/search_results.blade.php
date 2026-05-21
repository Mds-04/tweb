<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Risultati Ricerca - EventTicket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
</head>
<body id="search-results">

    @include('partials.header')

    <section class="motto-section">
        <div class="logo-container" style="flex-direction: column; gap: 20px;">
            <span id="event-name">Risultati della Ricerca</span>
            <div class="result-founded">
                {{ count($eventi) }} risultati trovati per "{{ request('q') }}"
            </div>
        </div>
    </section>

    <section class="highlighted-section">
        <div class="carousel-track" style="flex-wrap: wrap; justify-content: center; overflow-x: hidden;">
            @forelse($eventi as $evento)
                <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" 
                     onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'"
                     @if($evento->immagine)
                         style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;"
                     @endif>
                    <div style="position: absolute; bottom: 0; left: 0; width: 100%; background: linear-gradient(transparent, rgba(0,0,0,0.9)); padding: 20px 10px 10px 10px; color: white; text-align: center; border-radius: 0 0 10px 10px; box-sizing: border-box;">
                        <h4 style="margin: 0; font-size: 15px; font-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $evento->titolo }}</h4>
                        <p style="margin: 5px 0 0; font-size: 13px; color: #a3cc4e; font-weight: bold;">{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</p>
                    </div>
                </div>
            @empty
                <p style="text-align: center; width: 100%; color: var(--text-dark);">Nessun evento corrisponde alla tua ricerca.</p>
            @endforelse
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
