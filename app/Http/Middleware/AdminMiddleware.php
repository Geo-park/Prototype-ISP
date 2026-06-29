<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && in_array(auth()->user()->role, ['superadmin', 'admin']) && auth()->user()->is_active) {
            return $next($request);
        }

        abort(403, 'Akses ditolak.');
    }
}
