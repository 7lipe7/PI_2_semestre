<?php

declare (strict_types = 1);

namespace Controllers;

use core\Controller;
use Agendamento;
use Cachorro;
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

    public function create(array $params = []): void
    {
        $this->render('agendamentos/form', [
            'agendamento' => null,
            'cachorros'   => Cachorro::all(),
            'erro'        => null,
            'navAtivo'    => 'agendamentos',
        ]);
    }

    public function store(array $params = []): void
    {
        $cachorroId  = (int) ($_POST['cachorro_id'] ?? 0);
        $servico     = trim((string) ($_POST['servico'] ?? ''));
        $data        = trim((string) ($_POST['data'] ?? ''));
        $hora        = trim((string) ($_POST['hora'] ?? ''));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

        if ($cachorroId <= 0 || $servico === '' || $data === '' || $hora === '') {
            $this->render('agendamentos/form', [
                'agendamento' => null,
                'cachorros'   => Cachorro::all(),
                'erro'        => 'Cachorro, serviço, data e hora são obrigatórios.',
                'navAtivo'    => 'agendamentos',
            ]);
            return;
        }

        // ENUM do banco: só aceita esses valores qualquer va
        if (! in_array($servico, ['banho', 'tosa', 'banho_e_tosa'], true)) {
            $servico = 'banho';
        }

        try {
            Agendamento::create($cachorroId, $servico, $data, $hora, $observacoes !== '' ? $observacoes : null);
            $this->redirect('/agendamentos');
        } catch (PDOException) {
            // UNIQUE (data, hora) estourou ==  horário já ocupado
            $this->render('agendamentos/form', [
                'agendamento' => ['cachorro_id' => $cachorroId, 'servico' => $servico, 'data' => $data, 'hora' => $hora],
                'cachorros'   => Cachorro::all(),
                'erro'        => 'Este horário já está ocupado. Escolha outro.',
                'navAtivo'    => 'agendamentos',
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
            'erro'        => null,
            'navAtivo'    => 'agendamentos',
        ]);
    }

    public function update(array $params): void
    {
        $id          = (int) $params['id'];
        $cachorroId  = (int) ($_POST['cachorro_id'] ?? 0);
        $servico     = trim((string) ($_POST['servico'] ?? ''));
        $data        = trim((string) ($_POST['data'] ?? ''));
        $hora        = trim((string) ($_POST['hora'] ?? ''));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));
        $statusPost = (string) ($_POST['status'] ?? '');
        $status     = in_array($statusPost, ['agendado', 'concluido', 'cancelado'], true)
            ? $statusPost
            : 'agendado';

        if ($cachorroId <= 0 || $servico === '' || $data === '' || $hora === '') {
            $this->render('agendamentos/form', [
                // repõe os dados digitados pra não apagar o que o usuário escreveu
                'agendamento' => [
                    'id'          => $id,
                    'cachorro_id' => $cachorroId,
                    'servico'     => $servico,
                    'data'        => $data,
                    'hora'        => $hora,
                    'status'      => $status,
                    'observacoes' => $observacoes,
                ],
                'cachorros'   => Cachorro::all(),
                'erro'        => 'Cachorro, serviço, data e hora são obrigatórios.',
                'navAtivo'    => 'agendamentos',
            ]);
            return;
        }

        // ENUM do banco: só aceita esses valores
        if (! in_array($servico, ['banho', 'tosa', 'banho_e_tosa'], true)) {
            $servico = 'banho';
        }

        try {
            Agendamento::update(
                $id,
                $cachorroId,
                $servico,
                $data,
                $hora,
                $observacoes !== '' ? $observacoes : null,
                $status
            );
            $this->redirect('/agendamentos');
        } catch (PDOException) {
            // UNIQUE (data, hora) estourou == horário já ocupado
            $this->render('agendamentos/form', [
                'agendamento' => [
                    'id'          => $id,
                    'cachorro_id' => $cachorroId,
                    'servico'     => $servico,
                    'data'        => $data,
                    'hora'        => $hora,
                    'status'      => $status,
                    'observacoes' => $observacoes,
                ],
                'cachorros'   => Cachorro::all(),
                'erro'        => 'Este horário já está ocupado. Escolha outro.',
                'navAtivo'    => 'agendamentos',
            ]);
        }
    }

    public function destroy(array $params): void
    {
        Agendamento::delete((int) $params['id']);

        $this->redirect('/agendamentos');
    }
}
