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

        // Inizializza Faker
        $faker = \Faker\Factory::create('it_IT');

        // 5. Creazione 10 Clienti casuali
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'nome' => $faker->firstName,
                'cognome' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'username' => 'cliente_' . $faker->unique()->userName,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'livello' => 2,
                'telefono' => $faker->phoneNumber,
                'data_nascita' => $faker->date('Y-m-d', '2005-01-01'),
            ]);
        }

        // 6. Creazione 10 Organizzazioni casuali
        $organizerIds = [$organizer->id]; // includiamo anche l'organizzatore principale
        for ($i = 0; $i < 10; $i++) {
            $org = User::create([
                'nome' => $faker->firstName,
                'cognome' => $faker->lastName,
                'email' => $faker->unique()->companyEmail,
                'username' => 'org_' . $faker->unique()->userName,
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'livello' => 3,
                'organizzazione' => $faker->company,
                'telefono' => $faker->phoneNumber,
                'data_nascita' => $faker->date('Y-m-d', '1990-01-01'),
            ]);
            $organizerIds[] = $org->id;
        }

        // 7. Creazione 10 Eventi per ciascuna Categoria (50 in totale)
        $categorie = ['Eventi Musicali', 'Eventi Teatrali', 'Manifestazioni Letterarie', 'Mostre', 'Convegni'];

        foreach ($categorie as $categoria) {
            for ($i = 0; $i < 10; $i++) {
                $biglietti_totali = $faker->numberBetween(50, 5000);
                \App\Models\Event::create([
                    'organizzatore_id' => $faker->randomElement($organizerIds),
                    'titolo' => ucfirst($faker->words(3, true)),
                    'descrizione' => $faker->paragraph(3),
                    'programma' => "10:00 - Inizio\n13:00 - Pausa Pranzo\n18:00 - Chiusura",
                    'data' => $faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
                    'orario' => $faker->time('H:i'),
                    'citta' => $faker->city,
                    'luogo' => $faker->streetAddress,
                    'come_raggiungere' => 'Mezzi pubblici: ' . $faker->word,
                    'categoria' => $categoria,
                    'prezzo' => $faker->randomFloat(2, 5, 150),
                    'biglietti_totali' => $biglietti_totali,
                    'biglietti_disponibili' => $biglietti_totali,
                    'sconto_giorni' => $faker->optional(0.5)->numberBetween(1, 10),
                    'sconto_percentuale' => $faker->optional(0.5)->randomFloat(2, 5, 50),
                ]);
            }
        }
    }
}
