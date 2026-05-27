// --- 1. PULSANTE SCROLL TO TOP ---
function scrollToTop() {
    $('html, body').animate({
        scrollTop: 0
    }, 500);
}

// --- 2. GESTIONE CAROSELLI (Manuale, Automatico, Hover, Frecce) ---
// Funzione per i click manuali sulle frecce (chiamata da HTML inline)
window.scrollCarousel = function(button, amount) {
    $(button).parent().find('.carousel-track')[0].scrollBy({
        left: amount,
        behavior: 'smooth'
    });
}

$(document).ready(function() {
    
    const $scrollBtn = $("#scrollToTopBtn");

    // --- 3. GESTIONE SCROLL GENERALE (ScrollToTop & Sticky Navbar) ---
    const $secondNavbar = $('.sticky-navbar');
    const $standardSection = $('.standard-section');

    $(window).on('scroll', function() {
        // Gestione comparsa pulsante Scroll To Top
        if ($(this).scrollTop() > 300) {
            $scrollBtn.fadeIn(200);
        } else {
            $scrollBtn.fadeOut(200);
        }

        // Gestione comparsa Seconda Navbar
        if ($standardSection.length) {
            const triggerPoint = $standardSection.offset().top - 100;
            if ($(window).scrollTop() > triggerPoint) {
                $secondNavbar.addClass('is-visible');
            } else {
                $secondNavbar.removeClass('is-visible');
            }
        }
    });

    // Inizializzazione logica Caroselli
    $('.carousel-container').each(function() {
        const $container = $(this);
        const $track = $container.find('.carousel-track');
        const $prevBtn = $container.find('.carousel-btn.prev');
        const $nextBtn = $container.find('.carousel-btn.next');
        
        let scrollInterval;
        let direction = 1; // 1 = destra, -1 = sinistra

        if (!$prevBtn.length || !$nextBtn.length) return;

        function updateCarouselState() {
            if ($track.scrollLeft() <= 1) {
                $prevBtn.hide();
                direction = 1;
            } else {
                $prevBtn.css('display', 'flex');
            }

            const isAtEnd = $track.scrollLeft() + $track.innerWidth() >= $track[0].scrollWidth - 1;
            if (isAtEnd) {
                $nextBtn.hide();
                direction = -1;
            } else {
                $nextBtn.css('display', 'flex');
            }
        }

        $track.on('scroll', updateCarouselState);
        setTimeout(updateCarouselState, 100);

        function startScroll() {
            clearInterval(scrollInterval);
            scrollInterval = setInterval(() => {
                $track[0].scrollBy({ left: 200 * direction, behavior: 'smooth' });
            }, 4000);
        }

        function stopScroll() {
            clearInterval(scrollInterval);
        }

        startScroll();

        $container.on('mouseenter', stopScroll);
        $container.on('mouseleave', startScroll);
    });

    // --- 4. GESTIONE MODAL LOGIN ---
    $('.btn-apri-login').on('click', function(e) {
        e.preventDefault();
        $('#modal-login').addClass('active');
    });

    $('#btn-chiudi-login, #modal-login').on('click', function(e) {
        if (e.target === this) {
            $('#modal-login').removeClass('active');
        }
    });

    $('#toggle-password').on('click', function() {
        const $input = $('#password');
        const type = $input.attr('type') === 'password' ? 'text' : 'password';
        $input.attr('type', type);
        $(this).toggleClass('fa-eye-slash fa-eye');
    });

    // --- 5. GESTIONE MODAL REGISTER ---
    $('#link-apri-register').on('click', function(e) {
        e.preventDefault();
        $('#modal-login').removeClass('active');
        $('#modal-register').addClass('active');
    });

    $('#link-torna-login').on('click', function(e) {
        e.preventDefault();
        $('#modal-register').removeClass('active');
        $('#modal-login').addClass('active');
    });

    $('#btn-chiudi-register, #modal-register').on('click', function(e) {
        if (e.target === this) {
            $('#modal-register').removeClass('active');
        }
    });

    $('#toggle-reg-password').on('click', function() {
        const $input = $('#reg-password');
        const type = $input.attr('type') === 'password' ? 'text' : 'password';
        $input.attr('type', type);
        $(this).toggleClass('fa-eye-slash fa-eye');
    });

    // --- 6. GESTIONE CUSTOM DROPDOWNS (Filtri Ricerca) ---
    $('.dropdown-toggle').on('click', function(e) {
        e.stopPropagation();
        const $dropdown = $(this).closest('.custom-dropdown');
        $('.custom-dropdown').not($dropdown).removeClass('active');
        $dropdown.toggleClass('active');
    });

    $('.dd-item').on('click', function() {
        if (this.id !== 'apri-calendario') {
            const $dropdown = $(this).closest('.custom-dropdown');
            const text = $(this).text();
            $dropdown.find('.dd-selected').text(text);
            const $hiddenInput = $dropdown.find('input[type="hidden"]');
            if ($hiddenInput.length) {
                $hiddenInput.val(text === 'Tutta Italia' ? '' : text);
            }
            $dropdown.removeClass('active');
        }
    });

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.custom-dropdown').length) {
            $('.custom-dropdown').removeClass('active');
        }
    });

    // --- GESTIONE INPUT LUOGO MANUALE E AUTOCOMPLETE (AJAX con jQuery) ---
    const $inputCitta = $('#input-citta');
    if ($inputCitta.length) {
        const $autocompleteList = $('<div>', { id: 'autocomplete-list', class: 'autocomplete-items' });
        $inputCitta.parent().append($autocompleteList);

        function fetchAndShowLocations(val) {
            $autocompleteList.empty();
            $.ajax({
                url: '/api/locations',
                method: 'GET',
                data: { q: val },
                dataType: 'json',
                success: function(data) {
                    $autocompleteList.empty();
                    if (data.length > 0) {
                        $autocompleteList.addClass('active');
                        $.each(data, function(index, item) {
                            const $div = $('<div>');
                            
                            if (val) {
                                const matchIndex = item.toLowerCase().indexOf(val.toLowerCase());
                                if (matchIndex >= 0) {
                                    $div.html(item.substring(0, matchIndex) + "<strong>" + item.substring(matchIndex, matchIndex + val.length) + "</strong>" + item.substring(matchIndex + val.length));
                                } else {
                                    $div.html(item);
                                }
                            } else {
                                $div.html(item);
                            }
                            
                            $div.append($('<input>', { type: 'hidden', value: item }));
                            
                            $div.on('click', function() {
                                const selectedVal = $(this).find('input').val();
                                $inputCitta.val(selectedVal);
                                $('#dd-luogo .dd-selected').text(selectedVal);
                                $('#hidden-luogo').val(selectedVal);
                                $autocompleteList.empty().removeClass('active');
                                $('#dd-luogo').removeClass('active');
                            });
                            
                            $autocompleteList.append($div);
                        });
                    } else {
                        $autocompleteList.removeClass('active');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Errore nel fetch delle città:", error);
                }
            });
        }

        $inputCitta.on('input focus', function() {
            fetchAndShowLocations($(this).val());
        });

        $(document).on('click', function(e) {
            if (e.target !== $inputCitta[0] && !$(e.target).closest('#autocomplete-list').length) {
                $autocompleteList.empty().removeClass('active');
            }
        });
    }

    $('#btn-conferma-citta').on('click', function() {
        const val = $('#input-citta').val().trim();
        if (val !== "") {
            $('#dd-luogo .dd-selected').text(val);
            $('#hidden-luogo').val(val);
            $('#dd-luogo').removeClass('active');
            $('#input-citta').val("");
        }
    });

    // --- GESTIONE MODAL CALENDARIO ---
    $('#apri-calendario').on('click', function() {
        $('#dd-quando').removeClass('active');
        $('#modal-calendario').addClass('active');
    });

    $('#btn-chiudi-calendario, #modal-calendario').on('click', function(e) {
        if (e.target === this) {
            $('#modal-calendario').removeClass('active');
        }
    });

    $('#conferma-data').on('click', function() {
        const dateVal = $('#data-scelta').val();
        if (dateVal) {
            const dateObj = new Date(dateVal);
            const formattedDate = dateObj.toLocaleDateString('it-IT');
            $('#dd-quando .dd-selected').text(formattedDate);
            $('#modal-calendario').removeClass('active');
        }
    });

    // --- 7. GESTIONE MODAL LOGOUT ---
    $('#btn-apri-logout').on('click', function(e) {
        e.preventDefault();
        $('#modal-logout').addClass('active');
    });

    function chiudiModalLogout() {
        $('#modal-logout').removeClass('active');
    }

    $('#btn-chiudi-logout, #btn-annulla-logout, #modal-logout').on('click', function(e) {
        if (e.target === this) {
            chiudiModalLogout();
        }
    });
});