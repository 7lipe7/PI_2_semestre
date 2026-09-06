<?php
// a implementar
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amora Banho e Tosa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
          crossorigin="anonymous">
</head>
<body>

<nav class="navbar navbar-expand-md navbar-dark bg-danger">
    <div class="container">
        <a class="navbar-brand" href="/">Amora Banho e Tosa</a>

        <!-- hamburger: abre o menu no celular -->
        <button class="navbar-toggler" type="button"
                data-bs-toggle="collapse" data-bs-target="#menuPrincipal"
                aria-controls="menuPrincipal" aria-expanded="false" aria-label="Abrir menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="menuPrincipal">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $navAtivo === 'home' ? 'active' : '' ?>" href="/">Agenda de hoje</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $navAtivo === 'agendamentos' ? 'active' : '' ?>" href="/agendamentos">Agendamentos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $navAtivo === 'pets' ? 'active' : '' ?>" href="/pets">Pets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $navAtivo === 'clientes' ? 'active' : '' ?>" href="/clientes">Clientes</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<main class="container py-4">
    <?= $content ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous" defer></script>
</body>
</html>

