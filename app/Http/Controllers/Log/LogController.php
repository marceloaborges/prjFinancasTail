<?php

namespace App\Http\Controllers\Log;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\LogTipo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log as Logs;

class LogController extends Controller
{

    private $pagination = 25;

    public function index(Request $request)
    {
        // 1. Abordagem defensiva (Early Return): Se não tem permissão, barra logo de cara.
        if (!Gate::allows('visualizar_logs')) {
            Logs::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        // Define uma chave única na session para os filtros DESTA tela
        $sessionKey = 'filters_logs';

        // Cenário 1: O usuário clicou em "Limpar Filtros" (entrou na tela sem nenhuma Query String)
        // E a URL anterior continha filtros (veio do botão de limpar)
        if (empty($request->query())) {
            // Se a requisição está 100% limpa, nós limpamos a session para resetar de fato
            session()->forget($sessionKey);
        }

        // Cenário 2: O usuário enviou novos filtros pelo formulário
        if ($request->anyFilled(['log_tipo_ids', 'user_ids', 'tabela', 'descricao'])) {
            // Guarda o array de filtros atuais na session
            session([$sessionKey => $request->only(['log_tipo_ids', 'user_ids', 'tabela', 'descricao'])]);
        }

        // Cenário 3: O usuário deu F5 ou voltou para a página sem parâmetros, mas tem histórico salvo
        if (empty($request->query()) && session()->has($sessionKey)) {
            // Redireciona injetando os filtros salvos de volta na URL para manter a consistência visual
            return redirect()->route('painel.logs.index', session($sessionKey));
        }

        $usuarios = User::orderBy('name')->get();
        $tipos = LogTipo::orderBy('descricao')->get();

        $filter = $request->except('_token');
        $logs = Log::search($filter)->paginate($this->pagination)->withQueryString();
        return view('painel.log.index', compact('logs','tipos','usuarios'));

    }
}
