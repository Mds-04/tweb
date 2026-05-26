<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validateWithBag('register', [
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'data_nascita' => 'nullable|date_format:Y-m-d|before:today|after:1900-01-01',
        ], [
            'nome.required' => 'Il nome è obbligatorio.',
            'cognome.required' => 'Il cognome è obbligatorio.',
            'email.required' => 'L\'email è obbligatoria.',
            'email.email' => 'Inserisci un indirizzo email valido.',
            'email.unique' => 'L\'utente risulta già registrato nel sistema.',
            'password.required' => 'La password è obbligatoria.',
            'password.min' => 'La password deve contenere almeno 6 caratteri.',
            'data_nascita.date_format' => 'Il formato della data non è valido.',
            'data_nascita.before' => 'La data di nascita deve essere nel passato.',
            'data_nascita.after' => 'La data di nascita inserita non è valida.',
        ]);

        $user = User::create([
            'nome' => $request->nome,
            'cognome' => $request->cognome,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'livello' => 2,
            'data_nascita' => $request->data_nascita,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect()->back()->with('success_registration', 'Registrazione completata con successo! Benvenuto ' . $user->nome);
    }
}
