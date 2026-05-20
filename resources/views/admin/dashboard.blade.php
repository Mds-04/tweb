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
                <span class="role-badge" style="background-color: var(--primary-color);">AMMINISTRATORE</span>
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
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="dashboard-main">
            @if(session('success_admin'))
                <div class="alert alert-success">
                    {{ session('success_admin') }}
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

            <!-- SEZIONE 1: Analisi Vendite Organizzazioni -->
            <section id="analisi" class="dashboard-section active">
                <h2>Analisi Vendite Organizzazioni</h2>
                <div class="table-responsive">
                    <table class="dashboard-table">
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
                    <button class="btn btn-primary" onclick="openOrganizerModal()"><i class="fa-solid fa-plus"></i> Nuovo Organizzatore</button>
                </div>
                
                <div class="table-responsive">
                    <table class="dashboard-table">
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
                            @foreach($organizzatori as $org)
                                <tr>
                                    <td>{{ $org->organizzazione }}</td>
                                    <td>{{ $org->username }}</td>
                                    <td>{{ $org->email }}</td>
                                    <td>{{ $org->telefono }}</td>
                                    <td class="actions-cell">
                                        <button class="btn-icon edit" onclick="openOrganizerModal({{ $org->toJson() }})"><i class="fa-solid fa-pen"></i></button>
                                        <form action="{{ route('admin.organizzatori.destroy', $org->id) }}" method="POST" class="inline-delete" onsubmit="return confirm('Sei sicuro di voler eliminare questo organizzatore? Tutti i suoi eventi andranno persi.');">
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

            <!-- SEZIONE 3: Gestione Clienti -->
            <section id="clienti" class="dashboard-section" style="display: none;">
                <h2>Gestione Clienti</h2>
                <div class="table-responsive">
                    <table class="dashboard-table">
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
                            @foreach($clienti as $cliente)
                                <tr>
                                    <td>{{ $cliente->nome }} {{ $cliente->cognome }}</td>
                                    <td>{{ $cliente->username }}</td>
                                    <td>{{ $cliente->email }}</td>
                                    <td>{{ $cliente->created_at->format('d/m/Y') }}</td>
                                    <td class="actions-cell">
                                        <form action="{{ route('admin.clienti.destroy', $cliente->id) }}" method="POST" class="inline-delete" onsubmit="return confirm('Sei sicuro di voler eliminare questo cliente?');">
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

    <!-- Modal per Creazione/Modifica Organizzatore -->
    <div id="organizerModal" class="modal">
        <div class="modal-content large-modal">
            <span class="close" onclick="closeOrganizerModal()">&times;</span>
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
                    <button type="submit" class="btn btn-primary" id="submitOrganizerBtn">Salva Organizzatore</button>
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
                form.action = `/admin/organizzatori/${org.id}`;
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

            modal.style.display = 'flex';
        }

        function closeOrganizerModal() {
            document.getElementById('organizerModal').style.display = 'none';
        }

        // Chiudi modal se click fuori
        window.onclick = function(event) {
            const modal = document.getElementById('organizerModal');
            if (event.target == modal) {
                closeOrganizerModal();
            }
        }
    </script>
</body>
</html>
