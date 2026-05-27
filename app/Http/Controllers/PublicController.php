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

    public function category($slug, Request $request)
    {
        $map = [
            'musicali' => 'Eventi Musicali',
            'teatrali' => 'Eventi Teatrali',
            'letterarie' => 'Manifestazioni Letterarie',
            'mostre' => 'Mostre',
            'convegni' => 'Convegni'
        ];

        if (!array_key_exists($slug, $map)) {
            abort(404);
        }

        $categoria = $map[$slug];
        $eventi = $this->filterEvents($categoria, $request);
        
        return view('events.category', compact('eventi', 'categoria', 'slug'));
    }

    private function filterEvents(string $categoria, Request $request)
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
        $eventi = Event::with('organizzatore')
            ->when($request->q, function ($query, $q) {
                $q = trim($q);
                if (str_ends_with($q, '*')) {
                    $cleanTerm = rtrim($q, '*');
                    
                    // Match if description starts with term OR contains a space followed by term
                    return $query->where(function ($r) use ($cleanTerm) {
                        $r->where('descrizione', 'LIKE', $cleanTerm . '%')
                          ->orWhere('descrizione', 'LIKE', '% ' . $cleanTerm . '%');
                    });
                } else {
                    return $query->where(function ($r) use ($q) {
                        $r->where('descrizione', 'LIKE', $q . ' %')
                          ->orWhere('descrizione', 'LIKE', '% ' . $q . ' %')
                          ->orWhere('descrizione', 'LIKE', '% ' . $q)
                          ->orWhere('descrizione', '=', $q);
                    });
                }
            })
            ->when($request->luogo, function ($query, $luogo) {
                // Filtro per città (AND logico essendo incatenato al query builder)
                return $query->where('citta', 'like', '%' . $luogo . '%');
            })
            ->orderBy('data')
            ->get();
        return view('search_results', compact('eventi'));
    }

    public function locations(Request $request)
    {
        $term = $request->input('q', '');
        
        $query = Event::query();
        if ($term) {
            $query->where('citta', 'like', '%' . $term . '%');
        }
        
        // Fetch distinct cities that are not null or empty
        $citta = $query->select('citta')
                       ->whereNotNull('citta')
                       ->where('citta', '!=', '')
                       ->distinct()
                       ->orderBy('citta', 'asc')
                       ->pluck('citta');
                       
        return response()->json($citta);
    }

    public function show(int $id)
    {
        $evento = Event::with('organizzatore')->findOrFail($id);
        return view('events.show', compact('evento'));
    }

    public function toggleParticipation(int $id)
    {
        /** @var \App\Models\User $user */
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
