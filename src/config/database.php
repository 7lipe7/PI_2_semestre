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
    // tem precedência sobre o arquivo. O Compose também injeta as variáveis
    // do env_file, então no container este bloco quase nunca faz nada.
    $candidatos = [
        __DIR__ . '/../../.env',
        dirname(__DIR__, 2) . '/.env',
    ];

    $arquivo = null;
    foreach ($candidatos as $c) {
        if (is_file($c) && is_readable($c)) {
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
        // Remove BOM invisível que quebra a primeira chave do arquivo.
        $linha = ltrim($linha, "\xEF\xBB\xBF \t");
        $linha = rtrim($linha, "\r\n");

        if ($linha === '' || str_starts_with($linha, '#')) {
            continue;
        }

        if (!str_contains($linha, '=')) {
            continue;
        }

        [$chave, $valor] = explode('=', $linha, 2);
        // Chave com espaço ("DATABASE_URL =" ou "export DATABASE_URL=")
        // nunca bateria com getenv('DATABASE_URL').
        $chave = trim($chave);
        if (str_starts_with($chave, 'export ')) {
            $chave = trim(substr($chave, 7));
        }
        $valor = trim($valor);

        if ($chave === '' || !preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $chave)) {
            continue;
        }

        // Remove aspas: DATABASE_URL="..." vira string vazia de verdade.
        // Sem isso, o valor literal `"` cai no parse_url e quebra a conexão.
        if (strlen($valor) >= 2) {
            $primeiro = $valor[0];
            $ultimo = $valor[strlen($valor) - 1];
            if (($primeiro === '"' && $ultimo === '"') || ($primeiro === "'" && $ultimo === "'")) {
                $valor = substr($valor, 1, -1);
            }
        }
        $valor = trim($valor, "\"'");

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
        // Fora do Docker (php -S no host), "db" só existe dentro da rede do
        // Compose. Aponta para o MySQL publicado no host (porta 3308).
        $host = getenv('DB_HOST') ?: 'db';
        if ($host === 'db' && PHP_SAPI === 'cli-server') {
            $host = '127.0.0.1';
        }
        $port     = getenv('DB_PORT')     ?: '3306';
        if ($host === '127.0.0.1' && ($port === '3306' || $port === '')) {
            // 3306 costuma estar ocupada por outro MySQL do host; a nossa é a 3308.
            $port = '3308';
        }
        $dbname   = getenv('DB_NAME')     ?: 'petshop';
        $user     = getenv('DB_USER')     ?: 'petshop_user';
        $password = getenv('DB_PASSWORD') ?: '';
    }

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";

    if (!extension_loaded('pdo_mysql')) {
        // PHP do host (Fedora) vem sem pdo_mysql: o caminho suportado é o Docker.
        error_log('Extensão pdo_mysql ausente no PHP local. Rode via Docker: docker compose up -d');

        if (PHP_SAPI === 'cli') {
            throw new RuntimeException(
                'PHP sem pdo_mysql. Rode via Docker (docker compose up -d) ou instale php-mysqlnd.'
            );
        }

        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        die(json_encode([
            'error' => 'PHP sem pdo_mysql. Rode via Docker (docker compose up -d).',
        ], JSON_UNESCAPED_UNICODE));
    }

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
