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
    'servicos'     => ['Serviços', 'Catálogo e valores']

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">

    <!-- CSS do projeto (?v= força o navegador a buscar a versão nova) -->
    <?php
    $cssPath = ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/assets/css/index.css';
    $cssVersion = is_file($cssPath) ? filemtime($cssPath) : '1';
    ?>
    <link rel="stylesheet" href="/assets/css/index.css?v=<?= $cssVersion ?>">
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


            <a href="/" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'home' ? ' active' : '' ?>" <?= $navAtivo === 'home' ? ' aria-current="page"' : '' ?>>
                <i class="nav-icon" data-lucide="layout-dashboard" aria-hidden="true"></i>
                <span>Dashboard</span>
            </a>

            <a href="/agendamentos" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'agendamentos' ? ' active' : '' ?>" <?= $navAtivo === 'agendamentos' ? ' aria-current="page"' : '' ?>>
                <i class="nav-icon" data-lucide="calendar-days" aria-hidden="true"></i>
                <span>Agendamentos</span>
            </a>

            <a href="/pets" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'pets' ? ' active' : '' ?>" <?= $navAtivo === 'pets' ? ' aria-current="page"' : '' ?>>
                <i class="nav-icon" data-lucide="dog" aria-hidden="true"></i>
                <span>Pets</span>
            </a>
            <a href="/servicos" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'servicos' ? ' active' : '' ?>" <?= $navAtivo === 'servicos' ? ' aria-current="page"' : '' ?>>
                <i class="nav-icon" data-lucide="scissors" aria-hidden="true"></i>
                <span>Serviços</span>
            </a>

            <a href="/clientes" class="nav-item d-flex align-items-center gap-3 text-decoration-none<?= $navAtivo === 'clientes' ? ' active' : '' ?>" <?= $navAtivo === 'clientes' ? ' aria-current="page"' : '' ?>>
                <i class="nav-icon" data-lucide="users-round" aria-hidden="true"></i>
                <span>Clientes</span>
            </a>

            <form method="post" action="/logout">
                <?= \Core\Csrf::campo() ?>
                <button type="submit" class="nav-item d-flex align-items-center gap-3 text-decoration-none w-100 border-0 bg-transparent">
                    <i class="nav-icon" data-lucide="log-out" aria-hidden="true"></i>
                    <span>Sair</span>
                </button>
            </form>



        </nav>

        <div class="sidebar-footer d-flex gap-2 mt-auto">
            <span>v1.0</span>
            <span aria-hidden="true">•</span>
            <span>PetShop</span>
        </div>

    </aside>

    <header class="page-header d-flex align-items-center">
        <button type="button" class="sidebar-toggle" aria-label="Abrir menu" aria-expanded="false">
            <i data-lucide="menu"></i>
        </button>

        <div class="page-header__text container-fluid px-0">
            <h1 class="page-header__title"><?= \Core\e($titulo) ?></h1>
            <p class="page-header__subtitle"><?= \Core\e($subtitulo) ?></p>
        </div>

        <?php if ($navAtivo === 'agendamentos' || $navAtivo === 'clientes' || $navAtivo === 'pets' || $navAtivo === 'servicos'): ?>
            <div class="page-header__actions">
                <?php if ($navAtivo === 'agendamentos'): ?>
                    <a href="/agendamentos/historico" class="btn clientes-new-btn page-header__action">
                        <i data-lucide="history"></i>
                        <span>Histórico</span>
                    </a>
               
                <?php elseif ($navAtivo === 'clientes'): ?>
                    <a href="/clientes/novo" class="btn clientes-new-btn page-header__action">
                        <i data-lucide="plus"></i>
                        <span>Novo cliente</span>
                    </a>
                <?php elseif ($navAtivo === 'pets'): ?>
                    <button type="button" class="btn clientes-new-btn page-header__action" id="novoPetBtn">
                        <i data-lucide="plus"></i>
                        <span>Novo pet</span>
                    </button>
                <?php elseif ($navAtivo === 'servicos'): ?>
                    <button type="button" class="btn clientes-new-btn page-header__action" data-servico-modal-open>
                        <i data-lucide="plus"></i>
                        <span>Novo serviço</span>
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </header>

    <main>
        <?= $content ?>
    </main>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@1.48.0/dist/umd/lucide.min.js" crossorigin="anonymous"></script>
    <script>
        lucide.createIcons();

        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const body = document.body;

        if (sidebar && sidebarToggle) {
            const syncSidebarState = (isOpen) => {
                body.classList.toggle('sidebar-open', isOpen);
                sidebarToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                sidebarToggle.setAttribute('aria-label', isOpen ? 'Fechar menu' : 'Abrir menu');
            };

            sidebarToggle.addEventListener('click', () => {
                const willOpen = !body.classList.contains('sidebar-open');
                syncSidebarState(willOpen);
            });

            document.querySelectorAll('.nav-item').forEach((item) => {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 991) {
                        syncSidebarState(false);
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 991) {
                    syncSidebarState(false);
                }
            });

            document.addEventListener('click', (event) => {
                if (window.innerWidth > 991) {
                    return;
                }

                const clickedInsideSidebar = sidebar.contains(event.target);
                const clickedToggle = sidebarToggle.contains(event.target);

                if (!clickedInsideSidebar && !clickedToggle && body.classList.contains('sidebar-open')) {
                    syncSidebarState(false);
                }
            });
        }
    </script>

</body>

</html>