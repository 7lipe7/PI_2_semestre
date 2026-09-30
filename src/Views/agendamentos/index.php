<?php

/** Lista de agendamentos. Variável: $agendamentos (com data, hora, status, cachorro_nome...) */
$agendamentos = $agendamentos ?? [];
?>
<div class="clientes-page agendamentos-page">
    <div class="clientes-toolbar d-flex align-items-center justify-content-start gap-3 flex-wrap" style="margin-left: 0; padding-left: 0;">
        <div class="clientes-search-wrap flex-grow-1">
            <div class="clientes-search-form d-flex align-items-center" aria-label="Resumo de agendamentos">
                <span class="clientes-search-icon" aria-hidden="true">
                    <i data-lucide="calendar-days"></i>
                </span>
                <input
                    type="text"
                    class="clientes-search-input"
                    value="<?= count($agendamentos) ?> agendamento<?= count($agendamentos) === 1 ? '' : 's' ?>"
                    readonly
                    aria-label="Quantidade de agendamentos"
                >
            </div>
        </div>

        <a href="/agendamentos/novo" class="btn clientes-new-btn">
            <i data-lucide="plus"></i>
            <span>Marcar banho</span>
        </a>
    </div>

    <?php if (empty($agendamentos)): ?>
        <div class="clientes-empty">
            <p>Nenhum horário marcado.</p>
        </div>
    <?php else: ?>
        <div class="agendamentos-grid">
            <?php foreach ($agendamentos as $a): ?>
                <?php
                $status = strtolower((string) ($a['status'] ?? 'agendado'));

                $statusMap = [
                    'agendado' => ['icon' => 'calendar-check-2', 'label' => 'Agendado', 'class' => 'agendamento-card__badge--agendado'],
                    'andamento' => ['icon' => 'activity', 'label' => 'Em andamento', 'class' => 'agendamento-card__badge--andamento'],
                    'concluido' => ['icon' => 'check-circle', 'label' => 'Concluído', 'class' => 'agendamento-card__badge--concluido'],
                    'cancelado' => ['icon' => 'x-circle', 'label' => 'Cancelado', 'class' => 'agendamento-card__badge--cancelado'],
                ];

                $statusInfo = $statusMap[$status] ?? ['icon' => 'clock-3', 'label' => ucfirst($status), 'class' => 'agendamento-card__badge--agendado'];
                $statusClass = $statusInfo['class'];
                $statusIcon = $statusInfo['icon'];
                $statusLabel = $statusInfo['label'];

                $servicoNome = strtolower((string) ($a['servico_nome'] ?? ''));
                $servicoClass = 'agendamento-card__icon--default';
                $servicoIcon = 'scissors';

                if (strpos($servicoNome, 'banho') !== false) {
                    $servicoClass = 'agendamento-card__icon--banho';
                    $servicoIcon = 'sparkles';
                } elseif (strpos($servicoNome, 'tosa') !== false) {
                    $servicoClass = 'agendamento-card__icon--tosa';
                    $servicoIcon = 'brush-cleaning';
                } elseif (strpos($servicoNome, 'higiene') !== false) {
                    $servicoClass = 'agendamento-card__icon--higiene';
                    $servicoIcon = 'shield-check';
                }
                ?>
                <article class="agendamento-card">
                    <div class="agendamento-card__header">
                        <div class="agendamento-card__icon <?= $servicoClass ?>" aria-hidden="true">
                            <i data-lucide="<?= $servicoIcon ?>"></i>
                        </div>

                        <div class="agendamento-card__title-wrap">
                            <h2><?= \Core\e($a['cachorro_nome']) ?></h2>
                            <span class="agendamento-card__status-badge <?= $statusClass ?>" data-status-badge>
                                <i data-status-icon data-lucide="<?= $statusIcon ?>"></i>
                                <span><?= $status === 'andamento' ? 'Em andamento' : ($status === 'concluido' ? 'Concluído' : ucfirst($status)) ?></span>
                            </span>
                            <p><?= \Core\e($a['proprietario_nome']) ?></p>
                        </div>

                        <span class="agendamento-card__badge <?= $statusClass ?>"><?= \Core\e($statusLabel) ?></span>
                    </div>

                    <div class="agendamento-card__info">
                        <div class="agendamento-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="calendar-range"></i>
                            </span>
                            <span><?= date('d/m/Y', strtotime($a['data'])) ?></span>
                        </div>

                        <div class="agendamento-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="clock-3"></i>
                            </span>
                            <span><?= date('H:i', strtotime($a['hora'])) ?></span>
                        </div>

                        <div class="agendamento-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="scissors"></i>
                            </span>
                            <span>
                                <?= \Core\e($a['servico_nome']) ?>
                                — R$ <?= number_format((float) ($a['servico_preco'] ?? 0), 2, ',', '.') ?>
                            </span>
                        </div>

                        <div class="agendamento-card__description">
                            <span class="cliente-card__description-icon" aria-hidden="true">
                                <i data-lucide="message-square-text"></i>
                            </span>
                            <span><?= !empty($a['observacoes'])
                                        ? \Core\e($a['observacoes'])
                                        : 'Nenhuma descrição informada' ?></span>
                        </div>
                    </div>

                    <div class="agendamento-card__footer">
                        <a href="/agendamentos/<?= (int) $a['id'] ?>/editar"
                           class="agendamento-card__action agendamento-card__action--edit"
                           data-agendamento-edit
                           data-agendamento-id="<?= (int) $a['id'] ?>">
                            <i data-lucide="pencil"></i>
                            <span>Editar</span>
                        </a>

                        <form method="post" action="/agendamentos/<?= (int) $a['id'] ?>/excluir"
                              class="agendamento-card__delete-form"
                              onsubmit="return confirm('Cancelar este agendamento?')">
                            <?= \Core\Csrf::campo() ?>
                            <button type="submit" class="agendamento-card__action agendamento-card__action--delete">
                                <i data-lucide="trash-2"></i>
                                <span>Cancelar</span>
                            </button>
                        </form>
                        
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        <div class="clientes-empty agendamentos-empty-filter hidden" id="agendamentosEmptyFilter">
            <p>Nenhum agendamento encontrado neste filtro.</p>
        </div>
    <?php endif; ?>
</div>

