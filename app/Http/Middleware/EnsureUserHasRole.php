<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403);
        }

        $allowed = collect($roles)
            ->filter()
            ->map(fn ($role) => strtolower($role))
            ->contains(fn ($role) => $user->role === UserRole::from($role));

        if (! $allowed) {
            return redirect()->route('unauthorized');
        }

        return $next($request);
    }
}
