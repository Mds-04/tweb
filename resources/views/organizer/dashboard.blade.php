<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Organizzatore - EventTicket</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('img/noBgLogo.png') }}" type="image/x-icon">
</head>
<body class="dashboard-body">

    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="dashboard-sidebar">
            <div class="sidebar-header">
                <div class="logo-icon">
                    <i class="fa-solid fa-calendar-days"></i>
                </div>
                <h3>{{ $organizzatore->nome }} {{ $organizzatore->cognome }}</h3>
                <span class="role-badge">ORGANIZZATORE</span>
            </div>

            <nav class="sidebar-nav">
                <button class="nav-btn active" onclick="switchTab('analisi')">
                    <i class="fa-solid fa-chart-line"></i> Analisi Vendite
                </button>
                <button class="nav-btn" onclick="switchTab('biglietti')">
                    <i class="fa-solid fa-ticket"></i> Modifica Biglietti
                </button>
                <button class="nav-btn" onclick="switchTab('sconti')">
                    <i class="fa-solid fa-tags"></i> Sconti Last-Minute
                </button>
                <button class="nav-btn" onclick="switchTab('eventi')">
                    <i class="fa-solid fa-list-check"></i> Gestione Eventi
                </button>
            </nav>

            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            @if(session('success_dashboard'))
                <div class="alert alert-success">
                    {{ session('success_dashboard') }}
                </div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SEZIONE 1: Analisi Vendite -->
            <section id="analisi" class="dashboard-section active">
                <h2>Analisi Vendite</h2>
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Evento</th>
                                <th>Data</th>
                                <th>Biglietti Venduti</th>
                                <th>Percentuale</th>
                                <th>Incasso Totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($eventi as $evento)
                                <tr>
                                    <td>{{ $evento->titolo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</td>
                                    <td>{{ $evento->biglietti_venduti }} / {{ $evento->biglietti_totali }}</td>
                                    <td>
                                        <div class="progress-bar-container">
                                            <div class="progress-bar" style="width: {{ $evento->percentuale_venduti }}%;"></div>
                                        </div>
                                        <span>{{ $evento->percentuale_venduti }}%</span>
                                    </td>
                                    <td class="revenue">€ {{ number_format($evento->incasso_totale, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Nessun evento gestito al momento.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SEZIONE 2: Modifica Biglietti -->
            <section id="biglietti" class="dashboard-section" style="display: none;">
                <h2>Modifica Biglietti</h2>
                <div class="cards-grid">
                    @foreach($eventi as $evento)
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h4>{{ $evento->titolo }}</h4>
                                <span class="ticket-counter">{{ $evento->biglietti_disponibili }} / {{ $evento->biglietti_totali }} rimasti</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('organizer.events.update_tickets', $evento->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Biglietti Totali</label>
                                        <input type="number" name="biglietti_totali" value="{{ $evento->biglietti_totali }}" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Biglietti Disponibili</label>
                                        <input type="number" name="biglietti_disponibili" value="{{ $evento->biglietti_disponibili }}" min="0" required>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Aggiorna Biglietti</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- SEZIONE 3: Sconti Last-Minute -->
            <section id="sconti" class="dashboard-section" style="display: none;">
                <h2>Sconti Last-Minute</h2>
                <p class="section-desc">Applica sconti automatici agli acquisti effettuati nei giorni a ridosso dell'evento.</p>
                <div class="cards-grid">
                    @foreach($eventi as $evento)
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h4>{{ $evento->titolo }}</h4>
                                <span class="price-badge">Prezzo base: €{{ number_format($evento->prezzo, 2, ',', '.') }}</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('organizer.events.update_discount', $evento->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Giorni prima dell'evento (n)</label>
                                        <input type="number" name="sconto_giorni" value="{{ $evento->sconto_giorni }}" min="0" placeholder="Es. 3">
                                    </div>
                                    <div class="form-group">
                                        <label>Sconto (%)</label>
                                        <input type="number" step="0.01" name="sconto_percentuale" value="{{ $evento->sconto_percentuale }}" min="0" max="100" placeholder="Es. 20.00">
                                    </div>
                                    <button type="submit" class="btn btn-primary">Salva Sconti</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- SEZIONE 4: Gestione Eventi -->
            <section id="eventi" class="dashboard-section" style="display: none;">
                <div class="section-header">
                    <h2>Gestione Eventi</h2>
                    <button class="btn btn-primary" onclick="openEventModal()"><i class="fa-solid fa-plus"></i> Crea Nuovo Evento</button>
                </div>
                
                <div class="table-responsive">
                    <table class="dashboard-table">
                        <thead>
                            <tr>
                                <th>Titolo</th>
                                <th>Data</th>
                                <th>Città</th>
                                <th>Prezzo</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($eventi as $evento)
                                <tr>
                                    <td>{{ $evento->titolo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</td>
                                    <td>{{ $evento->citta }}</td>
                                    <td>€ {{ number_format($evento->prezzo, 2, ',', '.') }}</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon edit" onclick="openEventModal({{ $evento->toJson() }})"><i class="fa-solid fa-pen"></i></button>
                                        <form action="{{ route('organizer.events.destroy', $evento->id) }}" method="POST" class="inline-delete" onsubmit="return confirm('Sei sicuro di voler eliminare questo evento?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-icon delete"><i class="fa-solid fa-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Modal per Creazione/Modifica Evento -->
    <div id="eventModal" class="modal">
        <div class="modal-content large-modal">
            <span class="close" onclick="closeEventModal()">&times;</span>
            <h2 id="modalTitle">Nuovo Evento</h2>
            <form id="eventForm" action="{{ route('organizer.events.store') }}" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Titolo</label>
                        <input type="text" name="titolo" id="evt_titolo" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Descrizione</label>
                        <textarea name="descrizione" id="evt_descrizione" rows="3" required></textarea>
                    </div>
                    <div class="form-group full-width">
                        <label>Programma</label>
                        <textarea name="programma" id="evt_programma" rows="3" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Data</label>
                        <input type="date" name="data" id="evt_data" required>
                    </div>
                    <div class="form-group">
                        <label>Orario</label>
                        <input type="time" name="orario" id="evt_orario" required>
                    </div>
                    <div class="form-group">
                        <label>Città</label>
                        <input type="text" name="citta" id="evt_citta" required>
                    </div>
                    <div class="form-group">
                        <label>Luogo (Indirizzo/Struttura)</label>
                        <input type="text" name="luogo" id="evt_luogo" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Indicazioni</label>
                        <textarea name="indicazioni" id="evt_indicazioni" rows="2" required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Prezzo (€)</label>
                        <input type="number" step="0.01" name="prezzo" id="evt_prezzo" min="0" required>
                    </div>
                    <div class="form-group" id="group_biglietti_totali">
                        <label>Biglietti Totali (iniziali)</label>
                        <input type="number" name="biglietti_totali" id="evt_biglietti_totali" min="1" required>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitEventBtn">Salva Evento</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function switchTab(tabId) {
            // Nascondi tutte le sezioni
            document.querySelectorAll('.dashboard-section').forEach(sec => sec.style.display = 'none');
            // Rimuovi classe active dai bottoni
            document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
            
            // Mostra sezione richiesta
            document.getElementById(tabId).style.display = 'block';
            // Aggiungi classe active al bottone cliccato
            event.currentTarget.classList.add('active');
        }

        function openEventModal(evento = null) {
            const modal = document.getElementById('eventModal');
            const form = document.getElementById('eventForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');
            const btnTotalTickets = document.getElementById('group_biglietti_totali');
            const totalTicketsInput = document.getElementById('evt_biglietti_totali');

            if (evento) {
                // Modalità Modifica
                title.innerText = 'Modifica Evento';
                form.action = `/organizer/events/${evento.id}`;
                methodInput.value = 'PUT';
                
                // Nascondi i biglietti totali, vanno modificati nell'altra tab
                btnTotalTickets.style.display = 'none';
                totalTicketsInput.removeAttribute('required');

                // Popola campi
                document.getElementById('evt_titolo').value = evento.titolo;
                document.getElementById('evt_descrizione').value = evento.descrizione;
                document.getElementById('evt_programma').value = evento.programma;
                document.getElementById('evt_data').value = evento.data;
                // orario potrebbe arrivare come "21:00:00"
                document.getElementById('evt_orario').value = evento.orario.substring(0, 5); 
                document.getElementById('evt_citta').value = evento.citta;
                document.getElementById('evt_luogo').value = evento.luogo;
                document.getElementById('evt_indicazioni').value = evento.indicazioni;
                document.getElementById('evt_prezzo').value = evento.prezzo;
            } else {
                // Modalità Creazione
                title.innerText = 'Nuovo Evento';
                form.action = `{{ route('organizer.events.store') }}`;
                methodInput.value = 'POST';
                form.reset();
                
                btnTotalTickets.style.display = 'block';
                totalTicketsInput.setAttribute('required', 'required');
            }

            modal.style.display = 'flex';
        }

        function closeEventModal() {
            document.getElementById('eventModal').style.display = 'none';
        }

        // Chiudi modal se click fuori
        window.onclick = function(event) {
            const modal = document.getElementById('eventModal');
            if (event.target == modal) {
                closeEventModal();
            }
        }
    </script>
</body>
</html>
