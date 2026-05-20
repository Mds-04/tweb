<footer>
    <div class="newsletter">
        <div class="nl-title">
            <h2>Non perderti nessun evento!</h2>
        </div>
        <div>
            <span>Iscriviti alla newsletter per ricevere aggiornamenti sui <br> prossimi eventi e offerte esclusive.</span>
        </div>
        <form action="#" method="POST" class="newsletter-form">
            <input type="email" placeholder="Il tuo indirizzo email..." required>
            <button type="submit" class="btn-newsletter">Iscriviti</button>
        </form>
    </div>

    <div class="footer-row">
        <div class="logo-container">
            <img src="{{ asset('img/noBgLogo.png') }}" alt="Logo" class="logo">
            <span>EventTicket</span>
        </div>
        <div class="social-icons">
            <a href="#"><i class="fa-brands fa-facebook"></i></a>
            <a href="#"><i class="fa-brands fa-instagram"></i></a>
            <a href="#"><i class="fa-brands fa-tiktok"></i></a>
        </div>
    </div>

    <div class="footer-row">
        <div class="footer-links">
            <a href="{{ route('about') }}">About</a>
        </div>
        <div class="payment-methods">
            <i class="fa-brands fa-cc-mastercard"></i>
            <i class="fa-brands fa-cc-visa"></i>
            <i class="fa-brands fa-cc-paypal"></i>
            <i class="fa-solid fa-credit-card"></i> 
        </div>
    </div>

    <div class="copyright">
        &copy; EventTicket | Tutti i diritti riservati | 2026
    </div>
</footer>