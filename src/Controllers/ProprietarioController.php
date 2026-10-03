<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Proprietario;

class ProprietarioController extends Controller{
    public function index(array $params = []): void
    {
        $busca = trim((string) ($_GET['busca'] ?? ''));

        $this->render('proprietarios/index', [
            'clientes' => Proprietario::allWithVisitas($busca),
            'busca'    => $busca,
            'navAtivo' => 'clientes',
        ]);
    }
    public function create(array $params = []): void
    {
        $this->render('proprietarios/index', [
            'clientes'    => Proprietario::allWithVisitas(),
            'busca'       => '',
            'cliente'     => null,
            'formCliente' => null,
            'erro'        => null,
            'modalAberto' => true,
            'navAtivo'    => 'clientes',
        ]);
    }

    public function store(array $params = []): void
    {
        $nome    = trim((string) ($_POST['nome'] ?? ''));
        $celular = trim((string) ($_POST['celular'] ?? ''));
        $obs     = trim((string) ($_POST['observacoes'] ?? ''));

        if ($nome === '' || $celular === '') {
            $this->render('proprietarios/index', [
                'clientes'    => Proprietario::allWithVisitas(),
                'busca'       => '',
                'cliente'     => null,
                'formCliente' => null,
                'erro'        => 'Nome e celular são obrigatórios.',
                'modalAberto' => true,
                'navAtivo'    => 'clientes',
            ]);
            return;
        }

        Proprietario::create($nome, $celular, $obs !== '' ? $obs : null);

        $this->redirect('/clientes');
    }
    
    public function edit(array $params): void
    {
        $cliente = Proprietario::find((int) $params['id']);

        if ($cliente === null) {
            http_response_code(404);
            echo 'Cliente não encontrado.';
            return;
        }

        $this->render('proprietarios/index', [
            'clientes'    => Proprietario::allWithVisitas(),
            'busca'       => '',
            'cliente'     => $cliente,
            'formCliente' => $cliente,
            'erro'        => null,
            'modalAberto' => true,
            'navAtivo'    => 'clientes',
        ]);
    }

    public function update(array $params): void
    {
        $id      = (int) $params['id'];
        $nome    = trim((string) ($_POST['nome'] ?? ''));
        $celular = trim((string) ($_POST['celular'] ?? ''));
        $obs     = trim((string) ($_POST['observacoes'] ?? ''));

        if ($nome === '' || $celular === '') {
            $this->render('proprietarios/index', [
                'clientes'    => Proprietario::allWithVisitas(),
                'busca'       => '',
                'cliente'     => ['id' => $id, 'nome' => $nome, 'celular' => $celular, 'observacoes' => $obs],
                'formCliente' => ['id' => $id, 'nome' => $nome, 'celular' => $celular, 'observacoes' => $obs],
                'erro'        => 'Nome e celular são obrigatórios.',
                'modalAberto' => true,
                'navAtivo'    => 'clientes',
            ]);
            return;
        }

        Proprietario::update($id, $nome, $celular, $obs !== '' ? $obs : null);

        $this->redirect('/clientes');
    }
    public function destroy(array $params): void
    {
        // ATENÇÃO: CASCADE no banco — exclui os cachorros (e agendamentos) do cliente (entao alertar no front)
        Proprietario::delete((int) $params['id']);

        $this->redirect('/clientes');
    }
}