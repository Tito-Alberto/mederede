<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!$request->user()) {
            return response('Não autorizado', 401);
        }

        if (!in_array($request->user()->role, $roles)) {
            return response('Acesso proibido. Você não tem permissão para acessar este recurso.', 403);
        }

        return $next($request);
    }
}
