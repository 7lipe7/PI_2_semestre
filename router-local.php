<?php

declare(strict_types=1);

/**
 * Roteador para o servidor embutido do PHP (`php -S`).
 *
 * Uso (modo local sem Apache):
 *   php -S localhost:8000 router-local.php
 *
 * - Arquivos estáticos (css/js/img) são servidos direto;
 * - todo o resto cai no front controller public/index.php.
 */

$public = __DIR__ . '/public';
$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';

$arquivo = $public . $uri;
if ($uri !== '/' && is_file($arquivo)) {
    return false;
}

require $public . '/index.php';
