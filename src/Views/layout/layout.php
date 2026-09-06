<?php
// a implementar (visual pelo front) — estrutura mínima:
//   $content  → HTML da view da página
//   $navAtivo → item ativo do menu ('', 'home', 'clientes', 'pets', 'agendamentos')
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amora Banho e Tosa</title>
</head>
<body>

<nav>
    <a href="/" <?= $navAtivo === 'home' ? 'class="ativo"' : '' ?>>Agenda de hoje</a> |
    <a href="/agendamentos" <?= $navAtivo === 'agendamentos' ? 'class="ativo"' : '' ?>>Agendamentos</a> |
    <a href="/pets" <?= $navAtivo === 'pets' ? 'class="ativo"' : '' ?>>Pets</a> |
    <a href="/clientes" <?= $navAtivo === 'clientes' ? 'class="ativo"' : '' ?>>Clientes</a>
</nav>

<main>
    <?= $content ?>
</main>

</body>
</html>

