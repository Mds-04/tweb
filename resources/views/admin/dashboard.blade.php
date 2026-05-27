<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - EventTicket</title>
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
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h3>Admin</h3>
                <span class="role-badge"
                    style="background-color: #ffffff; color: #cc0000; font-weight: bold;">AMMINISTRATORE</span>
            </div>

            <nav class="sidebar-nav">
                <button class="nav-btn active" onclick="switchTab('analisi')">
                    <i class="fa-solid fa-chart-pie"></i> Analisi Vendite
                </button>
                <button class="nav-btn" onclick="switchTab('organizzatori')">
                    <i class="fa-solid fa-users-gear"></i> Gestione Organizzatori
                </button>
                <button class="nav-btn" onclick="switchTab('clienti')">
                    <i class="fa-solid fa-users"></i> Gestione Clienti
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
            @if (session('success_admin'))
                <div class="alert alert-success">
                    {{ session('success_admin') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- SEZIONE 1: Analisi Vendite Organizzazioni -->
            <section id="analisi" class="dashboard-section active">
                <h2>Analisi Vendite Organizzazioni</h2>

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-analisi" class="search-input" placeholder="Cerca organizzazione..."
                        onkeydown="if(event.key === 'Enter') filterTable('search-analisi', 'table-analisi')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterTable('search-analisi', 'table-analisi')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table" id="table-analisi">
                        <thead>
                            <tr>
                                <th>Organizzazione</th>
                                <th>Referente</th>
                                <th>Biglietti Venduti</th>
                                <th>Incasso Totale</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($organizzatori as $org)
                                <tr>
                                    <td><strong>{{ $org->organizzazione }}</strong></td>
                                    <td>{{ $org->nome }} {{ $org->cognome }}</td>
                                    <td>{{ $org->biglietti_venduti }}</td>
                                    <td class="revenue">€ {{ number_format($org->incasso_totale, 2, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">Nessuna organizzazione presente.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SEZIONE 2: Gestione Organizzatori -->
            <section id="organizzatori" class="dashboard-section" style="display: none;">
                <div class="section-header">
                    <h2>Gestione Organizzatori</h2>
                    <button class="btn btn-primary" onclick="openOrganizerModal()"><i class="fa-solid fa-plus"></i> Crea
                        Nuovo Organizzatore</button>
                </div>

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-organizzatori" class="search-input"
                        placeholder="Cerca organizzazione..."
                        onkeydown="if(event.key === 'Enter') filterTable('search-organizzatori', 'table-organizzatori')"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass"
                        onclick="filterTable('search-organizzatori', 'table-organizzatori')"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table" id="table-organizzatori">
                        <thead>
                            <tr>
                                <th>Organizzazione</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Telefono</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($organizzatori as $org)
                                <tr>
                                    <td>{{ $org->organizzazione }}</td>
                                    <td>{{ $org->username }}</td>
                                    <td>{{ $org->email }}</td>
                                    <td>{{ $org->telefono }}</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon edit"
                                            onclick="openOrganizerModal({{ $org->toJson() }})"><i
                                                class="fa-solid fa-pen"></i></button>
                                        <button type="button" class="btn-icon delete"
                                            onclick="openDeleteModal('{{ route('admin.organizzatori.destroy', $org->id) }}', 'Vuoi davvero eliminare questo organizzatore? Tutti i suoi eventi andranno persi.')"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- SEZIONE 3: Gestione Clienti -->
            <section id="clienti" class="dashboard-section" style="display: none;">
                <h2>Gestione Clienti</h2>

                <div class="search-bar-container" style="position: relative; margin-bottom: 20px;">
                    <input type="text" id="search-clienti" class="search-input"
                        placeholder="Cerca username cliente..."
                        onkeydown="if(event.key === 'Enter') filterTable('search-clienti', 'table-clienti', 1)"
                        style="width: 100%; padding: 10px 40px 10px 10px; border-radius: 5px; border: 1px solid #ccc; font-family: inherit;">
                    <i class="fa-solid fa-magnifying-glass" onclick="filterTable('search-clienti', 'table-clienti', 1)"
                        style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #666;"></i>
                </div>

                <div class="table-responsive">
                    <table class="dashboard-table" id="table-clienti">
                        <thead>
                            <tr>
                                <th>Nome e Cognome</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Data Registrazione</th>
                                <th>Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clienti as $cliente)
                                <tr>
                                    <td>{{ $cliente->nome }} {{ $cliente->cognome }}</td>
                                    <td>{{ $cliente->username }}</td>
                                    <td>{{ $cliente->email }}</td>
                                    <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                                    <td class="actions-cell">
                                        <button type="button" class="btn-icon delete"
                                            onclick="openDeleteModal('{{ route('admin.clienti.destroy', $cliente->id) }}', 'Vuoi davvero eliminare questo cliente?')"><i
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

    <!-- Modal per Creazione/Modifica Organizzatore -->
    <div id="organizerModal" class="modal-overlay">
        <div class="modal-content large-modal"
            style="padding: 0; border-radius: 12px; overflow: hidden; background: #fff;">
            <div style="max-height: 90vh; overflow-y: auto; padding: 30px; position: relative;">
                <span class="chiudi-modal" onclick="closeOrganizerModal()">&times;</span>
                <h2 id="modalTitle">Nuovo Organizzatore</h2>
                <form id="organizerForm" action="{{ route('admin.organizzatori.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label>Nome Organizzazione *</label>
                            <input type="text" name="organizzazione" id="org_organizzazione" required>
                        </div>
                        <div class="form-group">
                            <label>Nome Referente *</label>
                            <input type="text" name="nome" id="org_nome" required>
                        </div>
                        <div class="form-group">
                            <label>Cognome Referente *</label>
                            <input type="text" name="cognome" id="org_cognome" required>
                        </div>
                        <div class="form-group">
                            <label>Username *</label>
                            <input type="text" name="username" id="org_username" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" id="org_email" required>
                        </div>
                        <div class="form-group">
                            <label>Password <span id="pwdInfo">(Obbligatoria per i nuovi)</span></label>
                            <input type="password" name="password" id="org_password">
                        </div>
                        <div class="form-group">
                            <label>Telefono</label>
                            <input type="text" name="telefono" id="org_telefono">
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="submitOrganizerBtn"
                            style="margin-top: 15px;">Salva Organizzatore</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Conferma Eliminazione -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 30px;">
            <span class="chiudi-modal" onclick="closeDeleteModal()">&times;</span>
            <i class="fa-solid fa-triangle-exclamation"
                style="font-size: 50px; color: #cc0000; margin-bottom: 20px;"></i>
            <h2 style="margin-bottom: 10px;">Sei sicuro?</h2>
            <p id="deleteModalText" style="margin-bottom: 30px; color: #666; font-size: 15px;">Vuoi davvero eliminare
                questo elemento?</p>
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
        // colIndex=0 per organizzazione, colIndex=1 per username (sezione clienti)
        function filterTable(inputId, tableId, colIndex = 0) {
            let input = document.getElementById(inputId).value.toLowerCase();
            let rows = document.getElementById(tableId).getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                let cell = rows[i].getElementsByTagName('td')[colIndex];
                if (cell) {
                    let text = cell.innerText.toLowerCase();
                    rows[i].style.display = text.includes(input) ? '' : 'none';
                }
            }
        }

        function openOrganizerModal(org = null) {
            const modal = document.getElementById('organizerModal');
            const form = document.getElementById('organizerForm');
            const title = document.getElementById('modalTitle');
            const methodInput = document.getElementById('formMethod');
            const pwdInfo = document.getElementById('pwdInfo');
            const pwdInput = document.getElementById('org_password');

            if (org) {
                // Modalità Modifica
                title.innerText = 'Modifica Organizzatore';
                form.action = `{{ url('/admin/organizzatori') }}/${org.id}`;
                methodInput.value = 'PUT';

                pwdInfo.innerText = '(Lascia vuoto per non cambiarla)';
                pwdInput.removeAttribute('required');

                // Popola campi
                document.getElementById('org_organizzazione').value = org.organizzazione || '';
                document.getElementById('org_nome').value = org.nome;
                document.getElementById('org_cognome').value = org.cognome;
                document.getElementById('org_username').value = org.username;
                document.getElementById('org_email').value = org.email;
                document.getElementById('org_telefono').value = org.telefono || '';
            } else {
                // Modalità Creazione
                title.innerText = 'Nuovo Organizzatore';
                form.action = `{{ route('admin.organizzatori.store') }}`;
                methodInput.value = 'POST';
                form.reset();

                pwdInfo.innerText = '* (Obbligatoria)';
                pwdInput.setAttribute('required', 'required');
            }

            modal.classList.add('active');
        }

        function closeOrganizerModal() {
            document.getElementById('organizerModal').classList.remove('active');
        }

        // Gestione Delete Modal unico per admin
        function openDeleteModal(actionUrl, warningText) {
            document.getElementById('deleteForm').action = actionUrl;
            document.getElementById('deleteModalText').innerText = warningText;
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
