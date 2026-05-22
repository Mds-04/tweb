<?php

// 1. Update style.css
$cssFile = 'public/css/style.css';
$css = file_get_contents($cssFile);

$oldCssCard = <<<CSS
.card {
    min-width: 200px;
    height: 300px;
    background-color: #e0e0e0;
    border-radius: 10px;
    flex-shrink: 0;
    border-bottom: 25px solid #a3cc4e; /* Simula l'erba/sfondo della tua immagine */
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease !important;
}

.card::before {
    content: "\\f0c2"; /* Icona nuvola di FontAwesome per simulare l'immagine */
    font-family: "FontAwesome";
    position: absolute;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 40px;
}

.card:hover {
    transform: scale(1.05) !important;
}
CSS;

$newCssCard = <<<CSS
.card {
    min-width: 220px;
    background-color: white;
    border-radius: 15px;
    flex-shrink: 0;
    position: relative;
    overflow: hidden;
    cursor: pointer;
    transition: transform 0.3s ease !important;
    display: flex;
    flex-direction: column;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.card:hover {
    transform: scale(1.03) !important;
}

.card-image {
    height: 220px;
    background-color: #e0e0e0;
    background-size: cover;
    background-position: center;
    position: relative;
}

.card-image::before {
    content: "\\f0c2";
    font-family: "FontAwesome";
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 40px;
}

.card.has-image .card-image::before {
    content: none !important;
}

.card-info {
    padding: 15px;
    text-align: left;
}

.card-info .date {
    font-size: 13px;
    color: #666;
    margin-bottom: 8px;
    text-transform: capitalize;
}

.card-info .title {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-bottom: 12px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.card-info .location {
    font-size: 13px;
    color: #666;
}

.card-info .location i {
    color: #f13036;
    margin-right: 5px;
}
CSS;

// Replace main card css
$css = str_replace($oldCssCard, $newCssCard, $css);

// Also fix media query
$oldMediaQuery = <<<CSS
    #eventi-musicali .highlighted-section .carousel-track .card {
        min-width: 150px;
        max-width: 175px;
        height: 225px;
    }
CSS;
$newMediaQuery = <<<CSS
    #eventi-musicali .highlighted-section .carousel-track .card {
        min-width: 160px;
        max-width: 200px;
    }
    #eventi-musicali .highlighted-section .carousel-track .card-image {
        height: 160px;
    }
CSS;
$css = str_replace($oldMediaQuery, $newMediaQuery, $css);

file_put_contents($cssFile, $css);


// 2. Update blade files
$bladeFiles = [
    'resources/views/index.blade.php',
    'resources/views/search_results.blade.php',
    'resources/views/events/eventiMusicali.blade.php',
    'resources/views/events/eventiTeatrali.blade.php',
    'resources/views/events/manifestazioniLetterarie.blade.php',
    'resources/views/events/mostre.blade.php',
    'resources/views/events/convegni.blade.php'
];

foreach ($bladeFiles as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);

    // If it's index.blade.php, remove the <style> block
    if (basename($file) === 'index.blade.php') {
        $content = preg_replace('/<style>.*?<\/style>/s', '', $content);
    }

    // Use regex to replace ALL card divs with the new structure.
    // The old card looks like this:
    // <div class="card events {{ $evento->immagine ? 'has-image' : '' }}" onclick="..." @if(...) style="..." @endif>
    //     <div ...> ... </div>
    // </div>
    // OR <div class="card {{ $evento->immagine...
    
    // Pattern to match the whole <div class="card... > ... </div> block
    // We can rely on the fact that the card block doesn't contain nested cards.
    $pattern = '/<div class="card(?: events)? \{\{ \$evento->immagine \? \'has-image\' : \'\' \}\}".*?<\/div>.*?<\/div>/s';
    
    $newCardHtml = <<<HTML
<div class="card {{ \$evento->immagine ? 'has-image' : '' }}" onclick="window.location.href='{{ route('evento.show', ['id' => \$evento->id]) }}'">
    <div class="card-image" @if(\$evento->immagine) style="background-image: url('{{ Storage::url(\$evento->immagine) }}');" @endif></div>
    <div class="card-info">
        <div class="date">{{ \Carbon\Carbon::parse(\$evento->data)->translatedFormat('l d F Y') }} / {{ \Carbon\Carbon::parse(\$evento->orario)->format('H:i') }}</div>
        <div class="title">{{ \$evento->titolo }}</div>
        <div class="location"><i class="fa-solid fa-location-dot"></i> {{ \$evento->luogo ?? 'Location' }}</div>
    </div>
</div>
HTML;
    
    $content = preg_replace($pattern, $newCardHtml, $content);

    // A slightly different pattern for index.blade.php where the card overlay was just <div class="card-overlay">
    // Wait, the previous pattern matches <div class="card ...> ... </div> </div> because there's exactly one inner div.
    // Let's make sure the regex works for both cases (with absolute style inner div OR card-overlay inner div).
    // Actually, `<div class="card ...> (any content) </div>` is better matched using a more robust pattern, or just replace everything between `<div class="card` and the NEXT `@empty` or `</div>` of the parent.
    
    file_put_contents($file, $content);
}

echo "Done replacing CSS and Blade files.";
?>
