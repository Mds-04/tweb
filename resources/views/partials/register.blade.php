<div id="modal-register" class="modal-overlay">
    <style>
        #modal-register .form-group {
            margin-bottom: 10px;
        }

        #modal-register .form-group label {
            margin-bottom: 2px;
            font-size: 13px;
        }

        #modal-register .input-wrapper input,
        #modal-register .input-wrapper select {
            padding-top: 8px !important;
            padding-bottom: 8px !important;
        }

        #modal-register .btn-submit {
            padding: 10px;
            margin-top: 5px;
        }

        #modal-register h2 {
            font-size: 20px;
            margin-bottom: 10px !important;
        }

        #modal-register .testo-registrati {
            margin-top: 10px;
        }

        #modal-register .modal-content {
            padding: 20px 30px;
        }
    </style>
    <div class="modal-content">
        <span class="chiudi-modal" id="btn-chiudi-register">&times;</span>

        <?php
            if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'about.php' || basename($_SERVER['PHP_SELF']) == 'tAndC.php'):
        ?>

        <img src="{{ asset('img/noBgLogo.png') }}" alt="EventTicket Logo"
            style="width: 60px; margin: 0 auto 5px; display: block;">

        <?php else: ?>

        <img src="{{ asset('img/noBgLogo.png') }}" alt="EventTicket Logo"
            style="width: 60px; margin: 0 auto 5px; display: block;">

        <?php endif; ?>

        <h2 style="margin-bottom: 15px;">Crea un account</h2>

        @if ($errors->register->any())
            <div
                style="background-color: #ffeaea; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                <ul style="list-style: none; padding: 0; margin: 0;">
                    @foreach (array_unique($errors->register->all()) as $error)
                        <li><i class="fa-solid fa-triangle-exclamation"></i> {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-nome">Nome</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-user icon"></i>
                        <input type="text" id="reg-nome" name="nome" placeholder="Nome" required
                            style="padding-top: 10px; padding-bottom: 10px;">
                    </div>
                </div>
                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-cognome">Cognome</label>
                    <div class="input-wrapper">
                        <i class="fa-regular fa-user icon"></i>
                        <input type="text" id="reg-cognome" name="cognome" placeholder="Cognome" required
                            style="padding-top: 10px; padding-bottom: 10px;">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px;">
                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-username">Username</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-at icon"></i>
                        <input type="text" id="reg-username" name="username" placeholder="Username" required
                            style="padding-top: 10px; padding-bottom: 10px;">
                    </div>
                </div>

                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-email">Email</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-envelope icon"></i>
                        <input type="email" id="reg-email" name="email" placeholder="Email" required
                            style="padding-top: 10px; padding-bottom: 10px;">
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-data-nascita">Data di Nascita</label>
                    <div class="input-wrapper">
                        <input type="date" id="reg-data-nascita" name="data_nascita"
                            style="padding-left: 35px; width: 100%; border: 1px solid #ccc; border-radius: 6px; padding-top: 10px; padding-bottom: 10px;">
                    </div>
                </div>

                <div class="form-group" style="flex: 1 1 50%; max-width: 50%;">
                    <label for="reg-password">Password</label>
                    <div class="input-wrapper">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" id="reg-password" name="password" placeholder="Scegli password" required
                            style="padding-top: 10px; padding-bottom: 10px;">
                        <i class="fa-solid fa-eye-slash icon-toggle" id="toggle-reg-password"></i>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn-submit">REGISTRATI</button>
            <p class="testo-registrati">Hai già un account? <a href="#" id="link-torna-login"
                    class="register">Accedi</a></p>
        </form>
    </div>
</div>

@if ($errors->register->any())
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Riapre il modal in caso di errore
            document.getElementById('modal-register').classList.add('active');
        });
    </script>
@endif

