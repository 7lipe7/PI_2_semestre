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

    <!-- Bootstrap (SRI: garante que o arquivo do CDN chegou intacto) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous"
          integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB">

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
            <i data-lucide="user-round"></i>
        </div>

        <h1 class="login-title">Amora Banho e Tosa</h1>
        <p class="login-subtitle">Painel administrativo · Gestão do Banho e Tosa</p>

        <form method="post" action="/login" class="login-form">
            <?= \Core\Csrf::campo() ?>

            <div class="login-campo">
                <label for="login">Usuário</label>
                <input type="text" id="login" name="login" class="form-control" 
                       maxlength="60"  value="<?= \Core\e($usuario ?? '') ?>"
                       autocomplete="username"  placeholder="Digite seu usuário" required autofocus>
            </div>

            <div class="login-campo">
                <label for="senha">Senha</label>
                <div class="login-senha">
                    <input type="password" id="senha" name="senha" class="form-control"
                           maxlength="60" autocomplete="current-password" placeholder="Digite sua senha" required>
                    <button type="button" class="login-senha__toggle" id="loginSenhaToggle"
                            aria-label="Mostrar senha" aria-pressed="false">
                        <i data-lucide="eye" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <i data-lucide="log-in" aria-hidden="true"></i>
                <span>Entrar</span>
            </button>
        </form>

    </main>

    <?php if (($erro ?? null) !== null): ?>
        <div class="login-erro" id="loginErro" role="alert" aria-atomic="true">
            <i data-lucide="circle-alert" aria-hidden="true"></i>
            <span><?= \Core\e($erro) ?></span>
        </div>
    <?php endif; ?>

    <footer class="login-footer">
        &copy; 2026 Amora Banho e Tosa &middot; Todos os direitos reservados
    </footer>

    <!-- Lucide Icons (SRI) -->
    <script src="https://unpkg.com/lucide@1.48.0/dist/umd/lucide.min.js" crossorigin="anonymous"
            integrity="sha384-Hh7C333mXel+qppGoFs4qAOXp7h67eur4XsQVF2bvHAM3MQ4DX4cQI7oRCzb98J4"></script>
    <script nonce="<?= \Core\Csp::nonce() ?>">
        lucide.createIcons();

        const passwordInput = document.getElementById('senha');
        const passwordToggle = document.getElementById('loginSenhaToggle');
        if (passwordInput && passwordToggle) {
            passwordToggle.addEventListener('click', () => {
                const mostrarSenha = passwordInput.type === 'password';
                passwordInput.type = mostrarSenha ? 'text' : 'password';
                passwordToggle.setAttribute('aria-label', mostrarSenha ? 'Ocultar senha' : 'Mostrar senha');
                passwordToggle.setAttribute('aria-pressed', String(mostrarSenha));
                passwordToggle.innerHTML = `<i data-lucide="${mostrarSenha ? 'eye-off' : 'eye'}" aria-hidden="true"></i>`;
                lucide.createIcons();
            });
        }

        const loginError = document.getElementById('loginErro');
        if (loginError) {
            window.setTimeout(() => {
                loginError.classList.add('login-erro--saindo');
                window.setTimeout(() => loginError.remove(), 180);
            }, 4500);
        }
    </script>

</body>

</html>
