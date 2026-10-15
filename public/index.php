<?php

declare(strict_types=1);

// Nonce da CSP — precisa existir antes do cabeçalho abaixo e ser o mesmo
// usado por todas as views (<script nonce="..."> via \Core\Csp::nonce()).
require_once __DIR__ . '/../src/Core/Csp.php';

// Segurança: cabeçalhos de resposta aplicados a TODAS as requisições.
// Esse bloco vai antes de qualquer saída (HTML, erro de database, 404/500 do
// Router), garantindo headers em todas as páginas, inclusive a tela /login.
$secure = !empty($_SERVER['HTTPS']) && strtolower((string) $_SERVER['HTTPS']) !== 'off';
if (!$secure) {
    $secure = strtolower(trim((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))) === 'https';
}
$maxAgeHsts = 'max-age=31536000';
header('Strict-Transport-Security: ' . ($secure ? $maxAgeHsts . '; includeSubDomains; preload' : $maxAgeHsts));
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-Permitted-Cross-Domain-Policies: none');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Cross-Origin-Resource-Policy: same-origin');
header('Permissions-Policy: geolocation=(), camera=(), microphone=()');
header('Cross-Origin-Opener-Policy: same-origin');
header('Cross-Origin-Embedder-Policy: require-corp');
// 'unsafe-inline' foi removido do script-src: o nonce por requisição assume o
// papel (findings do Nuclei: weak-csp-detect:unsafe-script-src). style-src
// mantém 'unsafe-inline' porque as views usam atributos style="..." — e o
// template do Nuclei não sinaliza style-src.
$cspNonce = \Core\Csp::nonce();
header('Content-Security-Policy: '
    . "default-src 'self'; "
    . "script-src 'self' 'nonce-{$cspNonce}' https://cdn.jsdelivr.net https://unpkg.com; "
    . "style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://unpkg.com; "
    . "img-src 'self' data: https:; "
    . "font-src 'self' https://cdn.jsdelivr.net https://unpkg.com; "
    // connect-src inclui os CDNs porque o DevTools busca os source maps (.map)
    // de bootstrap/lucide quando aberto — esse download é regido por connect-src.
    . "connect-src 'self' https://cdn.jsdelivr.net https://unpkg.com; "
    . "object-src 'none'; "
    . "frame-ancestors 'self'; "
    . "base-uri 'self'; "
    . "form-action 'self';");

require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/Core/Router.php';
require_once __DIR__ . '/../src/Core/Controller.php';
require_once __DIR__ . '/../src/Models/Agendamento.php';
require_once __DIR__ . '/../src/Models/Cachorro.php';
require_once __DIR__ . '/../src/Models/Proprietario.php';

// autenticacao
require_once __DIR__ . '/../src/Core/Session.php';
require_once __DIR__ . '/../src/Core/Csrf.php';
require_once __DIR__ . '/../src/Core/Auth.php';
require_once __DIR__ . '/../src/Models/Usuario.php';
require_once __DIR__ . '/../src/Controllers/AuthController.php';

// protecao brute force
require_once __DIR__ . '/../src/Models/TentativaLogin.php';



// A sessão TEM de começar aqui: é o primeiro ponto do sistema com as classes
// carregadas e ainda sem nenhuma saída enviada. Além disso o gate de login
// abaixo lê $_SESSION — sem session_start() ele veria uma sessão vazia e
// destruiria algo que nunca existiu (warning + headers já enviados).
\Core\Session::start();

require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/ProprietarioController.php';
require_once __DIR__ . '/../src/Controllers/CachorroController.php';
require_once __DIR__ . '/../src/Controllers/AgendamentoController.php';
require_once __DIR__ . '/../src/Models/Servico.php';
require_once __DIR__ . '/../src/Controllers/ServicoController.php';

use Core\Router;


$router = new Router();

// home
$router->get('/', 'HomeController@index');

// clientes
$router->get('/clientes', 'ProprietarioController@index');
$router->get('/clientes/novo', 'ProprietarioController@create');
$router->post('/clientes', 'ProprietarioController@store');
$router->get('/clientes/{id}/editar', 'ProprietarioController@edit');
$router->post('/clientes/{id}', 'ProprietarioController@update');
$router->post('/clientes/{id}/excluir', 'ProprietarioController@destroy');

// pets
$router->get('/pets', 'CachorroController@index');
$router->get('/pets/novo', 'CachorroController@create');
$router->post('/pets', 'CachorroController@store');
$router->get('/pets/{id}/editar', 'CachorroController@edit');
$router->post('/pets/{id}', 'CachorroController@update');
$router->post('/pets/{id}/excluir', 'CachorroController@destroy');

// agendamentos
$router->get('/agendamentos', 'AgendamentoController@index');
$router->get('/agendamentos/historico', 'AgendamentoController@historico');
$router->get('/agendamentos/novo', 'AgendamentoController@create');
$router->post('/agendamentos', 'AgendamentoController@store');
$router->get('/agendamentos/{id}/editar', 'AgendamentoController@edit');
$router->post('/agendamentos/{id}', 'AgendamentoController@update');
$router->post('/agendamentos/{id}/excluir', 'AgendamentoController@destroy');
$router->post('/agendamentos/{id}/status', 'AgendamentoController@updateStatus');


// servicos
$router->get('/servicos', 'ServicoController@index');
$router->get('/servicos/novo', 'ServicoController@create');
$router->post('/servicos', 'ServicoController@store');
$router->get('/servicos/{id}/editar', 'ServicoController@edit');
$router->post('/servicos/{id}', 'ServicoController@update');
$router->post('/servicos/{id}/excluir', 'ServicoController@destroy');



// ---- Login (rotas livres) ----
$router->get('/login',  'AuthController@form');
$router->post('/login', 'AuthController@login');
$router->post('/logout', 'AuthController@logout');

// ---- Proteção: tudo abaixo do login exige sessão ----
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$metodo = $_SERVER['REQUEST_METHOD'];

// Rota livre: só o login. Qualquer outra rota — inclusive o logout — exige
// sessão ativa, senão um POST anônimo poderia alcançar o AuthController.
$rotasLivres = ['/login'];

if (!in_array($path, $rotasLivres, true)) {
    \Core\Auth::exigirLogin();
}

// ---- CSRF: todo POST exige token válido ----
// O gate é global de propósito. Exigir o token em cada action do controller
// é fácil de esquecer — um controller novo sem a chamada ficaria aberto.
// Um único ponto de verificação não depende de disciplina do programador.
//
// A única exceção é o POST /login: é a única requisição que o usuário faz
// sem sessão, então ainda não existe token para conferir.
if ($metodo === 'POST' && $path !== '/login') {
    \Core\Csrf::exigir();
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
