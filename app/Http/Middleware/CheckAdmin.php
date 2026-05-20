<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->livello == 4) {
            return $next($request);
        }

        abort(403, 'Accesso negato. Solo gli amministratori possono visualizzare questa pagina.');
    }
}
