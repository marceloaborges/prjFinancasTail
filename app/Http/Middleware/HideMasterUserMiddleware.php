<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class HideMasterUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Se tem usuário logado e ele NÃO é o ID 1 (Master)
        if (auth()->check() && auth()->id() !== 1) {
            // Aplica o escopo global somente nesta requisição
            User::addGlobalScope('hide_master', function ($builder) {
                $builder->where('users.id', '!=', 1);
            });
        }

        return $next($request);
    }
}
