<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if (Auth::user()->livello === 4) {
            return redirect()->route('admin.dashboard')->with('success_login', 'Accesso effettuato con successo!');
        }
        if (Auth::user()->livello === 3) {
            return redirect()->route('organizer.dashboard')->with('success_login', 'Accesso effettuato con successo!');
        }
        
        // redirect()->intended() in Breeze by default, ma con back() e fallback per il modal
        // poichè la request proviene dalla stessa pagina del modal
        return redirect()->back()->with('success_login', 'Accesso effettuato con successo!');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
