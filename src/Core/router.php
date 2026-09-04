<?php


declare(strict_types=1);

namespace core;


class router
{

  private array $routes = [];

  public function get(string $path, string $handler): void
  {
    $this->add('GET', $path, $handler);
  }

  public function post(string $path, string $handler): void
  {
    $this->add('POST', $path, $handler);
  }

  private function add(string $method, string $path, string $handler): void
  {
    $pattern = '#^' . preg_replace('#\{(\w+)\}#', '(?P<$1>[^/]+)', $path) . '$#';

    $this->routes[$method][] = [$pattern, $handler];
  }
  public function dispatch(string $method , string $uri):void{
    $path = rtrim(parse_url($uri, PHP_URL_PATH) ?: '/', '/');
        if ($path === '') {
            $path = '/';
        }

         foreach ($this->routes[$method] ?? [] as [$pattern, $handler]) {
            if (preg_match($pattern, $path, $matches)) {
                // Mantém apenas os parâmetros nomeados ({id}, etc.)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                [$class, $action] = explode('@', $handler);
                $fullClass = "Controllers\\{$class}";

                (new $fullClass())->$action($params);

                return;
            }
             }

        http_response_code(404);
        echo '<h1>404 — Página não encontrada</h1><p><a href="/">Voltar para a agenda</a></p>';
  

  }
}
