<?php

/**
 * Lista de agendamentos com seletor de categoria.
 * Variáveis:
 *   $agendamentos (com data, hora, status, cachorro_nome...),
 *   $contagem     (itens por categoria, com a chave 'todos')
 */
$agendamentos = $agendamentos ?? [];
$contagem     = $contagem ?? [];

$totalAgendamentos = count($agendamentos);

// "Todos" + as categorias do model (chave => rótulo/ícone)
$categorias = ['todos' => ['rotulo' => 'Todos', 'icone' => 'layout-grid']] + \Agendamento::CATEGORIAS;
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
                    id="agendamentosContagem"
                    value="<?= $totalAgendamentos ?> agendamento<?= $totalAgendamentos === 1 ? '' : 's' ?>"
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
        <div class="agendamentos-filtros" role="group" aria-label="Filtrar agendamentos por categoria">
            <?php foreach ($categorias as $chave => $categoria): ?>
                <?php $quantidade = (int) ($contagem[$chave] ?? 0); ?>
                <button
                    type="button"
                    class="agendamentos-filtro<?= $chave === 'todos' ? ' is-ativo' : '' ?><?= $quantidade === 0 && $chave !== 'todos' ? ' is-vazio' : '' ?>"
                    data-agendamento-filtro="<?= \Core\e($chave) ?>"
                    aria-pressed="<?= $chave === 'todos' ? 'true' : 'false' ?>">
                    <i data-lucide="<?= \Core\e($categoria['icone']) ?>" aria-hidden="true"></i>
                    <span class="agendamentos-filtro__rotulo"><?= \Core\e($categoria['rotulo']) ?></span>
                    <span class="agendamentos-filtro__contagem"><?= $quantidade ?></span>
                </button>
            <?php endforeach; ?>
        </div>

        <div class="agendamentos-grid">
            <?php foreach ($agendamentos as $a): ?>
                <?php
                $status = \Agendamento::normalizarStatus($a['status'] ?? 'agendado');

                $statusMap = [
                    'agendado' => ['class' => 'agendamento-card__badge--agendado'],
                    'andamento' => ['class' => 'agendamento-card__badge--andamento'],
                    'concluido' => ['class' => 'agendamento-card__badge--concluido'],
                    'cancelado' => ['class' => 'agendamento-card__badge--cancelado'],
                ];

                $statusInfo = $statusMap[$status] ?? ['class' => 'agendamento-card__badge--agendado'];
                $statusClass = $statusInfo['class'];
                $statusIcon = \Agendamento::icone($status);
                $statusLabel = \Agendamento::rotulo($status);
                $categoria = \Agendamento::categoriaDe($a);

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
                <article
                    class="agendamento-card"
                    data-agendamento-card
                    data-agendamento-categoria="<?= \Core\e($categoria) ?>"
                    data-agendamento-status="<?= \Core\e($status) ?>"
                >
                    <div class="agendamento-card__header">
                        <div class="agendamento-card__icon <?= $servicoClass ?>" aria-hidden="true">
                            <i data-lucide="<?= $servicoIcon ?>"></i>
                        </div>

                        <div class="agendamento-card__title-wrap">
                            <h2><?= \Core\e($a['cachorro_nome']) ?></h2>
                            <span class="agendamento-card__status-badge <?= $statusClass ?>" data-status-badge>
                                <i data-status-icon data-lucide="<?= $statusIcon ?>"></i>
                                <span data-status-rotulo><?= \Core\e($statusLabel) ?></span>
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

<script src="/assets/js/agendamentos-filtro.js"></script>

