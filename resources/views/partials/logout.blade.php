<div id="modal-logout" class="modal-overlay">
    <div class="modal-content" style="max-width: 400px; text-align: center; padding: 40px 30px;">
        <span class="chiudi-modal" id="btn-chiudi-logout">&times;</span>

        <i class="fa-solid fa-right-from-bracket"
            style="font-size: 50px; color: var(--primary-color); margin-bottom: 20px;"></i>
        <h2 style="margin-bottom: 10px;">Sei sicuro di voler uscire?</h2>
        <p style="margin-bottom: 30px; color: #666; font-size: 15px;">Dovrai effettuare nuovamente l'accesso per vedere i
            tuoi biglietti e gestire il tuo account.</p>

        <div style="display: flex; gap: 15px; justify-content: center;">
            <button class="btn btn-gray" id="btn-annulla-logout"
                style="flex: 1; padding: 12px; border-radius: 25px;">Annulla</button>
            <form action="{{ route('logout') }}" method="POST" style="flex: 1; margin: 0;">
                @csrf
                <button type="submit" class="btn-submit"
                    style="width: 100%; border-radius: 25px; padding: 12px; font-size: 16px;">Sì, Esci</button>
            </form>
        </div>
    </div>
</div>

