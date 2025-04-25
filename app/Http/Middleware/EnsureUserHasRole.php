<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {  

        $currentRole = auth()->user()->role;
        if(in_array($currentRole, $roles)){
            return $next($request);
        }

        if ($currentRole === 'client' && auth()->user()->approved_by === null) {
           
            abort(403, "This account is not approved yet");
        }
        abort(403, "You don't have permission to access this page");
    }
}