<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Unauthorized. Admin privileges required.'], 403);
        }

        return redirect()->route('login')->with('error', 'Access restricted! Please login with an admin account.');
    }
}
