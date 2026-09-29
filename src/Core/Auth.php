<?php

declare(strict_types=1);

namespace Core;

final class Auth
{
    public static function check(): bool
    {
        return Session::get('usuario_id') !== null;
    }

    public static function usuario(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id'   => (int) Session::get('usuario_id', 0),
            'nome' => (string) Session::get('usuario_nome', ''),
        ];
    }

    /** Chame no index.php: se não estiver logado, manda pro /login. */
    public static function exigirLogin(): void
    {
        if (self::check() && Session::dentroDoTempo()) {
            return;
        }

        Session::destruir();
        Session::start();
        Session::setFlash('Faça login para continuar.');

        header('Location: /login');
        exit;
    }
}
