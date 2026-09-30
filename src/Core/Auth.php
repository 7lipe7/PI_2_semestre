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

        // Só limpa a sessão se havia alguém autenticado (sessão expirou).
        //
        // Visitante anônimo não tem o que limpar — e apagar $_SESSION aqui
        // destruiria o token CSRF que a tela de login acabou de criar. Como
        // o navegador pede /favicon.ico sozinho e essa URL cai neste gate,
        // a limpeza invalidava o formulário de login ("Sessão expirada").
        if (self::check()) {
            Session::expirar();
        }

        Session::setFlash('Faça login para continuar.');

        header('Location: /login');
        exit;
    }
}
