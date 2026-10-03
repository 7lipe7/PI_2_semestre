<?php

declare(strict_types=1);

namespace Controllers;

use PDOException;
use Core\Controller;
use Servico;

class ServicoController extends Controller
{
    public function index(array $params = []): void
    {
        $this->render('servicos/index', [
            'servicos' => Servico::all(),
            'navAtivo' => 'servicos',
        ]);
    }

    public function create(array $params = []): void
    {
        $this->render('servicos/form', [
            'servico'  => null,
            'erro'     => null,
            'navAtivo' => 'servicos',
        ]);
    }

    public function store(array $params = []): void
    {
        $form = $this->lerFormulario();

        if ($form['erro'] !== null) {
            $this->render('servicos/form', [
                'servico'  => [
                    'nome'      => $form['nome'],
                    'descricao' => $form['descricao'],
                    'preco'     => $_POST['preco'] ?? '',
                    'ativo'     => $form['ativo'],
                ],
                'erro'     => $form['erro'],
                'navAtivo' => 'servicos',
            ]);
            return;
        }

        Servico::create(
            $form['nome'],
            $form['descricao'] !== '' ? $form['descricao'] : null,
            $form['preco'],
            $form['ativo']
        );

        $this->redirect('/servicos');
    }

    public function edit(array $params): void
    {
        $servico = Servico::find((int) $params['id']);

        if ($servico === null) {
            http_response_code(404);
            echo 'Serviço não encontrado.';
            return;
        }

        $this->render('servicos/form', [
            'servico'  => $servico,
            'erro'     => null,
            'navAtivo' => 'servicos',
        ]);
    }

    public function update(array $params): void
    {
        $id   = (int) $params['id'];
        $form = $this->lerFormulario();

        if ($form['erro'] !== null) {
            $this->render('servicos/form', [
                'servico'  => [
                    'id'        => $id,
                    'nome'      => $form['nome'],
                    'descricao' => $form['descricao'],
                    'preco'     => $_POST['preco'] ?? '',
                    'ativo'     => $form['ativo'],
                ],
                'erro'     => $form['erro'],
                'navAtivo' => 'servicos',
            ]);
            return;
        }

        Servico::update(
            $id,
            $form['nome'],
            $form['descricao'] !== '' ? $form['descricao'] : null,
            $form['preco'],
            $form['ativo']
        );

        $this->redirect('/servicos');
    }

    public function destroy(array $params): void
    {
        $id    = (int) $params['id'];
        $aviso = null;

        if (Servico::emUso($id)) {
            // Já usado em agendamentos: preserva o histórico e só inativa
            Servico::inativar($id);
            $aviso = 'Este serviço já foi usado em agendamentos e foi apenas inativado (não aparece mais na marcação).';
        } else {
            try {
                Servico::delete($id);
            } catch (PDOException) {
                // Rede de segurança: nunca deixar a tela de Fatal error aparecer
                Servico::inativar($id);
                $aviso = 'Não foi possível excluir este serviço; ele foi inativado.';
            }
        }

        $this->render('servicos/index', [
            'servicos' => Servico::all(),
            'aviso'    => $aviso,
            'navAtivo' => 'servicos',
        ]);
    }


    /**
     * Lê e valida o POST do formulário.
     * @return array{nome: string, descricao: string, preco: float, ativo: int, erro: ?string}
     */
    private function lerFormulario(): array
    {
        $nome      = trim((string) ($_POST['nome'] ?? ''));
        $descricao = trim((string) ($_POST['descricao'] ?? ''));
        $ativo     = ($_POST['ativo'] ?? '1') === '1' ? 1 : 0;

        $preco = Servico::parsePreco((string) ($_POST['preco'] ?? ''));

        if ($nome === '') {
            return ['nome' => $nome, 'descricao' => $descricao, 'preco' => 0.0, 'ativo' => $ativo, 'erro' => 'O nome do serviço é obrigatório.'];
        }

        if ($preco === null || $preco < 0) {
            return ['nome' => $nome, 'descricao' => $descricao, 'preco' => 0.0, 'ativo' => $ativo, 'erro' => 'Informe um valor válido (ex.: 45,00).'];
        }

        return ['nome' => $nome, 'descricao' => $descricao, 'preco' => $preco, 'ativo' => $ativo, 'erro' => null];
    }
}
