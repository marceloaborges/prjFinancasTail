<?php

namespace App\Helpers;

class Format
{
    /**
     * Formata CPF ou CNPJ limpo para exibição mascarada
     */
    public static function documento(?string $documento): string
    {
        if (!$documento) {
            return '';
        }

        $docLimpo = preg_replace("/\D/", '', $documento);

        if (strlen($docLimpo) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $docLimpo);
        }

        if (strlen($docLimpo) === 14) {
            return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $docLimpo);
        }

        return $documento; // Fallback
    }

    /**
     * Formata Telefone (Celular ou Fixo) limpo para exibição mascarada
     */
    public static function telefone(?string $telefone): string
    {
        if (!$telefone) {
            return '-';
        }

        $foneLimpo = preg_replace("/\D/", '', $telefone);

        if (strlen($foneLimpo) === 11) {
            return preg_replace("/(\d{2})(\d{5})(\d{4})/", "($1) $2-$3", $foneLimpo);
        }

        if (strlen($foneLimpo) === 10) {
            return preg_replace("/(\d{2})(\d{4})(\d{4})/", "($1) $2-$3", $foneLimpo);
        }

        return $telefone; // Fallback
    }
}