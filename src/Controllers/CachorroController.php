<?php

declare (strict_types = 1);

namespace Controllers;

use Cachorro;
use Proprietario;
use core\Controller;

class CachorroController extends Controller
{

    public function index(array $params = []): void
    {

        $this->render('pets/index', [
            'pets'     => Cachorro::all(),
            'navAtivo' => 'pets',
        ]);

    }

    public function create(array $params = []): void
    {
        $this->render('pets/form', [
            'pet'           => null,
            'proprietarios' => Proprietario::all(),
            'erro'          => null,
            'navAtivo'      => 'pets',
        ]);
    }

    public function store(array $params = []): void
    {
        $nome           = trim((string) ($_POST['nome'] ?? ''));
        $proprietarioId = (int) ($_POST['proprietario_id'] ?? 0);
        $raca           = trim((string) ($_POST['raca'] ?? ''));
        $porte          = trim((string) ($_POST['porte'] ?? ''));
        $pelagem        = trim((string) ($_POST['pelagem'] ?? ''));
        $observacoes    = trim((string) ($_POST['observacoes'] ?? ''));

        if ($nome === '' || $proprietarioId <= 0) {
            $this->render('pets/form', [
                'pet'           => null,
                'proprietarios' => Proprietario::all(),
                'erro'          => 'Nome e proprietário são obrigatórios.',
                'navAtivo'      => 'pets',

            ]);
            return;
        }

        $porte = in_array($porte, ['pequeno', 'medio', 'grande'], true) ? $porte : null;

        Cachorro::create(
            $proprietarioId,
            $nome,
            $raca !== '' ? $raca : null,
            $porte,
            $pelagem !== '' ? $pelagem : null,
            $observacoes !== '' ? $observacoes : null
        );

        $this->redirect('/pets');

    }
    public function edit(array $params): void
    {
        $pet = Cachorro::find((int) $params['id']);

        if ($pet === null) {
            http_response_code(404);
            echo 'Cachorro não encontrado.';
            return;
        }

        $this->render('pets/form', [
            'pet'           => $pet,
            'proprietarios' => Proprietario::all(),
            'erro'          => null,
            'navAtivo'      => 'pets',
        ]);
    }

    public function update(array $params): void
    {
        $id             = (int) $params['id'];
        $nome           = trim((string) ($_POST['nome'] ?? ''));
        $proprietarioId = (int) ($_POST['proprietario_id'] ?? 0);
        $raca           = trim((string) ($_POST['raca'] ?? ''));
        $porte          = trim((string) ($_POST['porte'] ?? ''));
        $pelagem        = trim((string) ($_POST['pelagem'] ?? ''));
        $observacoes    = trim((string) ($_POST['observacoes'] ?? ''));

        if ($nome === '' || $proprietarioId <= 0) {
            $this->render('pets/form', [
                'pet'           => [
                    'id'              => $id,
                    'nome'            => $nome,
                    'proprietario_id' => $proprietarioId,
                    'raca'            => $raca,
                    'porte'           => $porte,
                    'pelagem'         => $pelagem,
                    'observacoes'     => $observacoes,
                ],
                'proprietarios' => Proprietario::all(),
                'erro'          => 'Nome e proprietário são obrigatórios.',
                'navAtivo'      => 'pets',
            ]);
            return;
        }

        $porte = in_array($porte, ['pequeno', 'medio', 'grande'], true) ? $porte : null;

        Cachorro::update(
            $id,
            $proprietarioId,
            $nome,
            $raca !== '' ? $raca : null,
            $porte,
            $pelagem !== '' ? $pelagem : null,
            $observacoes !== '' ? $observacoes : null
        );

        $this->redirect('/pets');
    }

    public function destroy(array $params): void
{
    //  CASCADE no banco — excluir o cachorro apaga os agendamentos dele !!!
    Cachorro::delete((int) $params['id']);

    $this->redirect('/pets');
}


}
