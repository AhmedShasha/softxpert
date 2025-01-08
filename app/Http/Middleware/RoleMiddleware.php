<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = Auth::user();
        if ($role === 'manager' && !$user->isManager()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        if ($role === 'user' && !$user->isUser()) {
            return response()->json(['error' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
