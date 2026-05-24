<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventTicket - Chi Siamo</title>
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
        <span>Chi Siamo</span>
    </section>

    <section class="standard-section" style="max-width: 1000px; margin: 0 auto;">

        <div class="standard-header">
            <h2>Informazioni Generali</h2>
        </div>
        <div style="margin-bottom: 40px; line-height: 1.6; font-size: 16px;">
            <p><strong>EventTicket</strong> è la piattaforma leader in Italia per la gestione e la vendita di biglietti
                per eventi musicali, teatrali, mostre, convegni e manifestazioni letterarie.</p>
            <p style="margin-top: 10px;">La nostra missione è connettere gli appassionati con le loro esperienze
                preferite, fornendo un servizio sicuro, veloce e affidabile, sia per chi partecipa sia per chi
                organizza.</p>
        </div>

        <div class="standard-header">
            <h2>Dati dell'Azienda</h2>
        </div>
        <div
            style="margin-bottom: 40px; line-height: 1.6; display: flex; align-items: center; gap: 30px; flex-wrap: wrap;">
            <img src="{{ asset('img/noBgLogo.png') }}" alt="EventTicket Logo"
                style="width: 150px; height: 150px; object-fit: contain; background-color: var(--secondary-bg-color); border-radius: 20px; padding: 15px;">
            <div style="font-size: 16px;">
                <p><strong>Ragione Sociale:</strong> EventTicket S.p.A.</p>
                <p><strong>Sede Legale:</strong> Via Milano 1, 00100 Roma, Italia</p>
                <p><strong>Partita IVA:</strong> 12345678901</p>
                <p><strong>Email:</strong> <a href="mailto:info@eventticket.it"
                        style="color: var(--primary-color); text-decoration: none;">info@eventticket.it</a></p>
                <p><strong>Telefono:</strong> +39 06 1234567</p>
            </div>
        </div>

        <div class="standard-header">
            <h2>I Nostri Contenuti e Servizi</h2>
        </div>
        <div style="margin-bottom: 40px; line-height: 1.6; font-size: 16px;">
            <p>Offriamo una vasta gamma di contenuti tra cui:</p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 15px;">
                <li><strong>Eventi Musicali:</strong> Concerti, festival e live set.</li>
                <li><strong>Eventi Teatrali:</strong> Spettacoli di prosa, musical e balletti.</li>
                <li><strong>Manifestazioni letterarie:</strong> Incontri letterari.</li>
                <li><strong>Mostre:</strong> Esposizioni d'arte contemporanea e classica.</li>
                <li><strong>Convegni:</strong> Conferenze e seminari.</li>
            </ul>
            <p>I servizi di biglietteria vengono forniti tramite acquisto diretto sul nostro portale.</p>
        </div>

        <div class="standard-header">
            <h2>Come Diventare Organizzatore</h2>
        </div>
        <div style="margin-bottom: 40px; line-height: 1.6; font-size: 16px;">
            <p>Sei un organizzatore di eventi e vuoi vendere i tuoi biglietti su EventTicket? L'adesione è semplice e
                veloce.</p>
            <p style="margin-top: 10px;">Per richiedere l'apertura di un account <strong>Organizzatore</strong> e
                iniziare a pubblicare i tuoi eventi sulla nostra piattaforma, è necessario inviare una richiesta formale
                all'amministrazione.</p>
            <p style="margin-top: 10px;">Invia un'email a <a href="mailto:info@eventticket.it"
                    style="color: var(--primary-color); font-weight: bold; text-decoration: none;">info@eventticket.it</a>
                includendo tutti i seguenti dati obbligatori per la registrazione:</p>
            <ul style="margin-left: 20px; margin-top: 10px; margin-bottom: 15px;">
                <li><strong>Nome dell'Organizzazione</strong></li>
                <li><strong>Nome e Cognome del Referente</strong></li>
                <li><strong>Username desiderato</strong> (per l'accesso al sistema)</li>
                <li><strong>Indirizzo Email aziendale</strong></li>
                <li><strong>Numero di Telefono</strong></li>
            </ul>
            <p>Il nostro team valuterà la tua richiesta e, se idonea, provvederà a creare il tuo account riservato
                inviandoti le credenziali per accedere alla tua dashboard gestionale.</p>
        </div>

    </section>

    @include('partials.footer')
    @include('partials.login')
    @include('partials.register')

    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
