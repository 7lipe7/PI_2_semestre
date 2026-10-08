<?php

/**
 * Tela de login.
 *
 * Renderizada por Controller::renderLimpo() — sem o layout principal, porque
 * a sidebar só faz sentido depois de autenticado.
 *
 * Variáveis vindas do AuthController:
 *   $erro    → mensagem de flash (credencial inválida, sessão expirada…)
 *   $usuario → login já digitado, para não obrigar a redigitar
 *
 * Segue o mesmo padrão do layout: HTML escapado com \Core\e() e o token
 * CSRF injetado por \Core\Csrf::campo().
 */
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar — Amora Banho e Tosa</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-b11225e24c482c5bd8e3b275e9cafd670074eef3f827cf8b1fba8a427baa92e200bcd58bcde37cb44e5806e8d9a892c1" crossorigin="anonymous">

    <!-- CSS do projeto (?v= força o navegador a buscar a versão nova) -->
    <?php
    $cssPath = ($_SERVER['DOCUMENT_ROOT'] ?? '') . '/assets/css/index.css';
    $cssVersion = is_file($cssPath) ? filemtime($cssPath) : '1';
    ?>
    <link rel="stylesheet" href="/assets/css/index.css?v=<?= $cssVersion ?>">
</head>

<body class="login-body">

    <main class="login-card">

        <div class="login-logo" aria-hidden="true">
            <i data-lucide="paw-print"></i>
        </div>

        <h1 class="login-title">PetShop Admin</h1>
        <p class="login-subtitle">Entre para gerenciar o pet shop</p>

        <?php if (($erro ?? null) !== null): ?>
            <div class="login-erro" role="alert">
                <i data-lucide="circle-alert" aria-hidden="true"></i>
                <span><?= \Core\e($erro) ?></span>
            </div>
        <?php endif; ?>

        <form method="post" action="/login" class="login-form">
            <?= \Core\Csrf::campo() ?>

            <div class="login-campo">
                <label for="login">Usuário</label>
                <input type="text" id="login" name="login" class="form-control" 
                       maxlength="60"  value="<?= \Core\e($usuario ?? '') ?>"
                       autocomplete="username" required autofocus>
            </div>

            <div class="login-campo">
                <label for="senha">Senha</label>
                <input type="password" id="senha" name="senha" class="form-control"
                maxlength="60"
                       autocomplete="current-password" required>
            </div>

            <button type="submit" class="login-btn">
                <i data-lucide="log-in" aria-hidden="true"></i>
                <span>Entrar</span>
            </button>
        </form>

    </main>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@1.48.0/dist/umd/lucide.min.js" integrity="sha384-1e1ec2df7de65de97eaa9a46a05b38a80397a7b87aedebabe17b10545d9bbc700cdcc4380d7e1c408ee8442cdbf7c278" crossorigin="anonymous"></script>
    <script>
        lucide.createIcons();
    </script>

</body>

</html>
