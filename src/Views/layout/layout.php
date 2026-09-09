<?php
/**
 * Layout principal (MVC).
 *
 *   $content  → HTML da view da página
 *   $navAtivo → item ativo do menu ('home', 'agendamentos', 'pets', 'clientes')
 */

$navAtivo ??= '';

$paginas = [
    'home'         => ['Dashboard', 'Visão geral do pet shop'],
    'agendamentos' => ['Agendamentos', 'Banhos e tosas marcados'],
    'pets'         => ['Pets', 'Cães cadastrados'],
    'clientes'     => ['Clientes', 'Proprietários cadastrados'],
];

[$titulo, $subtitulo] = $paginas[$navAtivo] ?? $paginas['home'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= \Core\e($titulo) ?> — Amora Banho e Tosa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS do projeto -->
    <link rel="stylesheet" href="/assets/css/index.css">
</head>
<body class="admin-body">

<aside class="sidebar d-flex flex-column min-vh-100 position-fixed top-0 start-0 p-3" aria-label="Navegação principal">

    <div class="sidebar-header d-flex align-items-center gap-3">
        <div class="sidebar-logo" aria-hidden="true">
            <i data-lucide="paw-print"></i>
        </div>

        <div class="sidebar-title">
            <h2>PetShop Admin</h2>
            <p>Painel de controle</p>
        </div>
    </div>

    <nav class="sidebar-nav d-flex flex-column gap-1">

        <a href="/" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'home' ? ' active' : '' ?>"<?= $navAtivo === 'home' ? ' aria-current="page"' : '' ?>>
            <i class="nav-icon" data-lucide="layout-dashboard" aria-hidden="true"></i>
            <span>Dashboard</span>
        </a>

        <a href="/agendamentos" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'agendamentos' ? ' active' : '' ?>"<?= $navAtivo === 'agendamentos' ? ' aria-current="page"' : '' ?>>
            <i class="nav-icon" data-lucide="calendar-days" aria-hidden="true"></i>
            <span>Agendamentos</span>
        </a>

        <a href="/pets" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'pets' ? ' active' : '' ?>"<?= $navAtivo === 'pets' ? ' aria-current="page"' : '' ?>>
            <i class="nav-icon" data-lucide="dog" aria-hidden="true"></i>
            <span>Pets</span>
        </a>

        <a href="/clientes" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'clientes' ? ' active' : '' ?>"<?= $navAtivo === 'clientes' ? ' aria-current="page"' : '' ?>>
            <i class="nav-icon" data-lucide="users-round" aria-hidden="true"></i>
            <span>Clientes</span>
        </a>

    </nav>

    <div class="sidebar-footer d-flex gap-2 mt-auto">
        <span>v1.0</span>
        <span aria-hidden="true">•</span>
        <span>PetShop</span>
    </div>

</aside>

<header class="page-header d-flex align-items-center">
    <div class="page-header__text container-fluid px-0">
        <h1 class="page-header__title"><?= \Core\e($titulo) ?></h1>
        <p class="page-header__subtitle"><?= \Core\e($subtitulo) ?></p>
    </div>
</header>

<main>
    <?= $content ?>
</main>

<!-- Bootstrap JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- Lucide Icons -->
<script src="https://unpkg.com/lucide@latest"></script>
<script>
    lucide.createIcons();
</script>

</body>
</html>

