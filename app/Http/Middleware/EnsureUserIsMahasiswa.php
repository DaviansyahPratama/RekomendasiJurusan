<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsMahasiswa
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isMahasiswa()) {
            abort(403, 'Akses hanya untuk mahasiswa.');
        }

        return $next($request);
    }
}
