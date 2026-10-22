<?php

namespace App\Http\Controllers\Painel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Funcao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Services\LogService;
use App\Http\Requests\Painel\FuncaoRequest;

class CargoController extends Controller
{
    private $pagination = 14; 
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Abordagem defensiva (Early Return): Se não tem permissão, barra logo de cara.
        if (!Gate::allows('visualizar_cargos')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        $filter = $request->except('_token');
        $cargos = Funcao::search($filter)
            ->paginate($this->pagination)
            ->withQueryString();
        return view('painel.cargo.index', compact('cargos')); 
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FuncaoRequest $request)
    {
        if (!Gate::allows('cadastrar_cargos')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        $data = $request->except('_token');

        if(!isset($data['ativa']) ) {
            $data['ativa'] = 0;
        }

        $store = Funcao::create($data);
        if($store) {
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addSuccess('Cadastrado com Sucesso!');
            LogService::info(
                'STORE', /*acao*/ 
                'Registro Cadastrado.', /*descricao*/ 
                $store,
                [
                    'novo' => $store->toArray()
                ]
            );
            Log::info(auth()->user()->name . ' - Cadastrou um Cargo', ['Method' => __METHOD__, 'Data' => $data]);
            return redirect()->back();
        }

        sweetalert()->toast(true, 'top-end', false)
            ->timer(2500)
            ->showCloseButton(true)
            ->addError('Erro ao Cadastrar!');
        Log::error(auth()->user()->name . ' - Erro ao Cadastrar um Cargo', ['Method' => __METHOD__, 'Data' => $data]);
        return redirect()->back();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FuncaoRequest $request, string $id)
    {
        if (!Gate::allows('atualizar_cargos')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        $data = $request->except('_token');

        if(!isset($data['ativa']) ) {
            $data['ativa'] = 0;
        }

        $funcao = Funcao::find($id);
        $antes = $funcao->toArray();

        if($funcao->users->count() > 0) {
            sweetalert()->toast(true, 'top-end', false)   
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Item não pode ser atualizado!');
            Log::warning(auth()->user()->name . ' - Tentou Atualizar um Cargo com usuarios vinculados', ['Method' => __METHOD__, 'Data' => $data]);
            return redirect()->back();
        }

        $update =  $funcao->update($data);
        if($update) {
            $depois = $funcao->fresh()->toArray();
            sweetalert()->toast(true, 'top-end', false)   
                ->timer(2500)
                ->showCloseButton(true)
                ->addSuccess('Atualizado com Sucesso!');
            LogService::info(
                'UPDATE', /*acao*/
                'Registro atualizado.', /*descricao*/
                $funcao,
                [
                    'antes' => $antes,
                    'depois' => $depois, 
                    /* Fresh recarrega o registro do banco após o update(), garantindo que o log contenha exatamente os dados persistidos.*/
                ]
            );
            Log::info(auth()->user()->name . ' - Atualizou um Cargo', ['Method' => __METHOD__, 'Data' => $data]);
            return redirect()->back();
        }

        sweetalert()->toast(true, 'top-end', false)
            ->timer(2500)
            ->showCloseButton(true)
            ->addError('Erro ao Atualizar!');
        Log::error(auth()->user()->name . ' - Erro ao Atualizar um Cargo', ['Method' => __METHOD__, 'Data' => $data]);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!Gate::allows('excluir_cargos')) {
            Log::warning(auth()->user()->name . ' - Tentou Realizar uma Operação Não Autorizada', ['Method' => __METHOD__]);
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Permissão de acesso negada!');
            return redirect()->back();  
        }

        $funcao = Funcao::find($id);

        if($funcao->users->count() > 0) {
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addWarning('Item não pode ser excluído!');
            LogService::warning(
                'DESTROY', /*acao*/
                'Tentou excluir uma função com usuários vinculados.', /*descricao*/
                $funcao, /*tabela*/
                [
                    'registro' => $funcao->toArray(),
                ]
            );
            Log::warning(auth()->user()->name . ' - Tentou Excluir um Cargo com usuarios vinculados', ['Method' => __METHOD__, 'Data' => $id]);
            return redirect()->back();
        }
        
        $delete =  $funcao->delete();
        if($delete) {
            sweetalert()->toast(true, 'top-end', false)
                ->timer(2500)
                ->showCloseButton(true)
                ->addSuccess('Excluído com Sucesso!');
            LogService::info(
                'DESTROY', /*acao*/
                'Registro Excluído.', /*descricao*/
                $funcao, /*tabela*/
                [
                    'registro' => $funcao->toArray(),
                ]
            );
            Log::info(auth()->user()->name . ' - Excluiu um Cargo', ['Method' => __METHOD__, 'Data' => $id]);
            return redirect()->back();
        }

        sweetalert()->toast(true, 'top-end', false)
            ->timer(2500)
            ->showCloseButton(true)
            ->addError('Erro ao Excluir!');
        Log::error(auth()->user()->name . ' - Erro ao Excluir um Cargo', ['Method' => __METHOD__, 'Data' => $id]);
        return redirect()->back();
    }
}