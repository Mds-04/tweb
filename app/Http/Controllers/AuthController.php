<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Validazione base dei campi con messaggi in italiano
        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'ruolo' => 'required|in:cliente,organizzatore',
            'data_nascita' => 'nullable|date',
        ], [
            'nome.required' => 'Il nome è obbligatorio.',
            'cognome.required' => 'Il cognome è obbligatorio.',
            'email.required' => 'L\'email è obbligatoria.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.unique' => 'L\'utente risulta già registrato nel sistema.',
            'password.required' => 'La password è obbligatoria.',
            'password.min' => 'La password deve contenere almeno 6 caratteri.',
        ]);

        // 2. Determinazione livello
        $livello = ($request->ruolo === 'organizzatore') ? 3 : 2;

        // 3. Creazione utente
        $user = User::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'livello' => $livello,
            'data_nascita' => $request->data_nascita,
        ]);

        // 4. Log in automatico dopo registrazione
        Auth::login($user);

        // 5. Ritorno con script JavaScript per chiudere il modal e mostrare successo
        // Essendo che la form viene ricaricata nella stessa pagina, ritorniamo back con un messaggio in sessione
        return redirect()->back()->with('success_registration', 'Registrazione completata con successo! Benvenuto ' . $user->nome);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->has('ricordami'))) {
            $request->session()->regenerate();
            if (Auth::user()->livello === 4) {
                return redirect()->route('admin.dashboard')->with('success_login', 'Accesso effettuato con successo!');
            }
            if (Auth::user()->livello === 3) {
                return redirect()->route('organizer.dashboard')->with('success_login', 'Accesso effettuato con successo!');
            }
            return redirect()->back()->with('success_login', 'Accesso effettuato con successo!');
        }

        return redirect()->back()->withErrors([
            'login_error' => 'Le credenziali inserite non sono corrette.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'data_nascita' => 'nullable|date',
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->nome = $request->nome;
        $user->cognome = $request->cognome;
        $user->data_nascita = $request->data_nascita;
        $user->username = $request->username;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->back()->with('success_profile', 'Profilo aggiornato con successo!');
    }
}
