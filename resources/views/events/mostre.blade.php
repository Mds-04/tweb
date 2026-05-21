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
<body id="mostre">

    @include('partials.header')

    <section class="motto-section">
        <div class="logo-container" style="flex-direction: column; gap: 20px;">
            <span id="event-name">Mostre</span>

            <div class="search-bar">
                <input type="text" placeholder="Ricerca evento">
                <i class="fa-solid fa-search"></i>
            </div>

            <div class="combo-box-container">
                
                <div class="custom-dropdown" id="dd-categoria">
                    <button class="dropdown-toggle btn btn-gray" type="button">
                        <i class="fa fa-ticket"></i>
                        <div class="dd-text">
                            <strong>Categoria:</strong> <span class="dd-selected">Mostre</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dd-item">Eventi musicali</div>
                        <div class="dd-item">Eventi teatrali</div>
                        <div class="dd-item">Manifestazioni letterarie</div>
                        <div class="dd-item">Mostre</div>
                        <div class="dd-item">Convegni</div>
                    </div>
                </div>

                <div class="custom-dropdown" id="dd-luogo">
                    <button class="dropdown-toggle btn btn-gray" type="button">
                        <i class="fa fa-map-marker"></i>
                        <div class="dd-text">
                            <strong>Luogo:</strong> <span class="dd-selected">Tutta Italia</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dd-item">Tutta Italia</div>
                        <div class="dd-item">Vicino alla mia posizione</div>
                        <div class="dd-input-wrapper">
                            <input type="text" id="input-citta" placeholder="Inserisci una città...">
                            <button id="btn-conferma-citta" class="btn-submit" style="padding: 8px; border-radius: 6px;"><i class="fa fa-check"></i></button>
                        </div>
                    </div>
                </div>

                <div class="custom-dropdown" id="dd-quando">
                    <button class="dropdown-toggle btn btn-gray" type="button">
                        <i class="fa fa-calendar"></i>
                        <div class="dd-text">
                            <strong>Quando:</strong> <span class="dd-selected">Sempre</span>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dd-item">Sempre</div>
                        <div class="dd-item">Prossima settimana</div>
                        <div class="dd-item" id="apri-calendario">Seleziona date... <i class="fa-regular fa-calendar-days" style="float:right;"></i></div>
                    </div>
                </div>

            </div>

            <button class="btn btn-gray">Cerca</button>
            
            <div class="result-founded">
                {{ count($eventi) }} risultati trovati
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
                <p style="text-align: center; width: 100%; color: white;">Nessun evento disponibile per questa categoria.</p>
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
                <input type="date" id="data-scelta" style="width: 100%; padding: 15px; border: 1px solid #ccc; border-radius: 8px; font-family: inherit; margin-bottom: 20px;">
            </div>
            <button class="btn-submit" id="conferma-data">Conferma Data</button>
        </div>
    </div>

    <script src="{{ asset('js/script.js') }}"></script>
</body>
</html>
