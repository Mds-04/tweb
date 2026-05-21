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

    public function musicali()
    {
        $eventi = Event::where('categoria', 'Eventi Musicali')->orderBy('data', 'asc')->get();
        return view('events.eventiMusicali', compact('eventi'));
    }

    public function teatrali()
    {
        $eventi = Event::where('categoria', 'Eventi Teatrali')->orderBy('data', 'asc')->get();
        return view('events.eventiTeatrali', compact('eventi'));
    }

    public function letterarie()
    {
        $eventi = Event::where('categoria', 'Manifestazioni Letterarie')->orderBy('data', 'asc')->get();
        return view('events.manifestazioniLetterarie', compact('eventi'));
    }

    public function mostre()
    {
        $eventi = Event::where('categoria', 'Mostre')->orderBy('data', 'asc')->get();
        return view('events.mostre', compact('eventi'));
    }

    public function convegni()
    {
        $eventi = Event::where('categoria', 'Convegni')->orderBy('data', 'asc')->get();
        return view('events.convegni', compact('eventi'));
    }

    public function show($id)
    {
        $evento = Event::with('organizzatore')->findOrFail($id);
        return view('events.show', compact('evento'));
    }

    public function toggleParticipation($id)
    {
        $user = Auth::user();
        if ($user->livello != 2) return redirect()->back();
        
        if ($user->partecipazioni()->where('evento_id', $id)->exists()) {
            $user->partecipazioni()->detach($id);
        } else {
            $user->partecipazioni()->attach($id);
        }
        
        return redirect()->back();
    }
}
