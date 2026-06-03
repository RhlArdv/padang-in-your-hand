<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Cara pakai di route:
     * ->middleware('CheckRole:admin')
     * ->middleware('CheckRole:admin,operator')
     * ->middleware('CheckRole:super_admin,admin,operator')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user()) {
            return $this->unauthorized($request, 'Unauthenticated.', 401);
        }

        if (! in_array($request->user()->role, $roles)) {
            return $this->unauthorized($request, 'Akses ditolak. Anda tidak memiliki izin untuk aksi ini.', 403);
        }

        return $next($request);
    }

    /**
     * Return JSON for API requests, redirect/abort for web requests.
     */
    private function unauthorized(Request $request, string $message, int $status): Response
    {
        if ($request->expectsJson() || $request->is('api/*')) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        if ($status === 401) {
            return redirect()->guest(route('login'));
        }

        abort(403, $message);
    }
}