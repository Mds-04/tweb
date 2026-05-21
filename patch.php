<?php
$files = [
    'resources/views/events/eventiMusicali.blade.php' => 'Eventi musicali',
    'resources/views/events/eventiTeatrali.blade.php' => 'Eventi teatrali',
    'resources/views/events/manifestazioniLetterarie.blade.php' => 'Manifestazioni letterarie',
    'resources/views/events/mostre.blade.php' => 'Mostre',
    'resources/views/events/convegni.blade.php' => 'Convegni'
];

foreach ($files as $file => $name) {
    $content = file_get_contents($file);
    
    // Change Ricerca evento to Ricerca $name
    $singularName = rtrim($name, 'ie'); // rough singular
    if ($name == 'Eventi musicali') $singularName = 'Evento musicale';
    if ($name == 'Eventi teatrali') $singularName = 'Evento teatrale';
    if ($name == 'Manifestazioni letterarie') $singularName = 'Manifestazione letteraria';
    if ($name == 'Mostre') $singularName = 'Mostra';
    if ($name == 'Convegni') $singularName = 'Convegno';
    
    $content = preg_replace('/placeholder=\"Ricerca evento\"/', 'placeholder="Ricerca ' . $singularName . '" name="search" value="{{ request(\'search\') }}"', $content);
    
    // Wrap the top section in a form
    $content = preg_replace('/<div class=\"search-bar\">/', '<form action="" method="GET" style="display: contents;"><div class="search-bar">', $content);
    $content = preg_replace('/<button class=\"btn btn-gray\">Cerca<\/button>/', '<button type="submit" class="btn btn-gray">Cerca</button></form>', $content);
    
    // Remove Categoria and Quando dropdowns
    $content = preg_replace('/<div class=\"custom-dropdown\" id=\"dd-categoria\">.*?<\/div>\s*<\/div>/s', '', $content);
    $content = preg_replace('/<div class=\"custom-dropdown\" id=\"dd-quando\">.*?<\/div>\s*<\/div>/s', '', $content);
    
    // Remove Vicino alla mia posizione
    $content = preg_replace('/<div class=\"dd-item\">Vicino alla mia posizione<\/div>/', '', $content);
    
    // Make Luogo input dynamic
    $content = preg_replace('/<span class=\"dd-selected\">Tutta Italia<\/span>/', '<span class="dd-selected">{{ request(\'luogo\', \'Tutta Italia\') }}</span><input type="hidden" name="luogo" id="hidden-luogo" value="{{ request(\'luogo\') }}">', $content);
    
    file_put_contents($file, $content);
}
