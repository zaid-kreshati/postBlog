<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;


class CheckAdminAuth 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    public function handle($request, Closure $next)
    {
        if (!Auth::check() || !Auth::user()->hasRole('admin')) {
            // For web: redirect with error
            if (!$request->expectsJson()) {
                return redirect()->route('DashBoard.login.form')->with('error', 'You are not authorized to access this page.');
            }

            // For API: return unauthorized response
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}
