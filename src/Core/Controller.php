<?php
declare(strict_types=1);

namespace Core;

function e(mixed $value): string{
 return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

abstract class Controller{

   protected function render(string $view, array $data = []): void{
      $data['navAtivo'] ??='';

      extract($data);

      ob_start();
      require __DIR__ . '/../Views/' . $view . '.php';
      $content = (string) ob_get_clean();

      require __DIR__ . '/../Views/layout/layout.php';
   }
      protected function redirect(string $to): void{
        header('Location: ' . $to);
        exit;
    }
}
