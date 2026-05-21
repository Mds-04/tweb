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
    // Replace the plain <i> with a submit <button> wrapping the <i>
    $content = str_replace(
        '<i class="fa-solid fa-search"></i>', 
        '<button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; outline: none; z-index: 10;"><i class="fa-solid fa-search"></i></button>', 
        $content
    );
    file_put_contents($file, $content);
}

// Fix index.blade.php search bar
$indexFile = 'resources/views/index.blade.php';
$indexContent = file_get_contents($indexFile);
$searchFormHtml = <<<HTML
            <form action="{{ route('search') }}" method="GET" style="display: contents;">
                <div class="search-bar">
                    <input type="text" name="q" placeholder="Cerca eventi, artisti o location">
                    <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 0; cursor: pointer; outline: none; z-index: 10;"><i class="fa-solid fa-search"></i></button>
                </div>
            </form>
HTML;

$indexContent = preg_replace('/<div class="search-bar">.*?<\/div>/s', $searchFormHtml, $indexContent);
file_put_contents($indexFile, $indexContent);
?>
