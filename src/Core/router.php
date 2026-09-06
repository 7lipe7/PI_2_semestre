<?php


declare(strict_types=1);

namespace Core;

use Closure;

class Router
{

  private array $routes = [];

  public function get(string $path, Closure|string $handler): void
  {
    $this->add('GET', $path, $handler);
  }

  public function post(string $path, Closure|string $handler): void
  {
    $this->add('POST', $path, $handler);
  }

  private function add(string $method, string $path, Closure|string $handler): void
  {
    $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';

    $this->routes[$method][] = [$pattern, $handler];
  }

  public function dispatch(string $method, string $uri): void
  {
    $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/');
    if ($path === '') {
      $path = '/';
    }

    foreach ($this->routes[$method] ?? [] as [$pattern, $handler]) {
      if (preg_match($pattern, $path, $matches)) {
        // Mantém apenas os parâmetros nomeados ({id}, etc.)
        $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

        // Rota registrada como closure: executa e imprime o retorno
        if ($handler instanceof Closure) {
          $result = $handler($params);

          if ($result !== null) {
            echo $result;
          }

          return;
        }

        // Rota registrada como 'Controller@acao' (definida pelo desenvolvedor)
        [$class, $action] = explode('@', $handler);
        $fullClass = "Controllers\\{$class}";

        // Hardening: só instancia classes que realmente existem
        if (!class_exists($fullClass)) {
          http_response_code(500);
          echo "Erro de configuração de rota: classe '{$fullClass}' não encontrada.";
          return;
        }

        // method_exists (e não is_callable): is_callable exige método estático no PHP 8,
        // e nossas actions são métodos de instância
        if (!method_exists($fullClass, $action)) {
          http_response_code(500);
          echo "Erro de configuração de rota: action '{$action}' não encontrada em '{$fullClass}'.";
          return;
        }

        if (!str_contains($handler, '@')) {
          http_response_code(500);
          echo "Erro de configuração de rota: formato nao esperado'{$handler}'.";
          return;
        }
        [$class, $action] = explode('@', $handler);


        (new $fullClass())->$action($params);

        return;
      }
    }

    http_response_code(404);
    echo '<h1>404 — Página não encontrada</h1><p><a href="/">Voltar para a agenda</a></p>';
  }
}
