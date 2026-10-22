<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTwoFactor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Se o usuário não estiver logado, deixa o Laravel seguir para o fluxo de login padrão
        if (!auth()->check()) {
            return $next($request);
        }

        $user = auth()->user();

        // 2. Verifica se a conta exige 2FA e se a sessão atual AINDA NÃO foi validada
        if ($user->two_factor_required && !session()->has('2fa_validado')) {
            
            // 🛑 Proteção contra loop infinito: Se ele já estiver indo para as rotas do próprio 2FA, deixa passar!
            if ($request->routeIs('painel.2fa.*') || $request->routeIs('logout')) {
                return $next($request);
            }

            // Caso contrário, bloqueia e redireciona para a tela de inserção do código
            return redirect()->route('painel.2fa.index');
        }

        // Se o usuário não exige 2FA ou já validou na sessão, vida que segue!
        return $next($request);
    }
}
