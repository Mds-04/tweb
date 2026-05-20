<?php

use Illuminate\Support\Facades\Route;

// Rotta per la Homepage
Route::get('/', function () {
    if (\Illuminate\Support\Facades\Auth::check() && \Illuminate\Support\Facades\Auth::user()->livello === 3) {
        return redirect()->route('organizer.dashboard');
    }
    return view('index');
})->name('home');

// Rotta per la pagina Chi Siamo (About)
Route::get('/about', function () {
    return view('about');
})->name('about');

// Rotta per gli Eventi Musicali
Route::get('/eventi/musicali', function () {
    return view('events.eventiMusicali');
})->name('eventi.musicali');

// Rotta per gli Eventi Teatrali
Route::get('/eventi/teatrali', function () {
    return view('events.eventiTeatrali');
})->name('eventi.teatrali');

// Rotta per le Manifestazioni Letterarie
Route::get('/eventi/letterarie', function () {
    return view('events.manifestazioniLetterarie');
})->name('eventi.letterarie');

// Rotta per le Mostre
Route::get('/eventi/mostre', function () {
    return view('events.mostre');
})->name('eventi.mostre');

// Rotta per i Convegni
Route::get('/eventi/convegni', function () {
    return view('events.convegni');
})->name('eventi.convegni');

// Rotta parametrica per il Singolo Evento (Dinamica)
Route::get('/evento/{id}', function ($id) {
    // Array simulato per mostrare dati dinamici in base all'ID
    $eventiFittizi = [
        1 => [
            'titolo' => 'Concerto di Primavera',
            'categoria' => 'Eventi Musicali',
            'data' => '15 Maggio 2026',
            'orario' => '21:00',
            'citta' => 'Milano',
            'luogo' => 'Teatro alla Scala',
            'prezzo' => 45.00,
            'biglietti_disponibili' => 120,
            'descrizione' => "Unisciti a noi per una serata indimenticabile di musica classica. L'orchestra sinfonica eseguirà i brani più celebri della primavera.",
            'programma' => "20:30 - Apertura porte\n21:00 - Inizio concerto\n22:15 - Intervallo\n22:30 - Seconda parte\n23:30 - Chiusura",
            'indicazioni' => "Il Teatro alla Scala si trova nel centro di Milano, facilmente raggiungibile con la metropolitana Linea 1 (fermata Duomo) o Linea 3 (fermata Montenapoleone)."
        ],
        // Dati di fallback per altri ID
        'default' => [
            'titolo' => 'Evento Spettacolare',
            'categoria' => 'Eventi Vari',
            'data' => 'Data da definire',
            'orario' => '20:00',
            'citta' => 'Roma',
            'luogo' => 'PalaLottomatica',
            'prezzo' => 25.50,
            'biglietti_disponibili' => 500,
            'descrizione' => "Non perdere l'evento dell'anno! Una serie di attività entusiasmanti ti aspettano. Acquista ora il tuo biglietto.",
            'programma' => "19:00 - Accoglienza\n20:00 - Inizio show\n23:00 - Saluti finali",
            'indicazioni' => "Raggiungibile in auto con ampio parcheggio esterno, oppure tramite i mezzi pubblici di superficie."
        ]
    ];

    $evento = $eventiFittizi[$id] ?? $eventiFittizi['default'];
    $evento['id'] = $id;

    return view('events.show', compact('evento'));
})->name('evento.show');

// Rotte per l'Autenticazione
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Rotta per il Profilo (protetta da middleware auth)
Route::middleware('auth')->get('/profilo', function () {
    return view('profile');
})->name('profile');
Route::middleware('auth')->post('/profilo', [\App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');

// Rotte Carrello
Route::middleware('auth')->group(function () {
    Route::get('/carrello', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/carrello/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::post('/carrello/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
});

// Rotte Organizer Dashboard
Route::middleware(['auth', \App\Http\Middleware\CheckOrganizer::class])->prefix('organizer')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\OrganizerController::class, 'dashboard'])->name('organizer.dashboard');
    Route::post('/events', [\App\Http\Controllers\OrganizerController::class, 'storeEvent'])->name('organizer.events.store');
    Route::put('/events/{id}', [\App\Http\Controllers\OrganizerController::class, 'updateEvent'])->name('organizer.events.update');
    Route::delete('/events/{id}', [\App\Http\Controllers\OrganizerController::class, 'destroyEvent'])->name('organizer.events.destroy');
    Route::put('/events/{id}/tickets', [\App\Http\Controllers\OrganizerController::class, 'updateTickets'])->name('organizer.events.update_tickets');
    Route::put('/events/{id}/discount', [\App\Http\Controllers\OrganizerController::class, 'updateDiscount'])->name('organizer.events.update_discount');
});

// Rotte Admin Dashboard
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::post('/organizzatori', [\App\Http\Controllers\AdminController::class, 'storeOrganizer'])->name('admin.organizzatori.store');
    Route::put('/organizzatori/{id}', [\App\Http\Controllers\AdminController::class, 'updateOrganizer'])->name('admin.organizzatori.update');
    Route::delete('/organizzatori/{id}', [\App\Http\Controllers\AdminController::class, 'destroyOrganizer'])->name('admin.organizzatori.destroy');
    Route::delete('/clienti/{id}', [\App\Http\Controllers\AdminController::class, 'destroyClient'])->name('admin.clienti.destroy');
});