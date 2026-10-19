<?php
/**
 * Agenda do dia (dashboard) — interativa.
 * Variáveis:
 *   $dataSelecionada (Y-m-d exibida), $dataHoje, $dataAnterior, $dataProxima, $ehHoje,
 *   $agendamentos, $resumo (totais por status no dia)
 */
$agendamentos    ??= [];
$dataSelecionada ??= $dataHoje ?? date('Y-m-d');
$resumo          ??= array_fill_keys(\Agendamento::STATUS, 0);

$diasSemana = ['domingo', 'segunda-feira', 'terça-feira', 'quarta-feira', 'quinta-feira', 'sexta-feira', 'sábado'];
$diaSemana  = $diasSemana[(int) date('w', strtotime($dataSelecionada))];

$quantidade     = count($agendamentos);
$textoQuantidade = $quantidade === 0
    ? 'Nenhum atendimento'
    : ($quantidade === 1 ? '1 atendimento' : $quantidade . ' atendimentos');

// Rótulos amigáveis: "Ontem"/"Amanhã" quando o dia vizinho é relativo a hoje
$rotuloAnterior = $dataAnterior === date('Y-m-d', strtotime($dataHoje . ' -1 day'))
    ? 'Ontem'
    : date('d/m', strtotime($dataAnterior));

$rotuloProxima = $dataProxima === date('Y-m-d', strtotime($dataHoje . ' +1 day'))
    ? 'Amanhã'
    : date('d/m', strtotime($dataProxima));

