<?php

namespace App\Services;

use App\Models\Log;
use App\Enums\LogTipo;
use Illuminate\Database\Eloquent\Model;

class LogService
{
    private static function store(
        int         $tipo,
        string      $acao,
        ?string     $descricao = null,
        ?Model      $model = null,
        ?array      $payload = null,
    ): void {

        if (blank($acao)) {
            return;
        }

        try {
            Log::create([
                'user_id'      => auth()->id(),
                'log_tipo_id'  => $tipo,
                'acao'         => $acao,
                'metodo'       => request()->route()?->getName(),
                'descricao'    => $descricao,
                'tabela'       => $model?->getTable(),
                'registro_id'  => $model?->getKey(),
                'payload'      => $payload,
            ]);

        } catch (\Throwable $e) {

            logger()->error(
                'Erro ao gravar log.',
                [
                    'exception' => $e,
                    'tipo'      => $tipo,
                    'acao'      => $acao,
                    'tabela'    => $model?->getTable(),
                    'registro'  => $model?->getKey(),
                ]
            );

        }
    }

    public static function success(
        string      $acao,
        ?string     $descricao = null,
        ?Model      $model = null,
        ?array      $payload = null
    ): void {

        self::store(
            LogTipo::SUCCESS,
            $acao,
            $descricao,
            $model,
            $payload
        );
    }

    public static function info(
        string      $acao,
        ?string     $descricao = null,
        ?Model      $model = null,
        ?array      $payload = null
    ): void {

        self::store(
            LogTipo::INFO,
            $acao,
            $descricao,
            $model,
            $payload
        );
    }

    public static function warning(
        string      $acao,
        ?string     $descricao = null,
        ?Model      $model = null,
        ?array      $payload = null
    ): void {

        self::store(
            LogTipo::WARNING,
            $acao,
            $descricao,
            $model,
            $payload
        );
    }

    public static function error(
        string      $acao,
        ?string     $descricao = null,
        ?Model      $model = null,
        ?array      $payload = null
    ): void {

        self::store(
            LogTipo::ERROR,
            $acao,
            $descricao,
            $model,
            $payload
        );
    }
}