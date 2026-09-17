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

        <a href="/agendamentos/historico" class="btn clientes-new-btn">
            <i data-lucide="history"></i>
            <span>Histórico</span>
        </a>
        
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
                $status = (string) ($a['status'] ?? 'agendado');
                $statusLabel = [
                    'agendado' => 'Agendado',
                    'concluido' => 'Concluído',
                    'cancelado' => 'Cancelado',
                ][$status] ?? ucfirst($status);

                $statusClass = 'agendamento-card__badge--' . $status;
                ?>
                <article class="agendamento-card">
                    <div class="agendamento-card__header">
                        <div class="agendamento-card__icon" aria-hidden="true">
                            <i data-lucide="calendar-check-2"></i>
                        </div>

                        <div class="agendamento-card__title-wrap">
                            <h2><?= \Core\e($a['cachorro_nome']) ?></h2>
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
                            <span><?= \Core\e($a['servico_nome']) ?></span>
                        </div>

                        <?php if (!empty($a['observacoes'])): ?>
                            <div class="agendamento-card__description">
                                <span class="cliente-card__description-icon" aria-hidden="true">
                                    <i data-lucide="message-square-text"></i>
                                </span>
                                <span><?= \Core\e($a['observacoes']) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="agendamento-card__footer">
                        <a href="/agendamentos/<?= (int) $a['id'] ?>/editar" class="agendamento-card__action agendamento-card__action--edit">
                            <i data-lucide="pencil"></i>
                            <span>Editar</span>
                        </a>

                        <form method="post" action="/agendamentos/<?= (int) $a['id'] ?>/excluir"
                              class="agendamento-card__delete-form"
                              onsubmit="return confirm('Cancelar este agendamento?')">
                            <button type="submit" class="agendamento-card__action agendamento-card__action--delete">
                                <i data-lucide="trash-2"></i>
                                <span>Cancelar</span>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