// Cards do resumo do dia (mesma ordem das categorias da listagem)
$resumoCards = [
    ['chave' => 'agendado',  'rotulo' => 'Agendados',  'icone' => 'calendar-clock'],
    ['chave' => 'andamento', 'rotulo' => 'Em andamento', 'icone' => 'activity'],
    ['chave' => 'concluido', 'rotulo' => 'Concluídos', 'icone' => 'check-circle'],
    ['chave' => 'cancelado', 'rotulo' => 'Cancelados', 'icone' => 'x-circle'],
];
?>
<div class="clientes-page agenda-page">

    <div class="agenda-toolbar">
        <div class="agenda-nav" aria-label="Navegação por data">
            <a class="agenda-nav__arrow"
               href="/?data=<?= \Core\e($dataAnterior) ?>"
               aria-label="Ver <?= \Core\e(date('d/m/Y', strtotime($dataAnterior))) ?> (dia anterior)"
               title="<?= \Core\e(date('d/m/Y', strtotime($dataAnterior))) ?>">
                <i data-lucide="chevron-left" aria-hidden="true"></i>
                <span class="agenda-nav__hint"><?= \Core\e($rotuloAnterior) ?></span>
            </a>

            <div class="agenda-nav__atual">
                <span class="agenda-nav__data">
                    <?= date('d/m/Y', strtotime($dataSelecionada)) ?>
                    <?php if ($ehHoje): ?>
                        <span class="agenda-nav__badge">Hoje</span>
                    <?php endif; ?>
                </span>
                <span class="agenda-nav__meta"><?= \Core\e($diaSemana) ?> · <?= \Core\e($textoQuantidade) ?></span>
            </div>

            <a class="agenda-nav__arrow"
               href="/?data=<?= \Core\e($dataProxima) ?>"
               aria-label="Ver <?= \Core\e(date('d/m/Y', strtotime($dataProxima))) ?> (próximo dia)"
               title="<?= \Core\e(date('d/m/Y', strtotime($dataProxima))) ?>">
                <span class="agenda-nav__hint"><?= \Core\e($rotuloProxima) ?></span>
                <i data-lucide="chevron-right" aria-hidden="true"></i>
            </a>
        </div>

        <form class="agenda-jump" method="get" action="/">
            <label class="visually-hidden" for="agenda-data">Escolher data</label>
            <input type="date" id="agenda-data" name="data" value="<?= \Core\e($dataSelecionada) ?>">
            <button type="submit" class="agenda-jump__btn">
                <i data-lucide="calendar-search" aria-hidden="true"></i>
                <span>Ver data</span>
            </button>
        </form>

        <?php if (!$ehHoje): ?>
            <a class="agenda-hoje" href="/">
                <i data-lucide="calendar-check" aria-hidden="true"></i>
                <span>Voltar para hoje</span>
            </a>
        <?php endif; ?>
    </div>

    <?php if (empty($agendamentos)): ?>
        <div class="clientes-empty">
            <p>Nenhum atendimento marcado para esta data.</p>
            <a href="/agendamentos/novo">Marcar banho e tosa</a>
        </div>
    <?php else: ?>
        <div class="agenda-resumo">
            <?php foreach ($resumoCards as $card): ?>
                <?php $totalCard = (int) ($resumo[$card['chave']] ?? 0); ?>
                <div class="agenda-resumo__card agenda-resumo__card--<?= \Core\e($card['chave']) ?>">
                    <span class="agenda-resumo__label">
                        <i data-lucide="<?= \Core\e($card['icone']) ?>" aria-hidden="true"></i>
                        <?= \Core\e($card['rotulo']) ?>
                    </span>
                    <strong data-agenda-resumo="<?= \Core\e($card['chave']) ?>"><?= $totalCard ?></strong>
                </div>
            <?php endforeach; ?>
        </div>

        <ul class="agenda-lista">
            <?php foreach ($agendamentos as $a): ?>
                <?php
                $id     = (int) $a['id'];
                $status = \Agendamento::normalizarStatus($a['status'] ?? 'agendado');
                $acoes  = \Agendamento::acoesDe($status);
                ?>
                <li
                    class="agenda-lista__item agenda-lista__item--<?= \Core\e($status) ?>"
                    data-agenda-item
                    data-agendamento-id="<?= $id ?>"
                    data-agendamento-status="<?= \Core\e($status) ?>"
                    data-agendamento-categoria="<?= \Core\e(\Agendamento::categoriaDe($a)) ?>"
                    data-agendamento-data="<?= \Core\e($dataSelecionada) ?>"
                >
                    <span class="agenda-lista__hora"><?= date('H:i', strtotime($a['hora'])) ?></span>

                    <div class="agenda-lista__info">
                        <strong><?= \Core\e($a['cachorro_nome']) ?></strong>
                        <span>
                            <?= \Core\e($a['proprietario_nome']) ?> ·
                            <?= \Core\e($a['servico_nome']) ?>
                        </span>
                    </div>

                    <span
                        class="agenda-lista__status agenda-lista__status--<?= \Core\e($status) ?>"
                        data-agenda-status
                        title="<?= \Core\e(\Agendamento::rotulo($status)) ?>"
                    >
                        <i data-agenda-status-icon data-lucide="<?= \Core\e(\Agendamento::icone($status)) ?>" aria-hidden="true"></i>
                        <span data-agenda-status-rotulo><?= \Core\e(\Agendamento::rotulo($status)) ?></span>
                    </span>

                    <div class="agenda-lista__acoes" data-agenda-acoes>
                        <?php foreach ($acoes as $acao): ?>
                            <form
                                method="post"
                                action="/agendamentos/<?= $id ?>/status"
                                class="agenda-lista__acao-form"
                                data-agenda-acao-form
                            >
                                <?= \Core\Csrf::campo() ?>
                                <input type="hidden" name="status" value="<?= \Core\e($acao['status']) ?>">
                                <input type="hidden" name="redirecionar" value="/">
                                <input type="hidden" name="data" value="<?= \Core\e($dataSelecionada) ?>">

                                <button
                                    type="submit"
                                    class="agenda-lista__acao agenda-lista__acao--<?= \Core\e($acao['status']) ?>"
                                    data-agenda-acao="<?= \Core\e($acao['status']) ?>"
                                >
                                    <i data-lucide="<?= \Core\e($acao['icone']) ?>" aria-hidden="true"></i>
                                    <span><?= \Core\e($acao['rotulo']) ?></span>
                                </button>
                            </form>
                        <?php endforeach; ?>
                    </div>

                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <p class="agenda-aviso hidden" id="agendaAviso" role="status" aria-live="polite"></p>
</div>

<script nonce="<?= \Core\Csp::nonce() ?>">
    // Token usado para remontar os botões quando o status muda (o do PHP, nunca gerado aqui)
    window.AGENDA_CSRF = <?= json_encode(\Core\Csrf::token()) ?>;


</script>
<script src="/assets/js/agenda-status.js"></script>

