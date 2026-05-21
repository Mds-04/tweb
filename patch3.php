<?php
$files = [
    'resources/views/events/eventiMusicali.blade.php',
    'resources/views/events/eventiTeatrali.blade.php',
    'resources/views/events/manifestazioniLetterarie.blade.php',
    'resources/views/events/mostre.blade.php',
    'resources/views/events/convegni.blade.php'
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    
    preg_match('/<span id="event-name">(.*?)<\/span>/', $content, $matches1);
    $eventName = $matches1[1] ?? 'Eventi';
    
    preg_match('/placeholder="Ricerca (.*?)"/', $content, $matches2);
    $placeholder = $matches2[1] ?? 'Evento';

    $newForm = <<<HTML
    <section class="motto-section">
        <div class="logo-container" style="flex-direction: column; gap: 20px; width: 100%; max-width: 900px; margin: 0 auto;">
            <span id="event-name">$eventName</span>

            <form action="" method="GET" style="display: flex; gap: 15px; flex-wrap: wrap; align-items: center; justify-content: center; width: 100%;">
                
                <div class="search-bar" style="margin: 0; min-width: 250px; flex-grow: 2;">
                    <input type="text" placeholder="Ricerca $placeholder" name="search" value="{{ request('search') }}" style="width: 100%; box-sizing: border-box;">
                    <i class="fa-solid fa-search"></i>
                </div>

                <div class="custom-dropdown" id="dd-luogo" style="min-width: 200px; width: auto; flex-grow: 1;">
                    <button class="dropdown-toggle btn btn-gray" type="button" style="width: 100%; justify-content: space-between;">
                        <div style="display: flex; align-items: center;">
                            <i class="fa fa-map-marker"></i>
                            <div class="dd-text">
                                <strong>Luogo:</strong> <span class="dd-selected">{{ request('luogo', 'Tutta Italia') }}</span><input type="hidden" name="luogo" id="hidden-luogo" value="{{ request('luogo') }}">
                            </div>
                        </div>
                        <i class="fas fa-chevron-down arrow"></i>
                    </button>
                    <div class="dropdown-menu">
                        <div class="dd-item">Tutta Italia</div>
                        <div class="dd-input-wrapper">
                            <input type="text" id="input-citta" placeholder="Inserisci una città...">
                            <button id="btn-conferma-citta" class="btn-submit" type="button" style="padding: 8px; border-radius: 6px; width: auto;"><i class="fa fa-check"></i></button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-gray" style="padding: 14px 30px; font-size: 16px;">Cerca</button>
            </form>
            
            <div class="result-founded">
                {{ count(\$eventi) }} risultati trovati
            </div>
        </div>
    </section>
HTML;

    $content = preg_replace('/<section class="motto-section">.*?<\/section>/s', $newForm, $content);
    file_put_contents($file, $content);
}
?>
