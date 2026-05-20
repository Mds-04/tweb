// --- 1. PULSANTE SCROLL TO TOP ---
const scrollBtn = document.getElementById("scrollToTopBtn");

function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// --- 2. GESTIONE CAROSELLI (Manuale, Automatico, Hover, Frecce e Inversione) ---

// Funzione per i click manuali sulle frecce
function scrollCarousel(button, amount) {
    const track = button.parentElement.querySelector('.carousel-track');
    track.scrollBy({
        left: amount,
        behavior: 'smooth'
    });
}

// Selezioniamo tutti i caroselli della pagina
const carousels = document.querySelectorAll('.carousel-container');

carousels.forEach(container => {
    const track = container.querySelector('.carousel-track');
    const prevBtn = container.querySelector('.carousel-btn.prev');
    const nextBtn = container.querySelector('.carousel-btn.next');
    
    let scrollInterval;
    let direction = 1; // 1 significa scorrimento verso destra, -1 verso sinistra

    // Controlliamo che le frecce esistano nel DOM per evitare errori
    if (!prevBtn || !nextBtn) return;

    // Funzione che gestisce la visibilità delle frecce e inverte la marcia
    function updateCarouselState() {
        // Controllo estremità SINISTRA
        if (track.scrollLeft <= 1) {
            prevBtn.style.display = 'none';
            direction = 1; // Inverte la marcia verso destra
        } else {
            prevBtn.style.display = 'flex';
        }

        // Controllo estremità DESTRA
        const isAtEnd = track.scrollLeft + track.clientWidth >= track.scrollWidth - 1;
        if (isAtEnd) {
            nextBtn.style.display = 'none';
            direction = -1; // Inverte la marcia verso sinistra
        } else {
            nextBtn.style.display = 'flex';
        }
    }

    // Aggiorniamo le frecce ogni volta che il carosello scorre (manualmente o automaticamente)
    track.addEventListener('scroll', updateCarouselState);

    // Eseguiamo un primo controllo all'avvio (con un piccolo ritardo per caricare il CSS)
    setTimeout(updateCarouselState, 100);

    // Funzione per avviare lo scorrimento automatico indipendente
    function startScroll() {
        clearInterval(scrollInterval); // Evita timer multipli
        
        scrollInterval = setInterval(() => {
            // Moltiplichiamo i 200px per la 'direction' (+1 o -1) per andare a destra o sinistra
            track.scrollBy({ left: 200 * direction, behavior: 'smooth' });
        }, 4000); // Scorre ogni 2 secondi
    }

    // Funzione per fermare lo scorrimento
    function stopScroll() {
        clearInterval(scrollInterval);
    }

    // Avvia l'autoplay
    startScroll();

    // Mette in pausa al passaggio del mouse
    container.addEventListener('mouseenter', stopScroll);
    container.addEventListener('mouseleave', startScroll);
});


// --- 3. GESTIONE SCROLL GENERALE (ScrollToTop & Sticky Navbar) ---
const secondNavbar = document.querySelector('.sticky-navbar');
const standardSection = document.querySelector('.standard-section');

window.onscroll = function() {
    // Gestione comparsa pulsante Scroll To Top
    if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
        scrollBtn.style.display = "block";
    } else {
        scrollBtn.style.display = "none";
    }

    // Gestione comparsa Seconda Navbar
    if (standardSection) {
        // Appare appena la cima della sezione standard raggiunge la parte superiore della finestra
        // (o poco prima, es. offsetTop - altezza della navbar principale)
        const triggerPoint = standardSection.offsetTop - 100; // Regolabile se necessario
        if (window.scrollY > triggerPoint) {
            secondNavbar.classList.add('is-visible');
        } else {
            secondNavbar.classList.remove('is-visible');
        }
    }
};

// --- 4. GESTIONE MODAL LOGIN ---
// Seleziono TUTTI i pulsanti con la classe 'btn-apri-login' (ne hai due: top nav e sticky nav)
const btnsApriLogin = document.querySelectorAll('.btn-apri-login');
const modalLogin = document.getElementById('modal-login');
const btnChiudiLogin = document.getElementById('btn-chiudi-login');

// Aggiungo l'evento click a ciascun bottone "Accedi"
btnsApriLogin.forEach(btn => {
    btn.addEventListener('click', (e) => {
        e.preventDefault(); // Evita strani salti della pagina
        modalLogin.classList.add('active');
    });
});

// Chiudi il modal cliccando sulla X
if(btnChiudiLogin) {
    btnChiudiLogin.addEventListener('click', () => {
        modalLogin.classList.remove('active');
    });
}

// Chiudi il modal cliccando fuori dal riquadro bianco
if(modalLogin) {
    modalLogin.addEventListener('click', (event) => {
        if (event.target === modalLogin) {
            modalLogin.classList.remove('active');
        }
    });
}

