<?php

/**
 * Conexão com o banco de dados MySQL via PDO.
 *
 * As credenciais são lidas de variáveis de ambiente
 * definidas no arquivo .env (carregado pelo docker-compose).
 */

function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    $host     = getenv('DB_HOST')     ?: 'db';
    $port     = getenv('DB_PORT')     ?: '3306';
    $dbname   = getenv('DB_NAME')     ?: 'petshop';
    $user     = getenv('DB_USER')     ?: 'petshop_user';
    $password = getenv('DB_PASSWORD') ?: '';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]);
    } catch (PDOException $e) {
        http_response_code(500);

        header('Content-Type: application/json; charset=utf-8');

        die(json_encode([
            'error'   => 'Falha na conexão com o banco de dados',
              
        ], JSON_UNESCAPED_UNICODE));
    }

    return $pdo;
}
