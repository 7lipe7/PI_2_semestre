<?php


declare(strict_types=1);

namespace core;


class router
{

    private array $routes = [];

    public function get(string $path, string $handler): void{
       $this->add('GET', $path, $handler);
    }

    public function post(string $path,string $handler):void{
      $this->add('POST', $path, $handler);
    }

    private function add(string $method , string $path, string $handler):void{

    
    }


}
