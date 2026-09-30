<?php

declare(strict_types=1);

/**
 * Remove os registros antigos da tabela login_tentativas.
 *
 * A tabela de tentativas de login cresce a cada erro de senha e, sozinha,
 * nunca encolheria. Este script é a limpeza programada.
 *
 * Chamado automaticamente 1x por dia pelo serviço "limpeza"
 * (ver docker-compose.yml). Para rodar na mão:
 *   docker compose exec php php /var/www/src/bin/limpar-tentativas.php
 *
 * Ou informando quantos dias manter (padrão 30):
 *   docker compose exec php php /var/www/src/bin/limpar-tentativas.php 7
 *
 * Apagar registros antigos NÃO destrava um login bloqueado agora: a janela
 * de bloqueio do AuthController é de 15 minutos e conta apenas as tentativas
 * dentro dela. Este script só evita que o histórico cresça sem parar.
 */

// Script de uso interno: nunca deve responder requisição web.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/TentativaLogin.php';

// Quantos dias de histórico manter. A janela de bloqueio é de 15 minutos,
// então 7 dias já é folgado; 30 dias só serve para auditoria.
$dias = isset($argv[1]) ? (int) $argv[1] : 7;

if ($dias < 1) {
    printf("ERRO: informe um número de dias maior que zero (recebido: %d)\n", $dias);
    exit(1);
}

$removidos = TentativaLogin::limparAntigos($dias);

printf(
    "[%s] Tentativas com mais de %d dia(s) removidas: %d\n",
    date('Y-m-d H:i:s'),
    $dias,
    $removidos
);
