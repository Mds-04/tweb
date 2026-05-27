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
                <button type="button" class="logout-btn"
                    onclick="document.getElementById('modal-logout').classList.add('active')">
                    <i class="fa-solid fa-right-from-bracket"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            @if (session('success_dashboard'))
                <div class="alert alert-success" id="flash-alert">
                    {{ session('success_dashboard') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger" id="error-alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SEZIONE 1: Analisi Vendite -->
            <section id="analisi" class="dashboard-section active">
                <h2>Analisi Vendite</h2>

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-analisi" class="search-input" placeholder="Cerca evento..."
                        onkeydown="if(event.key === 'Enter') filterTable('search-analisi', 'table-analisi')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterTable('search-analisi', 'table-analisi')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table" id="table-analisi">
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
                                            <div class="progress-bar"
                                                style="width: {{ $evento->percentuale_venduti }}%;"></div>
                                        </div>
                                        <span>{{ $evento->percentuale_venduti }}%</span>
                                    </td>
                                    <td class="revenue">€ {{ number_format($evento->incasso_totale, 2, ',', '.') }}
                                    </td>
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

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-biglietti" class="search-input" placeholder="Cerca evento..."
                        onkeydown="if(event.key === 'Enter') filterCards('search-biglietti', 'grid-biglietti')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterCards('search-biglietti', 'grid-biglietti')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="cards-grid" id="grid-biglietti">
                    @foreach ($eventi as $evento)
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h4>{{ $evento->titolo }}</h4>
                                <span class="ticket-counter">{{ $evento->biglietti_disponibili }} /
                                    {{ $evento->biglietti_totali }} rimasti</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('organizer.events.update_tickets', $evento->id) }}"
                                    method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Biglietti Totali</label>
                                        <input type="number" name="biglietti_totali"
                                            value="{{ $evento->biglietti_totali }}" min="1" required>
                                    </div>
                                    <div class="form-group">
                                        <label>Biglietti Disponibili</label>
                                        <input type="number" name="biglietti_disponibili"
                                            value="{{ $evento->biglietti_disponibili }}" min="0" required>
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

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-sconti" class="search-input" placeholder="Cerca evento..."
                        onkeydown="if(event.key === 'Enter') filterCards('search-sconti', 'grid-sconti')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterCards('search-sconti', 'grid-sconti')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="cards-grid" id="grid-sconti">
                    @foreach ($eventi as $evento)
                        <div class="dashboard-card">
                            <div class="card-header">
                                <h4>{{ $evento->titolo }}</h4>
                                <span class="price-badge">Prezzo base:
                                    €{{ number_format($evento->prezzo, 2, ',', '.') }}</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('organizer.events.update_discount', $evento->id) }}"
                                    method="POST" class="inline-form">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label>Giorni prima dell'evento (n)</label>
                                        <input type="number" name="sconto_giorni"
                                            value="{{ $evento->sconto_giorni }}" min="0" placeholder="Es. 3">
                                    </div>
                                    <div class="form-group">
                                        <label>Sconto (%)</label>
                                        <input type="number" step="0.01" name="sconto_percentuale"
                                            value="{{ $evento->sconto_percentuale }}" min="0" max="100"
                                            placeholder="Es. 20.00">
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
                    <button class="btn btn-primary" onclick="openEventModal()"><i class="fa-solid fa-plus"></i> Crea
                        Nuovo Evento</button>
                </div>

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-eventi" class="search-input" placeholder="Cerca evento..."
                        onkeydown="if(event.key === 'Enter') filterTable('search-eventi', 'table-eventi')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterTable('search-eventi', 'table-eventi')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table" id="table-eventi">
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
                            @foreach ($eventi as $evento)
                                <tr>
                                    <td>{{ $evento->titolo }}</td>
                                    <td>{{ \Carbon\Carbon::parse($evento->data)->format('d/m/Y') }}</td>
                                    <td>{{ $evento->citta }}</td>
                                    <td>€ {{ number_format($evento->prezzo, 2, ',', '.') }}</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon edit"
                                            onclick="openEventModal({{ $evento->toJson() }})"><i
                                                class="fa-solid fa-pen"></i></button>
                                        <button type="button" class="btn-icon delete"
                                            onclick="openDeleteModal('{{ route('organizer.events.destroy', $evento->id) }}')"><i
                                                class="fa-solid fa-trash"></i></button>
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
    <div id="eventModal" class="modal-overlay">
        <div class="modal-content large-modal"
            style="padding: 0; border-radius: 12px; overflow: hidden; background: #fff;">
            <div style="max-height: 90vh; overflow-y: auto; padding: 30px; position: relative;">
                <span class="chiudi-modal" onclick="closeEventModal()">&times;</span>
                <h2 id="modalTitle">Nuovo Evento</h2>
                <form id="eventForm" action="{{ route('organizer.events.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Titolo</label>
                            <input type="text" name="titolo" id="evt_titolo" required>
                        </div>
                        <div class="form-group full-width">
                            <label>Categoria</label>
                            <select name="categoria" id="evt_categoria" required
                                style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                                <option value="">Seleziona una categoria</option>
                                <option value="Eventi Musicali">Eventi Musicali</option>
                                <option value="Eventi Teatrali">Eventi Teatrali</option>
                                <option value="Manifestazioni Letterarie">Manifestazioni Letterarie</option>
                                <option value="Mostre">Mostre</option>
                                <option value="Convegni">Convegni</option>
                            </select>
                        </div>
                        <div class="form-group full-width">
                            <label>Immagine di copertina (Locandina)</label>
                            <input type="file" name="immagine" id="evt_immagine" accept="image/*">
                            <p style="margin-top: 5px; font-size: 13px; color: #666;"><i
                                    class="fa-solid fa-circle-info"></i> Consigliato: immagine con orientamento
                                verticale (es. 600x900 px o proporzione 2:3) per evitare tagli o deformazioni durante la
                                visualizzazione pubblica.</p>
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
                            <label>Luogo di svolgimento (Indirizzo/Struttura)</label>
                            <input type="text" name="luogo" id="evt_luogo" required>
                        </div>
                        <div class="form-group full-width">
                            <label>Indicazioni su come raggiungere la località</label>
                            <textarea name="come_raggiungere" id="evt_come_raggiungere" rows="2"></textarea>
                        </div>
                        <div class="form-group">
                            <label>Prezzo del biglietto (€)</label>
                            <input type="number" step="0.01" name="prezzo" id="evt_prezzo" min="0"
                                required>
                        </div>
                        <div class="form-group" id="group_biglietti_totali">
                            <label>Numero di biglietti disponibili</label>
                            <input type="number" name="biglietti_totali" id="evt_biglietti_totali" min="1"
                                required>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitEventBtn"
                            style="margin-top: 15px;">Salva Evento</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Conferma Eliminazione Evento -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 30px;">
            <span class="chiudi-modal" onclick="closeDeleteModal()">&times;</span>
            <i class="fa-solid fa-triangle-exclamation"
                style="font-size: 50px; color: #cc0000; margin-bottom: 20px;"></i>
            <h2 style="margin-bottom: 10px;">Sei sicuro?</h2>
            <p style="margin-bottom: 30px; color: #666; font-size: 15px;">Vuoi davvero eliminare questo evento in modo
                permanente?</p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button class="btn btn-gray" onclick="closeDeleteModal()"
                    style="flex: 1; padding: 12px; border-radius: 25px;">Annulla</button>
                <form id="deleteForm" method="POST" style="flex: 1; margin: 0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-submit"
                        style="width: 100%; border-radius: 25px; padding: 12px; font-size: 16px; background: #cc0000;">Sì,
                        Elimina</button>
                </form>
            </div>
        </div>
    </div>

    @include('partials.logout')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    <script>
        // Scomparsa automatica alert dopo 5 secondi
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    alert.style.transition = "opacity 0.5s ease";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.style.display = 'none', 500);
                });
            }, 5000);
        });

        function switchTab(tabId) {
            document.querySelectorAll('.dashboard-section').forEach(sec => sec.style.display = 'none');
            document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
            document.getElementById(tabId).style.display = 'block';
            event.currentTarget.classList.add('active');
        }

        // Funzione per filtrare tabella
        function filterTable(inputId, tableId) {
            let input = document.getElementById(inputId).value.toLowerCase();
            let rows = document.getElementById(tableId).getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                // assume first column is title
                let title = rows[i].getElementsByTagName('td')[0].innerText.toLowerCase();
                rows[i].style.display = title.includes(input) ? '' : 'none';
            }
        }

        // Funzione per filtrare griglie di card
        function filterCards(inputId, gridId) {
            let input = document.getElementById(inputId).value.toLowerCase();
            let cards = document.getElementById(gridId).getElementsByClassName('dashboard-card');
            for (let i = 0; i < cards.length; i++) {
                let title = cards[i].querySelector('h4').innerText.toLowerCase();
                cards[i].style.display = title.includes(input) ? '' : 'none';
            }
        }

        // Gestione Event Modal
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

                btnTotalTickets.style.display = 'none';
                totalTicketsInput.removeAttribute('required');

                document.getElementById('evt_titolo').value = evento.titolo;
                document.getElementById('evt_descrizione').value = evento.descrizione;
                document.getElementById('evt_programma').value = evento.programma;
                document.getElementById('evt_data').value = evento.data;
                document.getElementById('evt_orario').value = evento.orario.substring(0, 5);
                document.getElementById('evt_citta').value = evento.citta;
                document.getElementById('evt_luogo').value = evento.luogo;
                document.getElementById('evt_come_raggiungere').value = evento.come_raggiungere || '';
                document.getElementById('evt_prezzo').value = evento.prezzo;
                document.getElementById('evt_categoria').value = evento.categoria || '';
            } else {
                // Modalità Creazione
                title.innerText = 'Nuovo Evento';
                form.action = `{{ route('organizer.events.store') }}`;
                methodInput.value = 'POST';
                form.reset();

                btnTotalTickets.style.display = 'block';
                totalTicketsInput.setAttribute('required', 'required');
            }

            modal.classList.add('active');
        }

        function closeEventModal() {
            document.getElementById('eventModal').classList.remove('active');
        }

        // Gestione Delete Modal
        function openDeleteModal(actionUrl) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModal').classList.add('active');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('active');
        }

        // Chiudi modal se click fuori
        window.onclick = function(event) {
            if (event.target.classList.contains('modal-overlay')) {
                event.target.classList.remove('active');
            }
        }
    </script>
</body>

</html>
