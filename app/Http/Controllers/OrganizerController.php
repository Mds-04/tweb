<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizerController extends Controller
{
    /**
     * Mostra la dashboard dell'organizzatore
     */
    public function dashboard()
    {
        $organizzatore = Auth::user();

        // Recuperiamo gli eventi gestiti dall'organizzatore, con il numero di acquisti (biglietti venduti)
        // Calcoliamo i biglietti venduti aggregando i 'numero_biglietti' dalla relazione 'acquisti'
        $eventi = Event::with('acquisti')
            ->where('organizzatore_id', $organizzatore->id)
            ->get();

        // Arricchiamo gli eventi con calcoli per la dashboard
        foreach ($eventi as $evento) {
            $biglietti_venduti = $evento->acquisti->sum('num_biglietti');
            $incasso_totale = $evento->acquisti->sum('totale');

            $percentuale_venduti = 0;
            if ($evento->biglietti_totali > 0) {
                $percentuale_venduti = round(($biglietti_venduti / $evento->biglietti_totali) * 100, 2);
            }

            $evento->biglietti_venduti = $biglietti_venduti;
            $evento->incasso_totale = $incasso_totale;
            $evento->percentuale_venduti = $percentuale_venduti;
        }

        return view('organizer.dashboard', compact('organizzatore', 'eventi'));
    }

    /**
     * Modifica il numero di biglietti disponibili per un evento
     */
    public function updateTickets(Request $request, $id)
    {
        $request->validate([
            'biglietti_disponibili' => 'required|integer|min:0',
            'biglietti_totali' => 'required|integer|min:0',
        ]);

        $evento = Event::where('organizzatore_id', Auth::id())->findOrFail($id);

        if ($request->biglietti_disponibili > $request->biglietti_totali) {
            return redirect()->back()->withErrors(['biglietti' => 'I biglietti disponibili non possono superare il totale.']);
        }

        $evento->biglietti_totali = $request->biglietti_totali;
        $evento->biglietti_disponibili = $request->biglietti_disponibili;
        $evento->save();

        return redirect()->back()->with('success_dashboard', 'Biglietti aggiornati con successo!');
    }

    /**
     * Modifica gli sconti last-minute
     */
    public function updateDiscount(Request $request, $id)
    {
        $request->validate([
            'sconto_giorni' => 'nullable|integer|min:0',
            'sconto_percentuale' => 'nullable|numeric|min:0|max:100',
        ]);

        $evento = Event::where('organizzatore_id', Auth::id())->findOrFail($id);

        $evento->sconto_giorni = $request->sconto_giorni;
        $evento->sconto_percentuale = $request->sconto_percentuale;
        $evento->save();

        return redirect()->back()->with('success_dashboard', 'Sconti aggiornati con successo!');
    }

    /**
     * Creazione di un nuovo evento
     */
    public function storeEvent(Request $request)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'required|string',
            'programma' => 'required|string',
            'data' => 'required|date',
            'orario' => 'required|date_format:H:i',
            'citta' => 'required|string|max:255',
            'luogo' => 'required|string|max:255',
            'come_raggiungere' => 'nullable|string',
            'categoria' => 'required|string|max:255',
            'immagine' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prezzo' => 'required|numeric|min:0',
            'biglietti_totali' => 'required|integer|min:1',
        ]);

        $immaginePath = null;
        if ($request->hasFile('immagine')) {
            $immaginePath = $request->file('immagine')->store('locandine', 'public');
        }

        Event::create([
            'organizzatore_id' => Auth::id(),
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'programma' => $request->programma,
            'data' => $request->data,
            'orario' => $request->orario,
            'citta' => $request->citta,
            'luogo' => $request->luogo,
            'come_raggiungere' => $request->come_raggiungere,
            'categoria' => $request->categoria,
            'immagine' => $immaginePath,
            'prezzo' => $request->prezzo,
            'biglietti_totali' => $request->biglietti_totali,
            'biglietti_disponibili' => $request->biglietti_totali, // Alla creazione i disponibili sono uguali al totale
        ]);

        return redirect()->back()->with('success_dashboard', 'Evento creato con successo!');
    }

    /**
     * Modifica di un evento esistente
     */
    public function updateEvent(Request $request, $id)
    {
        $request->validate([
            'titolo' => 'required|string|max:255',
            'descrizione' => 'required|string',
            'programma' => 'required|string',
            'data' => 'required|date',
            'orario' => 'required|date_format:H:i', // Format could be H:i:s depending on input
            'citta' => 'required|string|max:255',
            'luogo' => 'required|string|max:255',
            'come_raggiungere' => 'nullable|string',
            'categoria' => 'required|string|max:255',
            'immagine' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'prezzo' => 'required|numeric|min:0',
        ]);

        $evento = Event::where('organizzatore_id', Auth::id())->findOrFail($id);

        $immaginePath = $evento->immagine;
        if ($request->hasFile('immagine')) {
            $immaginePath = $request->file('immagine')->store('locandine', 'public');
        }

        $evento->update([
            'titolo' => $request->titolo,
            'descrizione' => $request->descrizione,
            'programma' => $request->programma,
            'data' => $request->data,
            'orario' => $request->orario,
            'citta' => $request->citta,
            'luogo' => $request->luogo,
            'come_raggiungere' => $request->come_raggiungere,
            'categoria' => $request->categoria,
            'immagine' => $immaginePath,
            'prezzo' => $request->prezzo,
        ]);

        return redirect()->back()->with('success_dashboard', 'Evento modificato con successo!');
    }

    /**
     * Eliminazione di un evento
     */
    public function destroyEvent($id)
    {
        $evento = Event::where('organizzatore_id', Auth::id())->findOrFail($id);
        $evento->delete();

        return redirect()->back()->with('success_dashboard', 'Evento eliminato con successo!');
    }
}
