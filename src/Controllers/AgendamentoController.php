<?php

declare(strict_types=1);

namespace Controllers;

use core\Controller;
use Agendamento;
use Cachorro;
use Servico;
use PDOException;

class AgendamentoController extends Controller
{
    public function index(array $params = []): void
    {
        $this->render('agendamentos/index', [
            'agendamentos' => Agendamento::all(),
            'navAtivo'     => 'agendamentos',
        ]);
    }

    public function historico(array $params = []): void
{
    $this->render('agendamentos/historico', [
        'historico' => Agendamento::historico(),
        'navAtivo'  => 'agendamentos',
    ]);
}

    public function create(array $params = []): void
    {
        $this->render('agendamentos/form', [
            'agendamento' => null,
            'cachorros'   => Cachorro::all(),
            'servicos'    => Servico::all(),
            'erro'        => null,
            'navAtivo'    => 'agendamentos',
        ]);
    }

    public function store(array $params = []): void
    {
        $cachorroId  = (int) ($_POST['cachorro_id'] ?? 0);
        $servicoId   = (int) ($_POST['servico_id'] ?? 0);
        $data        = trim((string) ($_POST['data'] ?? ''));
        $hora        = trim((string) ($_POST['hora'] ?? ''));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

        if (
            $cachorroId <= 0 ||
            $servicoId <= 0 ||
            $data === '' ||
            $hora === ''
        ) {
            $this->render('agendamentos/form', [
                'agendamento' => [
                    'cachorro_id' => $cachorroId,
                    'servico_id'  => $servicoId,
                    'data'        => $data,
                    'hora'        => $hora,
                    'observacoes' => $observacoes,
                ],
                'cachorros' => Cachorro::all(),
                'servicos'  => Servico::all(),
                'erro'      => 'Cachorro, serviço, data e hora são obrigatórios.',
                'navAtivo'  => 'agendamentos',
            ]);

            return;
        }

        try {
            Agendamento::create(
                $cachorroId,
                $servicoId,
                $data,
                $hora,
                $observacoes !== '' ? $observacoes : null
            );

            $this->redirect('/agendamentos');

        } catch (PDOException) {
            // UNIQUE (data, hora) = horário já ocupado
            $this->render('agendamentos/form', [
                'agendamento' => [
                    'cachorro_id' => $cachorroId,
                    'servico_id'  => $servicoId,
                    'data'        => $data,
                    'hora'        => $hora,
                    'observacoes' => $observacoes,
                ],
                'cachorros' => Cachorro::all(),
                'servicos'  => Servico::all(),
                'erro'      => 'Este horário já está ocupado. Escolha outro.',
                'navAtivo'  => 'agendamentos',
            ]);
        }
    }

    public function edit(array $params): void
    {
        $agendamento = Agendamento::find((int) $params['id']);

        if ($agendamento === null) {
            http_response_code(404);
            echo 'Agendamento não encontrado.';
            return;
        }

        $this->render('agendamentos/form', [
            'agendamento' => $agendamento,
            'cachorros'   => Cachorro::all(),
            'servicos'    => Servico::all(),
            'erro'        => null,
            'navAtivo'    => 'agendamentos',
        ]);
    }

    public function update(array $params): void
    {
        $id          = (int) $params['id'];
        $cachorroId  = (int) ($_POST['cachorro_id'] ?? 0);
        $servicoId   = (int) ($_POST['servico_id'] ?? 0);
        $data        = trim((string) ($_POST['data'] ?? ''));
        $hora        = trim((string) ($_POST['hora'] ?? ''));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

        $statusPost = (string) ($_POST['status'] ?? '');

        $status = in_array(
            $statusPost,
            ['agendado', 'concluido', 'cancelado'],
            true
        )
            ? $statusPost
            : 'agendado';

        if (
            $cachorroId <= 0 ||
            $servicoId <= 0 ||
            $data === '' ||
            $hora === ''
        ) {
            $this->render('agendamentos/form', [
                'agendamento' => [
                    'id'          => $id,
                    'cachorro_id' => $cachorroId,
                    'servico_id'  => $servicoId,
                    'data'        => $data,
                    'hora'        => $hora,
                    'status'      => $status,
                    'observacoes' => $observacoes,
                ],
                'cachorros' => Cachorro::all(),
                'servicos'  => Servico::all(),
                'erro'      => 'Cachorro, serviço, data e hora são obrigatórios.',
                'navAtivo'  => 'agendamentos',
            ]);

            return;
        }

        try {
            Agendamento::update(
                $id,
                $cachorroId,
                $servicoId,
                $data,
                $hora,
                $observacoes !== '' ? $observacoes : null,
                $status
            );

            $this->redirect('/agendamentos');

        } catch (PDOException) {
            // UNIQUE (data, hora) = horário já ocupado
            $this->render('agendamentos/form', [
                'agendamento' => [
                    'id'          => $id,
                    'cachorro_id' => $cachorroId,
                    'servico_id'  => $servicoId,
                    'data'        => $data,
                    'hora'        => $hora,
                    'status'      => $status,
                    'observacoes' => $observacoes,
                ],
                'cachorros' => Cachorro::all(),
                'servicos'  => Servico::all(),
                'erro'      => 'Este horário já está ocupado. Escolha outro.',
                'navAtivo'  => 'agendamentos',
            ]);
        }
    }

    public function destroy(array $params): void
    {
        Agendamento::delete((int) $params['id']);

        $this->redirect('/agendamentos');
    }
}

