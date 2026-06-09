<footer>
    <div class="footer-row">
        <div class="logo-container">
            <img src="{{ asset('img/noBgLogo.png') }}" alt="Logo" class="logo">
            <span>EventTicket</span>
        </div>
    </div>

    <div class="footer-row">
        <div class="footer-links">
            <a href="{{ route('about') }}">About</a>
        </div>
        <div>
            <a href="{{ asset('pdf/relazione.pdf') }}" target="_blank" class="btn loginBtn" style="text-decoration: none; display: inline-flex; align-items: center; gap: 10px; font-size: 16px; padding: 12px 24px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                <i class="fa-solid fa-file-pdf" style="font-size: 18px;"></i> Relazione Progetto
            </a>
        </div>
    </div>

    <div class="copyright">
        &copy; EventTicket | Tutti i diritti riservati | 2026
    </div>
</footer>

