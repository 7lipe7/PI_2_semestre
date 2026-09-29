<?php

declare(strict_types=1);

// Define o fuso antes que qualquer controller ou view utilize date().
// O PHP dos containers pode iniciar em UTC por padrão.
date_default_timezone_set(getenv('APP_TIMEZONE') ?: 'America/Sao_Paulo');

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

if ($path !== '/login') {
    \Core\Auth::exigirLogin();
}

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

