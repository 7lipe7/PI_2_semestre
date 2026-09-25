<?php
/**
 * Agenda do dia (dashboard). Variáveis:
 *   $dataSelecionada (Y-m-d exibida), $dataHoje, $dataAnterior, $dataProxima, $ehHoje, $agendamentos
 */
$agendamentos    ??= [];
$dataSelecionada ??= $dataHoje ?? date('Y-m-d');

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
        <ul class="agenda-lista">
            <?php foreach ($agendamentos as $a): ?>
                <li class="agenda-lista__item">
                    <span class="agenda-lista__hora"><?= date('H:i', strtotime($a['hora'])) ?></span>

                    <div class="agenda-lista__info">
                        <strong><?= \Core\e($a['cachorro_nome']) ?></strong>
                        <span>
                            <?= \Core\e($a['proprietario_nome']) ?> ·
                            <?= \Core\e($a['servico_nome']) ?>
                        </span>
                    </div>

                    <a class="agenda-lista__link" href="/agendamentos/<?= (int) $a['id'] ?>/editar">
                        <i data-lucide="pencil" aria-hidden="true"></i>
                        <span>Editar</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>

