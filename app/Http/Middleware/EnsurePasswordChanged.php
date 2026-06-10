<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePasswordChanged
{
    /**
     * Exempt routes that must remain accessible during forced password change.
     */
    private const EXEMPT_ROUTES = [
        'password.change',
        'password.change.update',
        'logout',
    ];

    /**
     * Redirect authenticated users with must_change_password = true
     * to the password change page before they can access anything else.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (
            Auth::check()
            && Auth::user()->must_change_password
            && ! in_array($request->route()?->getName(), self::EXEMPT_ROUTES, true)
        ) {
            return redirect()->route('password.change');
        }

        return $next($request);
    }
}
