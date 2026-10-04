<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AccessRules
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if (! in_array($user->role, $roles, true)) {
            abort(403, "you aren't allowed to view this page");
        }

        return $next($request);
    }
}
