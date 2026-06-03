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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->role, $roles, true)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        if (in_array($user->role, ['admin', 'petani'], true) && ! $user->organization_id) {
            return redirect()->route('account.pending');
        }

        if ($user->role === 'admin' && ! $user->isActive()) {
            return redirect()->route('account.pending');
        }

        return $next($request);
    }
}
