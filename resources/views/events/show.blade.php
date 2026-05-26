<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - {{ $evento->titolo }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
    <style>
        .event-hero {
            background: linear-gradient(135deg, var(--secondary-bg-color) 0%, var(--primary-color) 100%);
            color: white;
            padding: 60px 10%;
            display: flex;
            gap: 40px;
            align-items: center;
            border-bottom-left-radius: 40px;
            border-bottom-right-radius: 40px;
            margin-bottom: 40px;
        }

        .event-poster {
            width: 300px;
            height: 400px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 80px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            flex-shrink: 0;
            overflow: hidden;
        }

        .event-header-info h1 {
            font-size: 48px;
            margin-bottom: 10px;
        }

        .event-header-info .badge {
            display: inline-block;
            background-color: white;
            color: var(--primary-color);
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .event-meta {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .event-meta i {
            margin-right: 10px;
            opacity: 0.8;
            width: 20px;
            text-align: center;
        }

        .buy-box {
            background-color: white;
            color: var(--text-dark);
            padding: 25px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .price {
            font-size: 32px;
            font-weight: bold;
            color: var(--primary-color);
        }

        .availability {
            font-size: 14px;
            color: #666;
        }

        .buy-buttons {
            display: flex;
            gap: 20px;
            align-items: center;
            flex-wrap: wrap;
        }

        .btn-partecipero {
            background-color: white;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn-partecipero:hover,
        .btn-partecipero.active {
            background-color: grey;
            border-color: #555;
            cursor: initial;
            color: white;
            transform: scale(1.05);
        }

        .event-details-section {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 20px 60px;
        }

        .detail-card {
            background-color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        }

        .detail-card h2 {
            margin-bottom: 20px;
            color: var(--secondary-bg-color);
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
        }

        .detail-card p {
            line-height: 1.6;
            margin-bottom: 15px;
            color: #555;
            font-size: 16px;
        }

        @media (max-width: 850px) {
            .event-hero {
                flex-direction: column;
                text-align: center;
                padding: 40px 20px;
            }

            .event-meta {
                grid-template-columns: 1fr;
            }

            .buy-box {
                flex-direction: column;
                gap: 20px;
            }
        }
    </style>
</head>

<body>

    @include('partials.header')

    <div class="event-hero">
        <div class="event-poster"
            @if ($evento->immagine) style="background-image: url('{{ Storage::url($evento->immagine) }}'); background-size: cover; background-position: center;" @endif>
            @if (!$evento->immagine)
                <i class="fa-solid fa-music"></i>
            @endif
        </div>
        <div class="event-header-info">
            <span class="badge">{{ $evento->categoria }}</span>
            <h1>{{ $evento->titolo }}</h1>

            <div class="event-meta">
                <div><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}
                </div>
                <div><i class="fa-regular fa-clock"></i> {{ \Carbon\Carbon::parse($evento->orario)->format('H:i') }}
                </div>
                <div><i class="fa-solid fa-location-dot"></i> {{ $evento->citta }}</div>
                <div><i class="fa-solid fa-building"></i> {{ $evento->luogo }}</div>
            </div>

            <div class="buy-box">
                @php
                    $prezzo_finale = $evento->prezzo;
                    $sconto_applicato = false;
                    if ($evento->sconto_giorni > 0 && $evento->sconto_percentuale > 0) {
                        $giorni_mancanti = \Carbon\Carbon::today()->diffInDays(
                            \Carbon\Carbon::parse($evento->data),
                            false,
                        );
                        if ($giorni_mancanti >= 0 && $giorni_mancanti <= $evento->sconto_giorni) {
                            $prezzo_finale = $evento->prezzo * (1 - $evento->sconto_percentuale / 100);
                            $sconto_applicato = true;
                        }
                    }
                @endphp
                <div>
                    <div class="price">
                        @if ($sconto_applicato)
                            <span
                                style="text-decoration: line-through; color: #888; font-size: 22px; margin-right: 10px;">€
                                {{ number_format($evento->prezzo, 2, ',', '.') }}</span>
                        @endif
                        € {{ number_format($prezzo_finale, 2, ',', '.') }}
                    </div>
                    <div class="availability">Disponibilità: <strong>{{ $evento->biglietti_disponibili }}</strong>
                        biglietti</div>
                </div>
                <div class="buy-buttons">
                    @auth
                        @if (Auth::user()->livello == 2)
                            <form action="{{ route('evento.partecipa', $evento->id) }}" method="POST">
                                @csrf
                                @if (Auth::user()->partecipazioni()->where('evento_id', $evento->id)->exists())
                                    <button type="submit" class="btn-partecipero active" disabled>
                                        Partecipi <i class="fa-solid fa-check" style="margin-left: 5px;"></i>
                                    </button>
                                @else
                                    <button type="submit" class="btn-partecipero">
                                        Parteciperò
                                    </button>
                                @endif
                            </form>
                        @endif
                    @else
                        <button class="btn-partecipero btn-apri-login" style="padding: 15px 30px; font-size: 16px;"
                            title="Devi effettuare l'accesso per partecipare">
                            Accedi per Partecipare
                        </button>
                    @endauth
                    @auth
                        @if (Auth::user()->livello == 2)
                            <form action="{{ route('cart.add', $evento->id) }}" method="POST"
                                style="display: flex; gap: 10px; align-items: center;">
                                @csrf
                                <div style="display: flex; flex-direction: column;">
                                    <label for="quantity"
                                        style="font-size: 13px; color: #666; margin-bottom: 4px;">Quantità:</label>
                                    <input type="number" name="quantity" id="quantity" value="1" min="1"
                                        max="{{ $evento->biglietti_disponibili }}"
                                        style="width: 70px; padding: 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 16px; text-align: center;">
                                </div>
                                <button type="submit" class="btn loginBtn"
                                    style="padding: 15px 30px; font-size: 16px; border: none; cursor: pointer; height: 50px; display: flex; align-items: center; align-self: flex-end;">
                                    <i class="fa-solid fa-cart-plus" style="margin-right: 8px;"></i> Aggiungi
                                </button>
                            </form>
                        @endif
                    @endauth
                    @guest
                        <button class="btn loginBtn btn-apri-login" style="padding: 15px 40px; font-size: 18px;">Accedi per
                            Acquistare</button>
                    @endguest
                </div>
            </div>
        </div>
    </div>

    <div class="event-details-section">
        <div class="detail-card">
            <h2><i class="fa-solid fa-align-left"></i> Descrizione</h2>
            <p>{{ $evento->descrizione }}</p>
            <p style="margin-top: 15px; font-size: 14px; color: #888;"><strong>Organizzato da:</strong>
                {{ $evento->organizzatore->organizzazione ?? $evento->organizzatore->nome . ' ' . $evento->organizzatore->cognome }}
            </p>
        </div>

        <div class="detail-card">
            <h2><i class="fa-solid fa-list"></i> Programma</h2>
            <p>{!! nl2br(e($evento->programma)) !!}</p>
        </div>

        <div class="detail-card">
            <h2><i class="fa-solid fa-map-location-dot"></i> Come Raggiungerci</h2>
            <p><strong>{{ $evento->luogo }} - {{ $evento->citta }}</strong></p>
            <p>{{ $evento->come_raggiungere }}</p>
        </div>
    </div>

    @include('partials.footer')
    @include('partials.login')
    @include('partials.register')

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
