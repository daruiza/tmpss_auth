<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            return $next($request);
        }

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        // Modify this to redirect to a different route if necessary
        return redirect()->route('your_custom_route');
    }
}
