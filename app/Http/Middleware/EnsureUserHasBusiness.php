<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasBusiness
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login')->with('error', 'Please log in.');
        }

        if ($request->user()->business_id === null) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'User must be assigned to a business.'], 403);
            }

            return redirect()->route('business.create')->with('error', 'Create or join a business first.');
        }

        return $next($request);
    }
}
