<?php

declare(strict_types=1);

require_once __DIR__ . '/../src/config/database.php';
require_once __DIR__ . '/../src/Core/router.php';
require_once __DIR__ . '/../src/Core/Controller.php';
require_once __DIR__ . '/../src/Models/Agendamento.php';
require_once __DIR__ . '/../src/Models/Cachorro.php';
require_once __DIR__ . '/../src/Models/Proprietario.php';
require_once __DIR__ . '/../src/Controllers/HomeController.php';
require_once __DIR__ . '/../src/Controllers/ProprietarioController.php';
require_once __DIR__ . '/../src/Controllers/CachorroController.php';
require_once __DIR__ . '/../src/Controllers/AgendamentoController.php';


use core\router;


$router = new router();

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
$router->get('/agendamentos/novo', 'AgendamentoController@create');
$router->post('/agendamentos', 'AgendamentoController@store');
$router->get('/agendamentos/{id}/editar', 'AgendamentoController@edit');
$router->post('/agendamentos/{id}', 'AgendamentoController@update');
$router->post('/agendamentos/{id}/excluir', 'AgendamentoController@destroy');



$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
