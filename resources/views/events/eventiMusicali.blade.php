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

<body id="eventi-musicali">

    @include('partials.header')

    <section class="motto-section">
        <div class="logo-container"
            style="flex-direction: column; gap: 20px; width: 100%; max-width: 600px; margin: 0 auto;">
            <span id="event-name">Eventi Musicali</span>

            <form action="" method="GET"
                style="display: flex; flex-direction: column; gap: 15px; align-items: center; justify-content: center; width: 100%;">

                <div class="search-bar" style="margin: 0; width: 100%;">
                    <input type="text" placeholder="Ricerca Evento musicale" name="search"
                        value="{{ request('search') }}" style="width: 100%; box-sizing: border-box;">
                    <button type="submit"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; outline: none; z-index: 10;"><i
                            class="fa-solid fa-search"></i></button>
                </div>

                <div class="custom-dropdown" id="dd-luogo" style="width: 100%;">
                    <button class="dropdown-toggle btn btn-gray" type="button"
                        style="width: 100%; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <i class="fa fa-map-marker"></i>
                            <div class="dd-text">
                                <strong>Luogo:</strong> <span
                                    class="dd-selected">{{ request('luogo', 'Tutta Italia') }}</span><input
                                    type="hidden" name="luogo" id="hidden-luogo" value="{{ request('luogo') }}">
                            </div>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dd-item">Tutta Italia</div>
                        <div class="dd-input-wrapper">
                            <input type="text" id="input-citta" placeholder="Inserisci una città...">
                            <button id="btn-conferma-citta" class="btn-submit" type="button"
                                style="padding: 8px; border-radius: 6px; width: auto;"><i
                                    class="fa fa-check"></i></button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-gray" style="padding: 14px 50px; font-size: 16px;">Cerca</button>
            </form>

            <div class="result-founded">
                {{ count($eventi) }} risultati trovati
            </div>
        </div>
    </section>

    <section class="highlighted-section">
        <div class="carousel-track" style="flex-wrap: wrap; justify-content: center; overflow-x: hidden;">
            @forelse($eventi as $evento)
                <div class="card {{ $evento->immagine ? 'has-image' : '' }}"
                    onclick="window.location.href='{{ route('evento.show', ['id' => $evento->id]) }}'">
                    <div class="card-image"
                        @if ($evento->immagine) style="background-image: url('{{ Storage::url($evento->immagine) }}');" @endif>
                    </div>
                    <div class="card-info">
                        <div class="date">{{ \Carbon\Carbon::parse($evento->data)->translatedFormat('l d F Y') }} /
                            {{ \Carbon\Carbon::parse($evento->orario)->format('H:i') }}</div>
                        <div class="title">{{ $evento->titolo }}</div>
                        <div class="location"><i class="fa-solid fa-location-dot"></i>
                            {{ $evento->luogo ?? 'Location' }}</div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; width: 100%; color: var(--text-dark);">Nessun evento disponibile per
                    questa categoria.</p>
            @endforelse
        </div>
    </section>

    @include('partials.footer')
    @include('partials.login')
    @include('partials.register')

    <div id="modal-calendario" class="modal-overlay">
        <div class="modal-content" style="max-width: 350px;">
            <span class="chiudi-modal" id="btn-chiudi-calendario">&times;</span>
            <h2>Seleziona una Data</h2>
            <div class="form-group">
                <input type="date" id="data-scelta"
                    style="width: 100%; padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-family: inherit; margin-bottom: 20px;">
            </div>
            <button class="btn-submit" id="conferma-data">Conferma Data</button>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>

