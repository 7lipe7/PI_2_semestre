<?php

declare(strict_types=1);

namespace Controllers;

use Core\Controller;
use Core\Session;
use Agendamento;
use Cachorro;
use Servico;
use DateTimeImmutable;
use PDOException;

class AgendamentoController extends Controller
{
    public function index(array $params = []): void
    {
        $agendamentos = Agendamento::all();

        $this->render('agendamentos/index', [
            'agendamentos' => $agendamentos,
            'contagem'     => Agendamento::contagemPorCategoria($agendamentos),
            'cachorros'    => Cachorro::all(),
            'servicos'     => $this->servicosDisponiveis(),
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
        $this->renderIndiceComModal();
    }

    public function store(array $params = []): void
    {
        $cachorroId  = (int) ($_POST['cachorro_id'] ?? 0);
        $servicoId   = (int) ($_POST['servico_id'] ?? 0);
        $data        = trim((string) ($_POST['data'] ?? ''));
        $hora        = trim((string) ($_POST['hora'] ?? ''));
        $observacoes = trim((string) ($_POST['observacoes'] ?? ''));

        $erro = $this->validarCampos($cachorroId, $servicoId, $data, $hora);

        if ($erro === null && Agendamento::horarioOcupado($data, $hora)) {
            $erro = 'Este horário já está ocupado. Escolha outro.';
        }

        if ($erro !== null) {
            $this->renderIndiceComModal([
                'cachorro_id' => $cachorroId,
                'servico_id'  => $servicoId,
                'data'        => $data,
                'hora'        => $hora,
                'observacoes' => $observacoes,
            ], $erro);

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

            Session::setFlash('Agendamento marcado com sucesso.');
            $this->redirect('/agendamentos');
        } catch (PDOException $e) {
            $this->renderIndiceComModal([
                'cachorro_id' => $cachorroId,
                'servico_id'  => $servicoId,
                'data'        => $data,
                'hora'        => $hora,
                'observacoes' => $observacoes,
            ], $this->mensagemErroBanco($e));
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

        $this->renderIndiceComModal($agendamento);
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

      

        $status = in_array($statusPost, Agendamento::STATUS, true)
            ? $statusPost
            : 'agendado';


        $erro = $this->validarCampos($cachorroId, $servicoId, $data, $hora);

        if ($erro === null && Agendamento::horarioOcupado($data, $hora, $id)) {
            $erro = 'Este horário já está ocupado. Escolha outro.';
        }

        if ($erro !== null) {
            $this->renderIndiceComModal([
                'id'          => $id,
                'cachorro_id' => $cachorroId,
                'servico_id'  => $servicoId,
                'data'        => $data,
                'hora'        => $hora,
                'status'      => $status,
                'observacoes' => $observacoes,
            ], $erro);

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

            Session::setFlash('Agendamento atualizado com sucesso.');
            $this->redirect('/agendamentos');
        } catch (PDOException $e) {
            $this->renderIndiceComModal([
                'id'          => $id,
                'cachorro_id' => $cachorroId,
                'servico_id'  => $servicoId,
                'data'        => $data,
                'hora'        => $hora,
                'status'      => $status,
                'observacoes' => $observacoes,
            ], $this->mensagemErroBanco($e));
        }
    }

    /**
     * Troca rápida de status (botões do dashboard e da listagem).
     *
     * Quando a requisição vem do JavaScript (Accept: application/json), devolve
     * JSON com os dados já formatados para o card ser atualizado na hora, sem
     * recarregar a página. Sem JS, cai no redirect traditional para $destino.
     */
    public function updateStatus(array $params): void
    {
        $id     = (int) $params['id'];
        $status = (string) ($_POST['status'] ?? '');
        $destino = $this->destinoAposStatus();

        if (!in_array($status, Agendamento::STATUS, true)) {
            $this->responderStatus(422, ['erro' => 'Status inválido.'], $destino);

            return;
        }

        if (Agendamento::find($id) === null) {
            $this->responderStatus(404, ['erro' => 'Agendamento não encontrado.'], $destino);

            return;
        }

        try {
            Agendamento::updateStatus($id, $status);
        } catch (PDOException $e) {
            error_log('Falha ao mudar o status do agendamento #' . $id . ': ' . $e->getMessage());

            $this->responderStatus(
                500,
                ['erro' => 'Não foi possível mudar o status. Tente novamente.'],
                $destino
            );

            return;
        }

        if (!str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json')) {
            Session::setFlash('Status alterado para ' . Agendamento::rotulo($status) . '.');
        }

        $this->responderStatus(200, [
            'id'       => $id,
            'status'   => $status,
            'rotulo'   => Agendamento::rotulo($status),
            'icone'    => Agendamento::icone($status),
            'categoria' => Agendamento::categoriaDe(['status' => $status]),
            'acoes'    => Agendamento::acoesDe($status),
        ], $destino);
    }

    /**
     * Para onde voltar depois de mudar o status. Só aceita destinos internos
     * conhecidos — um "redirecionar" livre permitiria redirect aberto.
     */
    private function destinoAposStatus(): string
    {
        $redirecionar = (string) ($_POST['redirecionar'] ?? '');

        if ($redirecionar !== '/') {
            return '/agendamentos';
        }

        $data = trim((string) ($_POST['data'] ?? ''));

        if ($data === '') {
            return '/';
        }

        $valida = DateTimeImmutable::createFromFormat('!Y-m-d', $data);

        return $valida !== false && $valida->format('Y-m-d') === $data
            ? '/?data=' . $data
            : '/';
    }

    /**
     * Responde em JSON quando o pedido espera JSON; senão redireciona.
     * Mantém o endpoint utilizável pelos dois caminhos (com e sem JavaScript).
     */
    private function responderStatus(int $codigo, array $payload, string $destino): void
    {
        $aceita = str_contains((string) ($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');

        if (!$aceita) {
            http_response_code($codigo);

            if ($codigo >= 400) {
                echo htmlspecialchars((string) ($payload['erro'] ?? 'Operação não concluída.'), ENT_QUOTES, 'UTF-8');

                return;
            }

            $this->redirect($destino);
        }

        http_response_code($codigo);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public function destroy(array $params): void
    {
        $id = (int) $params['id'];

        try {
            Agendamento::delete($id);
        } catch (PDOException $e) {
            error_log('Falha ao excluir agendamento #' . $id . ': ' . $e->getMessage());

            $this->render('agendamentos/form', [
                'agendamento' => Agendamento::find($id) ?? [],
                'cachorros'   => Cachorro::all(),
                'servicos'    => $this->servicosDisponiveis(Agendamento::find($id)),
                'erro'        => 'Não foi possível excluir este agendamento. Tente novamente.',
                'navAtivo'    => 'agendamentos',
            ]);

            return;
        }

        $this->redirect('/agendamentos');
    }

    /**
     * Valida os campos básicos do formulário de agendamento.
     * Retorna a mensagem de erro ou null quando tudo estiver válido.
     */
    private function validarCampos(int $cachorroId, int $servicoId, string $data, string $hora): ?string
    {
        if ($cachorroId <= 0 || $servicoId <= 0) {
            return 'Cachorro e serviço são obrigatórios.';
        }

        if (!$this->dataValida($data) || !$this->horaValida($hora)) {
            return 'Informe uma data e uma hora válidas.';
        }

        return null;
    }

    private function renderIndiceComModal(array $agendamento = [], ?string $erro = null): void
    {
        $agendamentos = Agendamento::all();

        $this->render('agendamentos/index', [
            'agendamentos'           => $agendamentos,
            'contagem'               => Agendamento::contagemPorCategoria($agendamentos),
            'cachorros'              => Cachorro::all(),
            'servicos'               => $this->servicosDisponiveis($agendamento),
            'agendamentoModalAberto' => true,
            'agendamentoFormulario'  => $agendamento,
            'erro'                   => $erro,
            'navAtivo'               => 'agendamentos',
        ]);
    }

    /** Formato AAAA-MM-DD com data real (ex.: 2026-02-30 é inválida). */
    private function dataValida(string $data): bool
    {
        $dt = DateTimeImmutable::createFromFormat('!Y-m-d', $data);

        return $dt !== false && $dt->format('Y-m-d') === $data;
    }

    /** Formato HH:MM de 00:00 a 23:59. */
    private function horaValida(string $hora): bool
    {
        $h = DateTimeImmutable::createFromFormat('!H:i', $hora);

        return $h !== false && $h->format('H:i') === $hora;
    }

    /**
     * Mensagem amigável para o usuário a partir do erro do banco.
     * O detalhe técnico (SQL) vai apenas para o log do container.
     */
    private function mensagemErroBanco(PDOException $e): string
    {
        $codigo   = (string) ($e->errorInfo[0] ?? '');
        $driver   = (string) ($e->errorInfo[1] ?? 0);
        $mensagem = strtolower($e->getMessage());

        error_log('Erro de banco em agendamento: ' . $e->getMessage());

        if ($codigo === '23000' && $driver === '1062') {
            if (str_contains($mensagem, 'uq_agenda_horario')) {
                return 'Este horário já está ocupado. Escolha outro.';
            }

            if (str_contains($mensagem, 'fk_')) {
                return 'Registro vinculado a outro dado e não pode ser alterado.';
            }

            return 'Este registro já existe.';
        }

        if ($codigo === '23000' && $driver === '1451') {
            return 'Este registro está vinculado a outros dados e não pode ser excluído.';
        }

        if ($codigo === '40001') {
            return 'A operação conflitou com outra execução. Tente novamente.';
        }

        if ($codigo === 'HY000' || $e->getCode() === 'HY000') {
            return 'Falha na conexão com o banco de dados. Tente novamente.';
        }

        return 'Não foi possível concluir a operação. Tente novamente.';
    }

    /**
     * Serviços oferecidos na marcação (apenas ativos), mantendo visível
     * o serviço atual quando ele já foi inativado.
     */
    private function servicosDisponiveis(?array $agendamento = null): array
    {
        $servicos = Servico::all(true);

        $idAtual = (int) ($agendamento['servico_id'] ?? 0);

        if ($idAtual <= 0) {
            return $servicos;
        }

        $ids = array_map(fn(array $s): int => (int) $s['id'], $servicos);

        if (!in_array($idAtual, $ids, true)) {
            $atual = Servico::find($idAtual);

            if ($atual !== null) {
                $servicos[] = $atual;
            }
        }

        return $servicos;
    }
}
