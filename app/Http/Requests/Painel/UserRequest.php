<?php

namespace App\Http\Requests\Painel;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        $userId = $this->route('user') ?? $this->route('id');

        return [
            'name'      => 'required|string|max:255',
            'email'     => [
                'required', 'string', 'email', 'max:255',
                $isUpdate ? Rule::unique('users')->ignore($userId) : 'unique:users,email',
            ],
            
            // ⚡ Regra de Senha Segura e Prática
            'password'  => [
                $isUpdate ? 'nullable' : 'required_if:access,1', // Requerido só se access for true no cadastro
                'nullable',
                'string',
                'min:8',
                'confirmed' // 💡 Valida automaticamente se password == password_confirmation
            ],
            
            'funcao_id'           => 'required|exists:funcoes,id',
            'ciclo_id'            => 'nullable|exists:ciclos,id',
            'supervisor_id'       => 'nullable|exists:users,id',
            'gerente_id'          => 'nullable|exists:users,id',
            'active'              => 'required|boolean',
            'access'              => 'required|boolean',
            'two_factor_required' => 'required|boolean',
        ];

    }    

    /**
     * Customizar as mensagens de erro (Deixando o sistema comercial super amigável)
     */
    public function messages(): array
    {
        return [
            'name.required'                 => 'O campo nome completo é obrigatório.',
            'name.max'                      => 'O nome não pode ter mais de 255 caracteres.',
            'email.required'                => 'O campo e-mail é obrigatório.',
            'email.email'                   => 'Por favor, insira um endereço de e-mail válido.',
            'email.unique'                  => 'Este e-mail já está sendo utilizado por outro usuário.',
            'password.required_if'          => 'A senha é obrigatória quando o acesso geral está liberado.',
            'password.confirmed'            => 'A confirmação de senha não confere.',
            'password.min'                  => 'A senha deve conter no mínimo 8 caracteres.',
            'funcao_id.required'            => 'Selecione uma função/cargo para o usuário.',
            'funcao_id.exists'              => 'A função selecionada é inválida.',
            'ciclo_id.exists'               => 'O ciclo selecionado é inválido.',
            'supervisor_id.exists'          => 'O supervisor selecionado é inválido.',
            'gerente_id.exists'             => 'O gerente selecionado é inválido.',
            'active.required'               => 'Defina se o usuário está ativo.',
            'access.required'               => 'Defina a permissão de acesso geral.',
            'two_factor_required.required'  => 'Defina a obrigatoriedade do segundo fator de autenticação.',
        ];
    }

}
