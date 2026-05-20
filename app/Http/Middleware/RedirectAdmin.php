<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Se l'utente è un admin
        if (Auth::check() && Auth::user()->livello == 4) {
            // E la rotta corrente non inizia con 'admin' e non è 'logout'
            if (!$request->is('admin*') && !$request->is('logout')) {
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}
