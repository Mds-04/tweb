<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - Profilo Personale</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
</head>

<body>

    @include('partials.header')

    <div class="dashboard-wrapper" style="max-width: 1200px; margin: 50px auto; padding: 0 20px;">

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="user-profile-summary">
                <div class="user-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>
                <h3>{{ Auth::user()->nome }} {{ Auth::user()->cognome }}</h3>
                <p>
                    @if (Auth::user()->livello == 3)
                        <i class="fa-solid fa-star"></i> Organizzatore
                    @elseif(Auth::user()->livello == 4)
                        <i class="fa-solid fa-shield-halved"></i> Amministratore
                    @else
                        <i class="fa-solid fa-ticket"></i> Cliente
                    @endif
                </p>
            </div>
            <ul class="sidebar-menu">
                @if (Auth::user()->livello == 2)
                    <li><a href="#" onclick="showSection('acquisti', this)" id="link-acquisti"><i
                                class="fa-solid fa-ticket"></i> Storico Acquisti</a></li>
                    <li><a href="#" onclick="showSection('preferiti', this)" id="link-preferiti"><i
                                class="fa-regular fa-heart"></i> Eventi Preferiti</a></li>
                @endif
                <li><a href="#" onclick="showSection('account', this)" id="link-account" class="active"><i
                            class="fa-solid fa-user-pen"></i> Dati Account</a></li>
            </ul>
        </div>

        <!-- Contenuto Principale -->
        <div class="dashboard-content">

            @if (Auth::user()->livello == 2)
                <!-- SEZIONE 1: Storico Acquisti -->
                <div id="section-acquisti" class="dashboard-section" style="display: none;">
                    <div class="dashboard-header">
                        <h2><i class="fa-solid fa-ticket"></i> Storico Acquisti</h2>
                    </div>

                    @if (Auth::user()->acquisti && Auth::user()->acquisti->count() > 0)
                        <div class="ticket-list">
                            @foreach (Auth::user()->acquisti as $acquisto)
                                <div class="ticket-item">
                                    <div class="ticket-info-left">
                                        <div class="ticket-date">
                                            <span
                                                class="day">{{ date('d', strtotime($acquisto->evento->data)) }}</span>
                                            <span
                                                class="month">{{ date('M', strtotime($acquisto->evento->data)) }}</span>
                                        </div>
                                        <div class="ticket-details">
                                            <h4>{{ $acquisto->evento->titolo }}</h4>
                                            <p><i class="fa-solid fa-location-dot"></i> {{ $acquisto->evento->luogo }},
                                                {{ $acquisto->evento->citta }}</p>
                                            <p><i class="fa-solid fa-clock"></i> {{ $acquisto->evento->orario }}</p>
                                            <p><i class="fa-solid fa-ticket"></i> Biglietti acquistati:
                                                <strong>{{ $acquisto->num_biglietti }}</strong>
                                            </p>
                                            <p><i class="fa-solid fa-credit-card"></i> Pagato con:
                                                <strong>{{ $acquisto->metodo_pagamento }}</strong>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="ticket-actions">
                                        <p
                                            style="font-size: 20px; font-weight: bold; color: var(--primary-color); margin-bottom: 0;">
                                            € {{ number_format($acquisto->totale, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert"
                            style="text-align: center; padding: 40px; background-color: #fafafa; border-radius: 10px; border: 1px dashed #ccc;">
                            <i class="fa-solid fa-ticket"
                                style="font-size: 50px; color: #ddd; margin-bottom: 15px;"></i>
                            <h3 style="color: #666; margin-bottom: 10px;">Nessun acquisto effettuato</h3>
                            <p style="color: #888; margin-bottom: 20px;">Non hai ancora comprato biglietti per i nostri
                                eventi.</p>
                            <a href="{{ url('/') }}" class="btn loginBtn">Scopri gli eventi</a>
                        </div>
                    @endif
                </div>

                <!-- SEZIONE 2: Eventi Preferiti -->
                <div id="section-preferiti" class="dashboard-section" style="display: none;">
                    <div class="dashboard-header">
                        <h2><i class="fa-regular fa-heart"></i> Eventi Preferiti</h2>
                    </div>

                    @if (Auth::user()->partecipazioni && Auth::user()->partecipazioni->count() > 0)
                        <div class="ticket-list">
                            @foreach (Auth::user()->partecipazioni as $evento)
                                <div class="ticket-item" style="border-left-color: #e4405f;">
                                    <div class="ticket-info-left">
                                        <div class="ticket-date" style="background-color: #e4405f;">
                                            <span class="day">{{ date('d', strtotime($evento->data)) }}</span>
                                            <span class="month">{{ date('M', strtotime($evento->data)) }}</span>
                                        </div>
                                        <div class="ticket-details">
                                            <h4>{{ $evento->titolo }}</h4>
                                            <p><i class="fa-solid fa-location-dot"></i> {{ $evento->luogo }},
                                                {{ $evento->citta }}</p>
                                            <p><i class="fa-solid fa-clock"></i> {{ $evento->orario }}</p>
                                        </div>
                                    </div>
                                    <div class="ticket-actions">
                                        <a href="{{ route('evento.show', $evento->id) }}" class="btn btn-ticket"
                                            style="background-color: #e4405f;">Vedi Evento</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="alert"
                            style="text-align: center; padding: 40px; background-color: #fafafa; border-radius: 10px; border: 1px dashed #ccc;">
                            <i class="fa-regular fa-heart"
                                style="font-size: 50px; color: #ddd; margin-bottom: 15px;"></i>
                            <h3 style="color: #666; margin-bottom: 10px;">Nessun evento preferito</h3>
                            <p style="color: #888; margin-bottom: 20px;">Non hai ancora aggiunto eventi alla tua lista
                                dei desideri.</p>
                            <a href="{{ url('/') }}" class="btn loginBtn">Esplora gli eventi</a>
                        </div>
                    @endif
                </div>
            @endif

            <!-- SEZIONE 3: Modifica Account -->
            <div id="section-account" class="dashboard-section">
                <div class="dashboard-header">
                    <h2><i class="fa-solid fa-address-card"></i> Dati Account</h2>
                </div>

                @if (session('success_profile'))
                    <div class="alert alert-success"
                        style="background-color: #d4edda; color: #155724; padding: 15px; border-radius: 10px; margin-bottom: 20px; text-align: center;">
                        {{ session('success_profile') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger"
                        style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
                    @csrf
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="nome"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Nome</label>
                            <input type="text" name="nome" id="nome"
                                value="{{ old('nome', Auth::user()->nome) }}"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required>
                        </div>

                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="cognome"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Cognome</label>
                            <input type="text" name="cognome" id="cognome"
                                value="{{ old('cognome', Auth::user()->cognome) }}"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required>
                        </div>

                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="username"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Username</label>
                            <input type="text" name="username" id="username"
                                value="{{ old('username', Auth::user()->username) }}"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                        </div>

                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="email"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Email</label>
                            <input type="email" name="email" id="email"
                                value="{{ old('email', Auth::user()->email) }}"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;" required>
                        </div>

                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="data_nascita"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Data di
                                Nascita</label>
                            <input type="date" name="data_nascita" id="data_nascita"
                                value="{{ $errors->has('data_nascita') ? Auth::user()->data_nascita : old('data_nascita', Auth::user()->data_nascita) }}"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                        </div>
                    </div>

                    <h3
                        style="margin-top: 30px; margin-bottom: 15px; color: var(--secondary-bg-color); border-top: 1px solid #eee; padding-top: 20px;">
                        Modifica Password (Lascia vuoto se non vuoi cambiare)</h3>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="password"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Nuova Password</label>
                            <input type="password" name="password" id="password"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                        </div>

                        <div class="form-group" style="display: flex; flex-direction: column; gap: 5px;">
                            <label for="password_confirmation"
                                style="color: #666; font-size: 14px; text-transform: uppercase;">Conferma Nuova
                                Password</label>
                            <input type="password" name="password_confirmation" id="password_confirmation"
                                style="padding: 10px; border-radius: 5px; border: 1px solid #ccc;">
                        </div>
                    </div>

                    <div style="margin-top: 30px; text-align: right;">
                        <button type="submit" class="btn loginBtn" style="padding: 12px 30px; font-size: 16px;"><i
                                class="fa-solid fa-save"></i> Salva Modifiche</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    @include('partials.footer')

    <script src="{{ asset('js/script.js') }}"></script>
    <script>
        function showSection(sectionId, element) {
            // Nascondi tutte le sezioni
            const sections = document.querySelectorAll('.dashboard-section');
            sections.forEach(sec => sec.style.display = 'none');

            // Rimuovi la classe active da tutti i link
            const links = document.querySelectorAll('.sidebar-menu a');
            links.forEach(link => link.classList.remove('active'));

            // Mostra la sezione cliccata e imposta il link come attivo
            document.getElementById('section-' + sectionId).style.display = 'block';
            element.classList.add('active');
        }

        // Se è presente un messaggio di successo del profilo, assicuriamoci che la sezione account sia aperta
        @if (session('success_profile') || $errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                const accountLink = document.getElementById('link-account');
                if (accountLink) showSection('account', accountLink);
            });
        @endif
    </script>
</body>

</html>
