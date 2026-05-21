<?php
$files = [
    'resources/views/search_results.blade.php',
    'resources/views/events/eventiMusicali.blade.php',
    'resources/views/events/eventiTeatrali.blade.php',
    'resources/views/events/manifestazioniLetterarie.blade.php',
    'resources/views/events/mostre.blade.php',
    'resources/views/events/convegni.blade.php'
];

foreach ($files as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $content = str_replace('color: white;">Nessun evento', 'color: var(--text-dark);">Nessun evento', $content);
        file_put_contents($file, $content);
    }
}
