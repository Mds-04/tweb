<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOrganizer
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->livello == 3) {
            return $next($request);
        }

        abort(403, 'Accesso negato. Solo gli organizzatori possono visualizzare questa pagina.');
    }
}
