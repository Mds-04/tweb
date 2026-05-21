<div id="modal-login" class="modal-overlay">
    <div class="modal-content">
        <span class="chiudi-modal" id="btn-chiudi-login">&times;</span>
        
        <?php
            if (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == 'about.php' || basename($_SERVER['PHP_SELF']) == 'tAndC.php'):
        ?>
        
            <img src="{{ asset('img/noBgLogo.png') }}" alt="EventTicket Logo" style="width: 80px; margin: 0 auto 15px; display: block;">
        
        <?php else: ?>

            <img src="{{ asset('img/noBgLogo.png') }}" alt="EventTicket Logo" style="width: 80px; margin: 0 auto 15px; display: block;">

        <?php endif; ?>
        
        <h2>Accedi al tuo account</h2>

        @if($errors->has('login_error'))
            <div style="background-color: #ffeaea; color: #cc0000; padding: 10px; border-radius: 5px; margin-bottom: 15px; text-align: center; font-size: 14px;">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ $errors->first('login_error') }}
            </div>
        @endif
        
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-user icon"></i>
                    <input type="text" id="username" name="username" placeholder="Il tuo username" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <i class="fa-solid fa-lock icon"></i>
                    <input type="password" id="password" name="password" placeholder="La tua password" required>
                    <i class="fa-solid fa-eye-slash icon-toggle" id="toggle-password"></i>
                </div>
            </div>
            

            
            <button type="submit" class="btn-submit">ACCEDI</button>
            <p class="testo-registrati">Non hai un account? <a href="#" id="link-apri-register" class="register">Registrati</a></p>
        </form>
    </div>
</div>

@if($errors->has('login_error'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.getElementById('modal-login').classList.add('active');
    });
</script>
@endif