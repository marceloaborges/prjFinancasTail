<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use PragmaRX\Google2FA\Google2FA; // Facade da biblioteca PragmaRX

class TwoFactorController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if (session()->has('2fa_validado')) {
            return redirect()->route('painel.dashboard');
        }

        $google2fa = new Google2FA();
        $secretKey = $user->two_factor_secret;

        // ⚡ SEGUNDA BLINDAGEM: Se ele não tem chave OU se a chave nunca foi confirmada com sucesso...
        if (empty($secretKey) || empty($user->two_factor_confirmed_at)) {
            
            // Se a chave estiver zerada no banco, gera uma nova
            if (empty($secretKey)) {
                $secretKey = $google2fa->generateSecretKey();
                $user->update([
                    'two_factor_secret' => $secretKey
                ]);
            }

            // Gera a URL limpa para o QR Code
            $qrCodeUrl = $google2fa->getQRCodeUrl(
                config('app.name', 'impaar'),
                $user->email,
                $secretKey
            );
        } else {
            // Se ele já confirmou no passado, o QR Code não precisa aparecer
            $qrCodeUrl = null;
        }

        return view('painel.user.2fa', compact('secretKey', 'qrCodeUrl'));
    }

    public function verificar(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6',
        ]);

        $user = auth()->user();
        
        // ⚡ Instancia o objeto para verificar a chave
        $google2fa = new Google2FA();
        $valido = $google2fa->verifyKey($user->two_factor_secret, $request->code);

        if ($valido) {
            // 🔓 Se for a primeira validação do cara, carimba o banco de dados
            if (empty($user->two_factor_confirmed_at)) {
                $user->update([
                    'two_factor_confirmed_at' => now()
                ]);
            }

            session()->put('2fa_validado', true);

            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->addSuccess('Autenticação de 2 fatores confirmada!');

            return redirect()->intended(route('painel.dashboard'));
        }

        return back()->withErrors([
            'code' => 'Código de verificação inválido ou expirado.'
        ])->onlyInput('code');
    }
}