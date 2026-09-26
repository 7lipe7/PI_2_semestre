<?php

declare(strict_types=1);

namespace Controllers;

use core\Controller;
use Agendamento;

class HomeController extends Controller
{
    public function index(array $params = []): void
    {
        $hoje = date('Y-m-d');

        // $_GET pode chegar como array (ex.: ?data[]=...): só aceita string
        $dataInformada = $_GET['data'] ?? null;
        $data = $this->dataSelecionada(is_string($dataInformada) ? $dataInformada : null) ?? $hoje;

        $this->render('home/home', [
            'agendamentos'    => Agendamento::byData($data),
            'dataSelecionada' => $data,
            'dataHoje'        => $hoje,
            'dataAnterior'    => date('Y-m-d', strtotime($data . ' -1 day')),
            'dataProxima'     => date('Y-m-d', strtotime($data . ' +1 day')),
            'ehHoje'          => $data === $hoje,
            'navAtivo'        => 'home',
        ]);
    }

    /**
     * Valida o ?data=Y-m-d vindo da URL (navegação ontem/amanhã do dashboard).
     * Retorna null quando ausente ou inválida — a agenda volta a mostrar hoje.
     */
    private function dataSelecionada(?string $data): ?string
    {
        $data = trim((string) $data);

        if ($data === '') {
            return null;
        }

        $valida = \DateTime::createFromFormat('Y-m-d', $data);

        return ($valida && $valida->format('Y-m-d') === $data) ? $data : null;
    }
}
