<?php

declare(strict_types=1);

namespace Controllers;

use core\Controller;
use Core\Csrf;
use Core\Session;
use Usuario;

class AuthController extends Controller
{
    public function form(array $params = []): void
    {
        $this->renderLimpo('auth/login', [
            'erro'    => Session::getFlash(),
            'usuario' => Session::pegarLoginTentativa(),
            'navAtivo' => '',
        ]);
    }

    public function login(array $params = []): void
    {
        Csrf::exigir();

        $login = trim((string) ($_POST['login'] ?? ''));
        $senha = (string) ($_POST['senha'] ?? '');

        // Devolve o que foi digitado ao formulário: sem isso o campo volta
        // vazio e o usuário não percebe que o navegador (autofill) mandou
        // outro valor — foi exatamente o que causou o "não consigo logar".
        Session::guardarLoginTentativa($login);

        if ($login === '' || $senha === '') {
            Session::setFlash('Informe usuário e senha.');

            $this->redirect('/login');
        }

        $usuario = Usuario::findByLogin($login);

        if ($usuario === null) {
            // Even sem usuário, gastamos o mesmo tempo: senão o tempo de
            // resposta revelaria quais logins existem (timing attack).
            password_verify($senha, '$2y$12$JkbJ9GoTp/nQLPMRMbbpbOWM1Pakx9KfiVM6cJHcuoyNllkZks/E6');

            Session::setFlash('Usuário ou senha inválidos.');

            $this->redirect('/login');
        }

        if (!Usuario::verificarSenha($usuario, $senha)) {
            Session::setFlash('Usuário ou senha inválidos.');

            $this->redirect('/login');
        }

        // Login confirmado: descarta a tentativa guardada e troca o id
        Session::pegarLoginTentativa();
        Session::regenerar();
        Session::put('usuario_id', (int) $usuario['id']);
        Session::put('usuario_nome', (string) $usuario['nome']);
        Session::marcarAtividade();

        $this->redirect('/');
    }

    public function logout(array $params = []): void
    {
        Csrf::exigir();

        Session::destruir();

        $this->redirect('/login');
    }

    /** Tela sem a sidebar do layout principal. */
    private function renderLimpo(string $view, array $data = []): void
    {
        $data['navAtivo'] ??= '';

        extract($data);

        require __DIR__ . '/../Views/' . $view . '.php';
    }
}
