<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Painel\UserRequest;
use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class UserController extends Controller
{
    private $pagination = 1; 
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Gate::allows('visualizar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }

        // Define uma chave única na session para os filtros DESTA tela
        $sessionKey = 'filters_users';

        // Cenário Especial: Usuário clicou explicitamente em "Limpar Filtros" (?clear=1 na URL)
        if ($request->has('clear')) {
            session()->forget($sessionKey);
            // Redireciona para a rota limpa sem o '?clear=1' ficar poluindo a URL
            return redirect()->route('painel.users.index');
        }

        // Cenário de Submissão: Usuário enviou o formulário com novos filtros
        if ($request->anyFilled(['name', 'gerente_id', 'supervisor_id'])) {
            // Salva os filtros atuais na Session
            session([$sessionKey => $request->only(['name', 'gerente_id', 'supervisor_id'])]);
        }

        // Cenário de Navegação/Voltar: Entrou na tela sem filtros na URL, mas TEM histórico salvo
        if (empty($request->query()) && session()->has($sessionKey)) {
            // Restaura o estado redirecionando com os filtros salvos!
            return redirect()->route('painel.users.index', session($sessionKey));
        }

        $filter = $request->except('_token');
        
        // Supondo que você use o Local Scope 'scopeSearch' no User também
        $users = User::search($filter)
            ->paginate($this->pagination)
            ->withQueryString();

        return view('painel.user.index', compact('users'));      
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Gate::allows('cadastrar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }

        // Passamos $usuario como null para o form.blade saber que é um cadastro
        return view('painel.user.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        if (!Gate::allows('cadastrar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }

        $data = $request->except('_token');

        // Se o acesso está desligado e não veio senha digitada, gera a randômica
        if (empty($data['password'])) {
            $data['password'] = Hash::make(Str::random(16));
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Remove o campo de confirmação do array antes de mandar pro Eloquent,
        // pois a coluna 'password_confirmation' não existe na tabela users.
        unset($data['password_confirmation']);

        // Cria o registro no banco
        $create = User::create($data);

        // ⚡ Registra o Log de Sucesso usando a sua Service rica
        LogService::success(
            acao: 'STORE',
            descricao: "Cadastrou o usuário: {$create->name} ({$create->email})",
            model: $create,
            payload: $request->except(['password', '_token']) // Protege a senha de ir para o log
        );

        sweetalert()->toast(true, 'top-end', false)
            ->timer(2500)
            ->showCloseButton(true)
            ->addSuccess('Cadastrado com Sucesso!');

        return redirect()->route('painel.users.show', $create->id);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Gate::allows('visualizar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }
        
        $usuario = User::findOrFail($id);
        return view('painel.user.show', compact('usuario'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!Gate::allows('editar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }

        $usuario = User::findOrFail($id);

        return view('painel.user.edit', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        if (!Gate::allows('editar_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();
        }

        $usuario = User::findOrFail($id);
        
        // Captura os dados no seu padrão
        $data = $request->except('_token');


        // ⚡ TRATAMENTO DOS TOGGLES (ACTIVE E ACCESS)
        $data['active'] = $request->has('active') && $request->active == '1' ? 1 : 0;
        $data['access'] = $request->has('access') && $request->access == '1' ? 1 : 0;

        // 🛡️ TRATAMENTO SELETIVO DO 2FA
        if ($request->has('two_factor_required') && $request->two_factor_required == '1') {
            $data['two_factor_required'] = 1;
        } else {
            $data['two_factor_required'] = 0;
            
            // 🧹 O PULO DO GATO: Se desativou o 2FA, limpa a mesa no banco de dados!
            $data['two_factor_secret'] = null;
            $data['two_factor_recovery_codes'] = null;
            $data['two_factor_confirmed_at'] = null;
        }

        // ⚡ A MÁGICA DA SEGURANÇA EXTRA ACONTECE AQUI
        if ($request->boolean('access') === false && $usuario->access == 1) {
            // Cenário: O usuário estava ativo (1) e o admin acabou de bloquear o acesso (0).
            // Forçamos a geração de uma nova senha aleatória de 32 caracteres para invalidar a antiga.
            $data['password'] = Hash::make(Str::random(32));
            
            $descricaoLog = "Bloqueou o acesso do usuário: {$usuario->name} e forçou a rotação da senha por segurança.";
        } elseif (!empty($data['password'])) {
            // Cenário: O admin digitou uma nova senha manualmente no formulário
            $data['password'] = Hash::make($data['password']);
            $descricaoLog = "Atualizou os dados e alterou a senha do usuário: {$usuario->name}";
        } else {
            // Cenário: Não digitou senha e não alterou o acesso para bloqueado, mantém a atual.
            unset($data['password']);
            $descricaoLog = "Atualizou os dados do usuário: {$usuario->name}";
        }

        // Sempre removemos o confirmation para não quebrar o Eloquent
        unset($data['password_confirmation']);

        // Atualiza os dados no banco
        $usuario->update($data);

        // ⚡ Registra o Log de Sucesso com a descrição dinâmica contextualizada
        LogService::success(
            acao: 'UPDATE',
            descricao: $descricaoLog,
            model: $usuario,
            payload: $request->except(['password', 'password_confirmation', '_token'])
        );

        sweetalert()->toast(true, 'top-end', false)
            ->timer(2500)
            ->showCloseButton(true)
            ->addSuccess('Atualizado com Sucesso!');

        return redirect()->route('painel.users.show', $usuario->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('excluir_usuarios')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)->timer(2500)->showCloseButton(true)->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        if (!$user = User::find($id)) {
            sweetalert()->toast(true, 'top-end', false)->timer(2500)->showCloseButton(true)->addWarning('Usuário não encontrado!');
            return redirect()->back();
        }

        // ⚡ TRY-CATCH GENÉRICO E BLINDADO PARA ERROS DE SGBD (Ex: Chaves Estrangeiras)
        try {

            // Backup dos dados para o Log antes de deletar de fato
            $dadosUsuarioLog = $user->toArray();

            $user->delete();

            // Salva na sua Service rica (CORRIGIDO: trocado $funcao por $dadosUsuarioLog)
            LogService::info(
                acao: 'DESTROY',
                descricao: "Registro Excluído: {$dadosUsuarioLog['name']} ({$dadosUsuarioLog['email']})",
                model: $user,
                payload: ['registro' => $dadosUsuarioLog]
            );

            Log::info(auth()->user()->name . ' - Excluiu um Usuário', ['Method' => __METHOD__, 'Data' => $id]);

            sweetalert()->toast(true, 'top-end', false)->timer(2500)->showCloseButton(true)->addSuccess('Excluído com Sucesso!');
            return redirect()->back();

        } catch (Exception $e) {

            // Se o banco travar por causa de relacionamentos (ex: o usuário possui pedidos vinculados)
            Log::error(auth()->user()->name . ' - Falha ao excluir usuário devido a restrições no banco de dados.', [
                'Method' => __METHOD__,
                'Error'  => $e->getMessage()
            ]);

            sweetalert()->toast(true, 'top-end', false)->timer(4000)->showCloseButton(true)
                ->addError('Não foi possível excluir! Este registro está vinculado a outras movimentações no sistema.');
            
            return redirect()->back();
        }
    }
}
