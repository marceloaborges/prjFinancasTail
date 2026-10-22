<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Support\Facades\Hash;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        return;
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::redirectUserForTwoFactorAuthenticationUsing(RedirectIfTwoFactorAuthenticatable::class);

        Fortify::loginView(function () {
            return view('pages.auth.login');
        });

        Fortify::authenticateUsing(function (Request $request) {

            // ⚡ Higieniza o e-mail vindo do request (remove espaços e força minúsculo)
            $email = $request->email ? strtolower(trim($request->email)) : null;

            // 1. Busca o usuário pelo e-mail
            $user = User::where('email', $request->email)->first();

            // Verifica se Usuário existe e a senha bate, Faz a esteira de validações de segurança
            // 🛑 Bloqueia se o access estiver false no banco
            // 🛑 Bloqueia se o active estiver false no banco
            if ($user && Hash::check($request->password, $user->password) && $user->access == 1 && $user->active == 1 ){
                
                // ⚡ Dispara o método SUCCESS da sua Service
                LogService::success(
                    acao: 'LOGIN',
                    descricao: "Usuário {$user->name} realizou acesso ao sistema.",
                    model: $user, // Passamos o model completo! A service vai extrair a tabela 'users' e o ID automaticamente
                    payload: [
                        'status' => 'sucesso',
                        'usuario' => [
                            'id'    => $user->id,
                            'nome'  => $user->name,
                            'email' => $user->email,
                        ],
                        'contexto' => [
                            'horario'    => now()->format('Y-m-d H:i:s'),
                            'ip'         => $request->ip(),
                            'user_agent' => $request->header('User-Agent'),
                        ]
                    ]
                );
                return $user;
            }

            // 3. FALHA: E-mail não existe ou senha errada
            // ⚡ Dispara o método WARNING ou ERROR da sua Service (você escolhe o nível do Alerta)
            LogService::warning(
                acao: 'FALHA_LOGIN',
                descricao: "Tentativa de login malsucedida para o e-mail: " . $request->email,
                model: null, // Não há model aqui, passamos null
                payload: [
                    'status' => 'falha',
                    'tentativa' => [
                        'email_digitado' => $request->email,
                    ],
                    'contexto' => [
                        'horario'    => now()->format('Y-m-d H:i:s'),
                        'ip'         => $request->ip(),
                        'user_agent' => $request->header('User-Agent'),
                    ]
                ]
            );

            sweetalert()->toast(true, 'top-start', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addError('Erro ao realizar login. Verifique os dados informados e tente novamente.');

            return null;
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            $credentialId = $request->input('credential.id');

            return Limit::perMinute(10)->by(
                ($credentialId ?: $request->session()->getId()).'|'.$request->ip()
            );
        });
    }
}
