<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $clienti = User::where('livello', 2)->get();
        $organizzatori = User::with(['eventiOrganizzati.acquisti'])->where('livello', 3)->get();

        foreach ($organizzatori as $org) {
            $biglietti_venduti = 0;
            $incasso_totale = 0;
            foreach ($org->eventiOrganizzati as $evento) {
                $biglietti_venduti += $evento->acquisti->sum('num_biglietti');
                $incasso_totale += $evento->acquisti->sum('totale');
            }
            $org->biglietti_venduti = $biglietti_venduti;
            $org->incasso_totale = $incasso_totale;
        }

        return view('admin.dashboard', compact('clienti', 'organizzatori'));
    }

    public function storeOrganizer(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'organizzazione' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
        ]);

        User::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'livello' => 3,
            'organizzazione' => $request->organizzazione,
            'telefono' => $request->telefono,
        ]);

        return redirect()->back()->with('success_admin', 'Organizzatore creato con successo!');
    }

    public function updateOrganizer(Request $request, $id)
    {
        $organizer = User::where('livello', 3)->findOrFail($id);

        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'organizzazione' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
        ]);

        $organizer->nome = $request->nome;
        $organizer->cognome = $request->cognome;
        $organizer->username = $request->username;
        $organizer->email = $request->email;
        $organizer->organizzazione = $request->organizzazione;
        $organizer->telefono = $request->telefono;

        if ($request->filled('password')) {
            $organizer->password = Hash::make($request->password);
        }

        $organizer->save();

        return redirect()->back()->with('success_admin', 'Organizzatore modificato con successo!');
    }

    public function destroyOrganizer($id)
    {
        $organizer = User::where('livello', 3)->findOrFail($id);
        $organizer->delete();

        return redirect()->back()->with('success_admin', 'Organizzatore eliminato con successo!');
    }

    public function destroyClient($id)
    {
        $client = User::where('livello', 2)->findOrFail($id);
        $client->delete();

        return redirect()->back()->with('success_admin', 'Cliente eliminato con successo!');
    }
}
