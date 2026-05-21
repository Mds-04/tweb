<?php

use Illuminate\Support\Facades\Route;

// Rotte Pubbliche gestite dal PublicController
Route::get('/', [\App\Http\Controllers\PublicController::class, 'home'])->name('home');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/eventi/musicali', [\App\Http\Controllers\PublicController::class, 'musicali'])->name('eventi.musicali');
Route::get('/eventi/teatrali', [\App\Http\Controllers\PublicController::class, 'teatrali'])->name('eventi.teatrali');
Route::get('/eventi/letterarie', [\App\Http\Controllers\PublicController::class, 'letterarie'])->name('eventi.letterarie');
Route::get('/eventi/mostre', [\App\Http\Controllers\PublicController::class, 'mostre'])->name('eventi.mostre');
Route::get('/eventi/convegni', [\App\Http\Controllers\PublicController::class, 'convegni'])->name('eventi.convegni');
Route::get('/search', [\App\Http\Controllers\PublicController::class, 'search'])->name('search');

Route::get('/evento/{id}', [\App\Http\Controllers\PublicController::class, 'show'])->name('evento.show');

// Rotte per l'Autenticazione
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register.post');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Rotta per il Profilo (protetta da middleware auth)
Route::middleware('auth')->get('/profilo', function () {
    return view('profile');
})->name('profile');
Route::middleware('auth')->post('/profilo', [\App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
Route::middleware('auth')->post('/evento/{id}/partecipa', [\App\Http\Controllers\PublicController::class, 'toggleParticipation'])->name('evento.partecipa');

// Rotte Carrello
Route::middleware('auth')->group(function () {
    Route::get('/carrello', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
    Route::post('/carrello/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
    Route::put('/carrello/update/{id}', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
    Route::post('/carrello/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
    Route::post('/carrello/checkout', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/carrello/success', [\App\Http\Controllers\CartController::class, 'success'])->name('cart.success');
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