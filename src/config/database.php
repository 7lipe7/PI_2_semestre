<?php

/**
 * Conexão com o banco de dados MySQL via PDO.
 *
 * As credenciais são lidas de variáveis de ambiente
 * definidas no arquivo .env (carregado pelo docker-compose).
 * Para rodar com `php -S` fora do Docker, este arquivo também lê o .env
 * da raiz do projeto quando a variável ainda não existe no ambiente.
 */

function carregarEnvLocal(): void
{
    // Variável já vinda do ambiente (docker-compose, export, linha de comando)
    // tem precedência sobre o arquivo.
    $candidatos = [
        __DIR__ . '/../../.env',
        dirname(__DIR__, 2) . '/.env',
    ];

    $arquivo = null;
    foreach ($candidatos as $c) {
        if (is_file($c)) {
            $arquivo = $c;
            break;
        }
    }

    if ($arquivo === null) {
        return;
    }

    $linhas = @file($arquivo, FILE_IGNORE_NEW_LINES);
    if ($linhas === false) {
        return;
    }

    foreach ($linhas as $linha) {
        $linha = trim($linha);

        if ($linha === '' || str_starts_with($linha, '#')) {
            continue;
        }

        if (!str_contains($linha, '=')) {
            continue;
        }

        [$chave, $valor] = explode('=', $linha, 2);
        $chave = trim($chave);
        $valor = trim($valor);

        if ($chave === '') {
            continue;
        }

        // Remove aspas: DATABASE_URL="..." vira string vazia de verdade.
        if (strlen($valor) >= 2) {
            $primeiro = $valor[0];
            $ultimo = $valor[strlen($valor) - 1];
            if (($primeiro === '"' && $ultimo === '"') || ($primeiro === "'" && $ultimo === "'")) {
                $valor = substr($valor, 1, -1);
            }
        }

        if (getenv($chave) === false) {
            putenv($chave . '=' . $valor);
            $_ENV[$chave] = $valor;
            $_SERVER[$chave] = $valor;
        }
    }
}

carregarEnvLocal();

function getConnection(): PDO
{
    static $pdo = null;

    if ($pdo !== null) {
        return $pdo;
    }

    date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'America/Sao_Paulo');

    $timezone = getenv('APP_TIMEZONE') ?: 'America/Sao_Paulo';

    $databaseUrl = trim((string) (getenv('DATABASE_URL') ?: ''));
    // O .env de exemplo traz DATABASE_URL="" (com aspas). Se o valor chegar
    // com as aspas literais, vira string vazia de verdade em vez de URL.
    if (strlen($databaseUrl) >= 2) {
        $primeiro = $databaseUrl[0];
        $ultimo = $databaseUrl[strlen($databaseUrl) - 1];
        if (($primeiro === '"' && $ultimo === '"') || ($primeiro === "'" && $ultimo === "'")) {
            $databaseUrl = substr($databaseUrl, 1, -1);
        }
    }
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '{$timezone}'",
    ];

    if ($databaseUrl !== '') {
        $url = parse_url($databaseUrl);

        if (
            $url === false
            || ($url['scheme'] ?? '') !== 'mysql'
            || empty($url['host'])
            || empty($url['user'])
            || !array_key_exists('pass', $url)
            || empty($url['path'])
        ) {
            throw new InvalidArgumentException('DATABASE_URL inválida. Use uma URL mysql:// completa.');
        }

        $host = $url['host'];
        $port = $url['port'] ?? 3306;
        $dbname = rawurldecode(substr($url['path'], 1));
        $user = rawurldecode($url['user']);
        $password = rawurldecode($url['pass']);

        $query = [];
        parse_str($url['query'] ?? '', $query);
        $sslMode = strtoupper($query['ssl-mode'] ?? '');

        if ($sslMode === 'REQUIRED' || $sslMode === 'PREFERRED') {
            // REQUIRED/PREFERRED: criptografa a conexão e verifica o certificado
            // do servidor para evitar ataque do tipo Man-in-the-Middle.
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        } elseif ($sslMode !== '') {
            throw new InvalidArgumentException('DATABASE_URL usa um ssl-mode não suportado.');
        }
    } else {
        $host     = getenv('DB_HOST')     ?: 'db';
        $port     = getenv('DB_PORT')     ?: '3306';
        $dbname   = getenv('DB_NAME')     ?: 'petshop';
        $user     = getenv('DB_USER')     ?: 'petshop_user';
        $password = getenv('DB_PASSWORD') ?: '';
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $password, $options);
    } catch (PDOException $e) {
        // Detalhe técnico vai para o log do container; o usuário recebe só o aviso genérico
        error_log('Falha na conexão com o banco de dados: ' . $e->getMessage());

        http_response_code(500);

        header('Content-Type: application/json; charset=utf-8');

        die(json_encode([
            'error' => 'Falha na conexão com o banco de dados',
        ], JSON_UNESCAPED_UNICODE));
    }

    return $pdo;
}
