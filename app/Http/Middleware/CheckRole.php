<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, $requiredRole): Response
    {
        $role = $request->user()->role;
        if ($role !== $requiredRole) {
            abort(403);
        }

        return $next($request);
    }
}
