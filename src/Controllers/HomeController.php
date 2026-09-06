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

        $this->render('home/home', [
            'agendamentos' => Agendamento::byData($hoje),
            'dataHoje'     => $hoje,
            'navAtivo'     => 'home',
        ]);
    }
}
