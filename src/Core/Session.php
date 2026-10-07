<?php

declare(strict_types=1);

namespace Core;

final class Session
{
    public const TIMEOUT_SEGUNDOS = 1800;

    private const CHAVE_TEMPO = 'usuario_ultima_atividade';
    private const CHAVE_FLASH = '_flash';
    private const CHAVE_LOGIN_TENTATIVA = '_login_tentativa';

    public static function start(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        // O id da sessão passa a ser sempre gerado pelo servidor
        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1'); // recusa ?PHPSESSID= na URL

        session_name('PETSHOPSESSID');

        // O flag 'secure' do cookie só deve ser false em dev local (HTTP puro).
        // Em produção, quando o container é atingido por um proxy reverso em TLS,
        // basta definir SESSION_SECURE_COOKIE=1 no .env (ou deixar o proxy
        // definir $_SERVER['HTTPS']). Se vazio, mantém o sinal viaHttps().
        $secure = (bool) getenv('SESSION_SECURE_COOKIE') || self::viaHttps();

        session_set_cookie_params([
            'lifetime' => 0,           // morre ao fechar o navegador
            'path'     => '/',
            'secure'   => $secure,
            'httponly' => true,        // JavaScript não lê o cookie
            'samesite' => 'Lax',       // não reenvia quando vem de outro site
        ]);

        session_start();
    }

    public static function viaHttps(): bool
    {
        if (!empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off') {
            return true;
        }

        return strtolower(trim((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))) === 'https';
    }

    public static function put(string $chave, mixed $valor): void
    {
        $_SESSION[$chave] = $valor;
    }

    public static function get(string $chave, mixed $padrao = null): mixed
    {
        return $_SESSION[$chave] ?? $padrao;
    }

    public static function forget(string $chave): void
    {
        unset($_SESSION[$chave]);
    }

    /** Regenera o id preservando os dados — impede session fixation. */
    public static function regenerar(): void
    {
        session_regenerate_id(true);
    }

    public static function destruir(): void
    {
        $_SESSION = [];

        // Sem sessão ativa o PHP emite um warning — e warning vira saída,
        // o que por sua vez impede os headers de saírem depois.
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();

            setcookie(session_name(), '', [
                'expires'  => time() - 42000,
                'path'     => $p['path'],
                'domain'   => $p['domain'],
                'secure'   => $p['secure'],
                'httponly' => $p['httponly'],
                'samesite' => $p['samesite'] ?? 'Lax',
            ]);
        }

        session_destroy();
    }

    /**
     * Derruba a autenticação sem matar a sessão.
     *
     * Os dados somem e o id troca (invalida qualquer cópia que tenha vazado),
     * mas a sessão continua viva para carregar o flash até o /login.
     */
    public static function expirar(): void
    {
        $_SESSION = [];

        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    /** False quando passou do tempo de inatividade. */
    public static function dentroDoTempo(): bool
    {
        $ultima = (int) self::get(self::CHAVE_TEMPO, 0);

        if ($ultima === 0 || (time() - $ultima) > self::TIMEOUT_SEGUNDOS) {
            return false;
        }

        self::put(self::CHAVE_TEMPO, time());

        return true;
    }

    public static function marcarAtividade(): void
    {
        self::put(self::CHAVE_TEMPO, time());
    }

    public static function setFlash(string $mensagem): void
    {
        $_SESSION[self::CHAVE_FLASH] = $mensagem;
    }

    public static function getFlash(): ?string
    {
        $mensagem = $_SESSION[self::CHAVE_FLASH] ?? null;
        unset($_SESSION[self::CHAVE_FLASH]);

        return is_string($mensagem) ? $mensagem : null;
    }

    /**
     * Guarda o login digitado para devolver no formulário.
     *
     * Sem isso o campo volta vazio e o usuário não tem como perceber que o
     * navegador preencheu outra coisa (autofill) — ele só vê a senha sendo
     * recusada sem entender por quê.
     */
    public static function guardarLoginTentativa(string $login): void
    {
        $_SESSION[self::CHAVE_LOGIN_TENTATIVA] = $login;
    }

    /** Lê e apaga: o campo só é repovoado uma vez. */
    public static function pegarLoginTentativa(): string
    {
        $login = $_SESSION[self::CHAVE_LOGIN_TENTATIVA] ?? '';
        unset($_SESSION[self::CHAVE_LOGIN_TENTATIVA]);

        return is_string($login) ? $login : '';
    }
}
