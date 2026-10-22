<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class MoneyCast implements CastsAttributes
{
    /**
     * Ao LER do banco: Formata como decimal simples
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): float
    {
        return (float) ($value ?? 0);
    }

    /**
     * Ao GRAVAR no banco: Converte a string/máscara pt-BR para float válido
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): float
    {
        if (empty($value)) {
            return 0.0;
        }

        if (is_numeric($value)) {
            return (float) $value;
        }

        $limpo = preg_replace('/[^\d,-]/', '', (string) $value);
        return (float) str_replace(',', '.', $limpo);
    }
}