// Funzione per mostrare/nascondere la password
const togglePasswordIcon = document.getElementById('toggle-password');
const inputPasswordBox = document.getElementById('password');

if(togglePasswordIcon && inputPasswordBox) {
    togglePasswordIcon.addEventListener('click', function () {
        const type = inputPasswordBox.getAttribute('type') === 'password' ? 'text' : 'password';
        inputPasswordBox.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
}

// --- 5. GESTIONE MODAL REGISTER ---
const modalRegister = document.getElementById('modal-register');
const btnChiudiRegister = document.getElementById('btn-chiudi-register');
const linkApriRegister = document.getElementById('link-apri-register');
const linkTornaLogin = document.getElementById('link-torna-login');

// Apri Register dalla modale Login
if(linkApriRegister) {
    linkApriRegister.addEventListener('click', (e) => {
        e.preventDefault();
        modalLogin.classList.remove('active'); // Chiudo il login
        modalRegister.classList.add('active'); // Apro il register
    });
}

// Torna al Login dalla modale Register
if(linkTornaLogin) {
    linkTornaLogin.addEventListener('click', (e) => {
        e.preventDefault();
        modalRegister.classList.remove('active'); // Chiudo il register
        modalLogin.classList.add('active'); // Riapro il login
    });
}

// Chiudi Register (cliccando la X)
if(btnChiudiRegister) {
    btnChiudiRegister.addEventListener('click', () => {
        modalRegister.classList.remove('active');
    });
}

// Chiudi Register cliccando fuori dallo sfondo scuro
if(modalRegister) {
    modalRegister.addEventListener('click', (event) => {
        if (event.target === modalRegister) {
            modalRegister.classList.remove('active');
        }
    });
}

// Funzione per mostrare/nascondere la password nella registrazione
const toggleRegPasswordIcon = document.getElementById('toggle-reg-password');
const inputRegPasswordBox = document.getElementById('reg-password');

if(toggleRegPasswordIcon && inputRegPasswordBox) {
    toggleRegPasswordIcon.addEventListener('click', function () {
        const type = inputRegPasswordBox.getAttribute('type') === 'password' ? 'text' : 'password';
        inputRegPasswordBox.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });
}

// --- 6. GESTIONE CUSTOM DROPDOWNS (Filtri Ricerca) ---
const dropdowns = document.querySelectorAll('.custom-dropdown');

dropdowns.forEach(dropdown => {
    const toggleBtn = dropdown.querySelector('.dropdown-toggle');
    const items = dropdown.querySelectorAll('.dd-item');
    const selectedText = dropdown.querySelector('.dd-selected');

    // Apri/Chiudi il dropdown cliccando sul bottone
    if(toggleBtn) {
        toggleBtn.addEventListener('click', (e) => {
            // Chiudi tutti gli altri dropdown prima di aprire questo
            dropdowns.forEach(dd => {
                if(dd !== dropdown) dd.classList.remove('active');
            });
            dropdown.classList.toggle('active');
        });
    }

    // Seleziona un'opzione standard
    items.forEach(item => {
        item.addEventListener('click', () => {
            // Se non è il tasto "Seleziona date..." (che apre il modal)
            if(item.id !== 'apri-calendario') {
                selectedText.innerText = item.innerText;
                dropdown.classList.remove('active'); // Chiudi il menu
            }
        });
    });
});

// Chiudi i dropdown se l'utente clicca fuori
document.addEventListener('click', (e) => {
    if (!e.target.closest('.custom-dropdown')) {
        dropdowns.forEach(dd => dd.classList.remove('active'));
    }
});

// --- GESTIONE INPUT LUOGO MANUALE ---
const inputCitta = document.getElementById('input-citta');
const btnConfermaCitta = document.getElementById('btn-conferma-citta');
const selectedLuogo = document.querySelector('#dd-luogo .dd-selected');
const dropdownLuogo = document.getElementById('dd-luogo');

if(btnConfermaCitta && inputCitta) {
    btnConfermaCitta.addEventListener('click', () => {
        if(inputCitta.value.trim() !== "") {
            selectedLuogo.innerText = inputCitta.value;
            dropdownLuogo.classList.remove('active');
            inputCitta.value = ""; // Svuoto l'input
        }
    });
}

// --- GESTIONE MODAL CALENDARIO ---
const modalCalendario = document.getElementById('modal-calendario');
const btnApriCalendario = document.getElementById('apri-calendario');
const btnChiudiCalendario = document.getElementById('btn-chiudi-calendario');
const btnConfermaData = document.getElementById('conferma-data');
const inputData = document.getElementById('data-scelta');
const selectedQuando = document.querySelector('#dd-quando .dd-selected');
const dropdownQuando = document.getElementById('dd-quando');

if(btnApriCalendario) {
    btnApriCalendario.addEventListener('click', () => {
        dropdownQuando.classList.remove('active'); // Chiudi il dropdown
        modalCalendario.classList.add('active'); // Apri il modal
    });
}

if(btnChiudiCalendario) {
    btnChiudiCalendario.addEventListener('click', () => {
        modalCalendario.classList.remove('active');
    });
}

// Chiudi cliccando fuori
if(modalCalendario) {
    modalCalendario.addEventListener('click', (e) => {
        if (e.target === modalCalendario) modalCalendario.classList.remove('active');
    });
}

// Conferma la data dal calendario
if(btnConfermaData && inputData) {
    btnConfermaData.addEventListener('click', () => {
        if(inputData.value) {
            // Formatta la data (opzionale, es: DD/MM/YYYY)
            const dateObj = new Date(inputData.value);
            const formattedDate = dateObj.toLocaleDateString('it-IT');
            
            selectedQuando.innerText = formattedDate;
            modalCalendario.classList.remove('active');
        }
    });
}

// --- 7. GESTIONE MODAL LOGOUT ---
const modalLogout = document.getElementById('modal-logout');
const btnApriLogout = document.getElementById('btn-apri-logout');
const btnChiudiLogout = document.getElementById('btn-chiudi-logout');
const btnAnnullaLogout = document.getElementById('btn-annulla-logout');

// Apri il modal quando si clicca "Esci" nella sidebar
if(btnApriLogout) {
    btnApriLogout.addEventListener('click', (e) => {
        e.preventDefault(); // Evita salti di pagina
        modalLogout.classList.add('active');
    });
}

// Funzione riutilizzabile per chiudere il modal
function chiudiModalLogout() {
    if(modalLogout) {
        modalLogout.classList.remove('active');
    }
}

// Chiudi cliccando la "X" in alto a destra
if(btnChiudiLogout) {
    btnChiudiLogout.addEventListener('click', chiudiModalLogout);
}

// Chiudi cliccando il pulsante grigio "Annulla"
if(btnAnnullaLogout) {
    btnAnnullaLogout.addEventListener('click', chiudiModalLogout);
}

// Chiudi cliccando fuori dal riquadro bianco
if(modalLogout) {
    modalLogout.addEventListener('click', (event) => {
        if (event.target === modalLogout) {
            chiudiModalLogout();
        }
    });
}

// --- 8. GESTIONE MODAL PAGAMENTI E FATTURAZIONE ---
const modalAddCard = document.getElementById('modal-add-card');
const btnApriAddCard = document.getElementById('btn-apri-add-card');
const btnChiudiAddCard = document.getElementById('btn-chiudi-add-card');

const modalEditBilling = document.getElementById('modal-edit-billing');
const btnApriEditBilling = document.getElementById('btn-apri-edit-billing');
const btnChiudiEditBilling = document.getElementById('btn-chiudi-edit-billing');

// ---> Gestione Apertura/Chiusura Modal Aggiungi Carta
if(btnApriAddCard && modalAddCard) {
    btnApriAddCard.addEventListener('click', (e) => {
        e.preventDefault();
        modalAddCard.classList.add('active');
    });
}
if(btnChiudiAddCard && modalAddCard) {
    btnChiudiAddCard.addEventListener('click', () => modalAddCard.classList.remove('active'));
}
if(modalAddCard) {
    modalAddCard.addEventListener('click', (e) => {
        if(e.target === modalAddCard) modalAddCard.classList.remove('active');
    });
}

// ---> Gestione Apertura/Chiusura Modal Modifica Fatturazione
if(btnApriEditBilling && modalEditBilling) {
    btnApriEditBilling.addEventListener('click', (e) => {
        e.preventDefault();
        modalEditBilling.classList.add('active');
    });
}
if(btnChiudiEditBilling && modalEditBilling) {
    btnChiudiEditBilling.addEventListener('click', () => modalEditBilling.classList.remove('active'));
}
if(modalEditBilling) {
    modalEditBilling.addEventListener('click', (e) => {
        if(e.target === modalEditBilling) modalEditBilling.classList.remove('active');
    });
}

// --- 9. GESTIONE MODAL ELIMINA ACCOUNT ---
const modalDeleteAccount = document.getElementById('modal-delete-account');
const btnApriDeleteAccount = document.getElementById('btn-apri-delete-account');
const btnChiudiDeleteAccount = document.getElementById('btn-chiudi-delete-account');

// ---> Gestione Apertura/Chiusura Modal Aggiungi Carta
if(btnApriDeleteAccount && modalDeleteAccount) {
    btnApriDeleteAccount.addEventListener('click', (e) => {
        e.preventDefault();
        modalDeleteAccount.classList.add('active');
    });
}
if(btnChiudiDeleteAccount && modalDeleteAccount) {
    btnChiudiDeleteAccount.addEventListener('click', () => modalDeleteAccount.classList.remove('active'));
}
if(modalDeleteAccount) {
    modalDeleteAccount.addEventListener('click', (e) => {
        if(e.target === modalDeleteAccount) modalDeleteAccount.classList.remove('active');
    });
}