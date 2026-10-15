<?php

declare(strict_types=1);

namespace Core;

/**
 * Nonce da Content-Security-Policy (CSP).
 *
 * Substitui o 'unsafe-inline' do script-src: todo <script> inline precisa
 * carregar nonce="..." para ser executado. O valor é sorteado a cada
 * requisição, então um script injetado por XSS não consegue adivinhar o
 * nonce para escapar da política.
 */
final class Csp
{
    private static ?string $nonce = null;

    /** Nonce desta requisição (hex — seguro dentro de atributo HTML). */
    public static function nonce(): string
    {
        if (self::$nonce === null) {
            self::$nonce = bin2hex(random_bytes(16));
        }

        return self::$nonce;
    }
}