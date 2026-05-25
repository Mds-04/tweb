<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class PublicController extends Controller
{
    public function home()
    {
        if (Auth::check() && Auth::user()->livello === 3) {
            return redirect()->route('organizer.dashboard');
        }

        // Recupera i prossimi 8 eventi (ordinati per data crescente a partire da oggi)
        $prossimiEventi = Event::where('data', '>=', now()->toDateString())
            ->orderBy('data', 'asc')
            ->take(12)
            ->get();

        // Fallback: se non ci sono eventi futuri
        if ($prossimiEventi->isEmpty()) {
            $prossimiEventi = Event::orderBy('data', 'asc')->take(12)->get();
        }

        // 6 eventi per ogni categoria per riempire le altre sezioni della home
        $categorie = ['Eventi Musicali', 'Eventi Teatrali', 'Manifestazioni Letterarie', 'Mostre', 'Convegni'];
        $eventiPerCategoria = [];
        foreach ($categorie as $cat) {
            $eventiPerCategoria[$cat] = Event::where('categoria', $cat)
                ->orderBy('data', 'asc')
                ->take(6)
                ->get();
        }

        return view('index', compact('prossimiEventi', 'eventiPerCategoria'));
    }

    public function musicali(Request $request)
    {
        $eventi = $this->filterEvents('Eventi Musicali', $request);
        return view('events.eventiMusicali', compact('eventi'));
    }

    public function teatrali(Request $request)
    {
        $eventi = $this->filterEvents('Eventi Teatrali', $request);
        return view('events.eventiTeatrali', compact('eventi'));
    }

    public function letterarie(Request $request)
    {
        $eventi = $this->filterEvents('Manifestazioni Letterarie', $request);
        return view('events.manifestazioniLetterarie', compact('eventi'));
    }

    public function mostre(Request $request)
    {
        $eventi = $this->filterEvents('Mostre', $request);
        return view('events.mostre', compact('eventi'));
    }

    public function convegni(Request $request)
    {
        $eventi = $this->filterEvents('Convegni', $request);
        return view('events.convegni', compact('eventi'));
    }

    private function filterEvents($categoria, Request $request)
    {
        $query = Event::where('categoria', $categoria);
        if ($request->filled('search')) {
            $query->where('titolo', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('luogo') && $request->luogo !== 'Tutta Italia') {
            $query->where(function ($q) use ($request) {
                $q->where('citta', 'like', '%' . $request->luogo . '%')
                  ->orWhere('luogo', 'like', '%' . $request->luogo . '%');
            });
        }
        return $query->orderBy('data', 'asc')->get();
    }

    public function search(Request $request)
    {
        $query = Event::query();
        if ($request->filled('q')) {
            $query->where('titolo', 'like', '%' . $request->q . '%')
                  ->orWhere('descrizione', 'like', '%' . $request->q . '%');
        }
        $eventi = $query->orderBy('data', 'asc')->get();
        return view('search_results', compact('eventi'));
    }

    public function show($id)
    {
        $evento = Event::with('organizzatore')->findOrFail($id);
        return view('events.show', compact('evento'));
    }

    public function toggleParticipation($id)
    {
        $user = Auth::user();
        if ($user->livello != 2) {
            return redirect()->back();
        }

        if ($user->partecipazioni()->where('evento_id', $id)->exists()) {
            $user->partecipazioni()->detach($id);
        } else {
            $user->partecipazioni()->attach($id);
        }

        return redirect()->back();
    }
}
