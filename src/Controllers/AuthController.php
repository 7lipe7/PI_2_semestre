<?php

declare(strict_types=1);

namespace Controllers;

use core\Controller;
use Core\Csrf;
use Core\Session;
use Usuario;
use TentativaLogin;


class AuthController extends Controller
{


    /** Tentativas malsucedidas antes do bloqueio (por login+IP). */
    private const MAX_TENTATIVAS = 5;

    /** Janela, em minutos, na qual as tentativas são contadas. */
    private const JANELA_BLOQUEIO_MIN = 15;

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

        // Bloqueio de força bruta: conta tentativas malsucedidas recentes
        // do mesmo login vindas do mesmo IP, dentro da janela.
        $ip   = (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
        $tentativas = TentativaLogin::contar($login, $ip, self::JANELA_BLOQUEIO_MIN);

        if ($tentativas >= self::MAX_TENTATIVAS) {
            // Não dizemos quantas faltam nem distinguimos login existente de
            // inexistente — a mesma mensagem para qualquer motivo de recusa.
            Session::setFlash('Muitas tentativas. Aguarde alguns minutos e tente de novo.');

            $this->redirect('/login');
        }

        $usuario = Usuario::findByLogin($login);


        if ($usuario === null) {
            // Sem usuário não há hash de verificação — o fluxo abaixo é
            // idêntico para login legítimo e para quem errou: registra a
            // tentativa (bloqueio de força bruta) e informa genericamente
            // "inválido".
            TentativaLogin::registrar($login, $ip);

            Session::setFlash('Usuário ou senha inválidos.');

            $this->redirect('/login');
        }

        if (!Usuario::verificarSenha($usuario, $senha)) {
            TentativaLogin::registrar($login, $ip);

            Session::setFlash('Usuário ou senha inválidos.');

            $this->redirect('/login');
        }

        // Login confirmado: o histórico de erros é zerado, para que o próximo
        // erro do usuário legítimo comece do zero em vez de herdar as
        // tentativas de um ataque anterior.
        TentativaLogin::limpar($login, $ip);

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
