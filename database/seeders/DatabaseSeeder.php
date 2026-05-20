<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Creazione Admin
        User::create([
            'nome' => 'Admin',
            'cognome' => 'Sistema',
            'email' => 'admin@eventticket.it',
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin'),
            'livello' => 4, // 4 = Admin
            'data_nascita' => '1990-01-01',
        ]);

        // 2. Creazione Organizzatore
        $organizer = User::create([
            'nome' => 'Mario',
            'cognome' => 'Rossi',
            'email' => 'organizzatore@eventticket.it',
            'username' => 'organizzatore',
            'password' => \Illuminate\Support\Facades\Hash::make('organizzatore'),
            'livello' => 3, // 3 = Organizzatore
            'organizzazione' => 'LiveNation Italia',
            'telefono' => '3331234567',
            'data_nascita' => '1985-05-15',
        ]);

        // 3. Creazione Cliente
        $cliente = User::create([
            'nome' => 'Luca',
            'cognome' => 'Bianchi',
            'email' => 'cliente@gmail.com',
            'username' => 'cliente',
            'password' => \Illuminate\Support\Facades\Hash::make('cliente'),
            'livello' => 2, // 2 = Cliente
            'telefono' => '3409876543',
            'data_nascita' => '1995-10-20',
        ]);

        // 4. Creazione Evento di prova per l'organizzatore
        \App\Models\Event::create([
            'organizzatore_id' => $organizer->id,
            'titolo' => 'Concerto di Primavera',
            'descrizione' => 'Un grandioso concerto per celebrare la primavera.',
            'programma' => "20:00 - Apertura\n21:00 - Inizio Spettacolo\n23:30 - Fine",
            'data' => '2026-05-30',
            'orario' => '21:00:00',
            'citta' => 'Milano',
            'luogo' => 'San Siro',
            'come_raggiungere' => 'Metropolitana Linea Lilla (M5) fermata San Siro Stadio.',
            'categoria' => 'Eventi musicali',
            'prezzo' => 45.00,
            'biglietti_totali' => 10000,
            'biglietti_disponibili' => 10000,
        ]);
    }
}
