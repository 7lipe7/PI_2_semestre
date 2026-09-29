<?php

declare(strict_types=1);

/**
 * Cria (ou redefine a senha de) um usuário do painel.
 *
 * Rode de dentro do projeto:
 *   docker compose exec php php /var/www/src/bin/criar-usuario.php
 *
 * Ou informando os dados:
 *   docker compose exec php php /var/www/src/bin/criar-usuario.php "Maria" maria "SenhaForte@2026"
 *
 * Sem argumentos, usa os valores padrão de apresentação.
 */

// Script de uso interno: nunca deve responder requisição web.
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

require_once __DIR__ . '/../config/database.php';

$nome  = $argv[1] ?? 'Administrador';
$login = $argv[2] ?? 'admin';
$senha = $argv[3] ?? 'Petshop@2026';

$pdo = getConnection();

// Nunca guardamos a senha: só o hash bcrypt.
$hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $pdo->prepare('SELECT id FROM usuarios WHERE login = :login LIMIT 1');
$stmt->execute([':login' => $login]);
$id = $stmt->fetchColumn();

if ($id === false) {
    $stmt = $pdo->prepare(
        'INSERT INTO usuarios (nome, login, senha_hash)
         VALUES (:nome, :login, :hash)'
    );
    $stmt->execute([':nome' => $nome, ':login' => $login, ':hash' => $hash]);

    $id   = (int) $pdo->lastInsertId();
    $acao = 'criado';
} else {
    $id = (int) $id;

    $stmt = $pdo->prepare(
        'UPDATE usuarios SET nome = :nome, senha_hash = :hash WHERE id = :id'
    );
    $stmt->execute([':nome' => $nome, ':hash' => $hash, ':id' => $id]);

    $acao = 'atualizado';
}

printf("Usuário %s: #%d\n", $acao, $id);
printf("  nome : %s\n", $nome);
printf("  login: %s\n", $login);
printf("  senha: %s\n", $senha);

// Confere lendo de volta do banco, do mesmo jeito que o login faz.
$stmt = $pdo->prepare('SELECT senha_hash FROM usuarios WHERE id = :id');
$stmt->execute([':id' => $id]);
$gravado = (string) $stmt->fetchColumn();

printf("\npassword_verify() contra o banco: %s\n", password_verify($senha, $gravado) ? 'OK' : 'FALHOU');
