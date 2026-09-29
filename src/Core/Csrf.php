<?php

declare(strict_types=1);

namespace Core;

final class Csrf
{
    private const CHAVE = '_csrf';

    public static function token(): string
    {
        $token = Session::get(self::CHAVE);

        if (!is_string($token) || $token === '') {
            $token = bin2hex(random_bytes(32));
            Session::put(self::CHAVE, $token);
        }

        return $token;
    }

    /** Campo hidden pronto para soltar dentro de qualquer <form>. */
    public static function campo(): string
    {
        return '<input type="hidden" name="_csrf" value="' . e(self::token()) . '">';
    }

    /** hash_equals() compara em tempo constante. */
    public static function valido(?string $recebido): bool
    {
        $esperado = Session::get(self::CHAVE);

        if (!is_string($esperado) || $esperado === '' || !is_string($recebido) || $recebido === '') {
            return false;
        }

        return hash_equals($esperado, $recebido);
    }

    public static function exigir(?string $recebido = null): void
    {
        $recebido ??= $_POST['_csrf'] ?? null;

        if (self::valido(is_string($recebido) ? $recebido : null)) {
            return;
        }

        error_log('CSRF recusado em ' . ($_SERVER['REQUEST_URI'] ?? '?'));

        http_response_code(419);
        header('Content-Type: text/html; charset=utf-8');

        echo '<!DOCTYPE html><meta charset="utf-8"><title>Sessão expirada</title>'
            . '<h1>Sessão expirada</h1>'
            . '<p>Recarregue a página e tente de novo.</p>'
            . '<p><a href="/login">Voltar para o login</a></p>';

        exit;
    }
}
