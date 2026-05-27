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

<body id="category-{{ $slug }}">

    @include('partials.header')

    <section class="motto-section">
        <div class="logo-container"
            style="flex-direction: column; gap: 20px; width: 100%; max-width: 600px; margin: 0 auto;">
            <span id="event-name">{{ $categoria }}</span>

            

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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>

