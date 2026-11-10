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

                if (strpos($servicoNome, 'banho') !== false) {
                    $servicoClass = 'agendamento-card__icon--banho';
                } elseif (strpos($servicoNome, 'tosa') !== false) {
                    $servicoClass = 'agendamento-card__icon--tosa';
                } elseif (strpos($servicoNome, 'higiene') !== false) {
                    $servicoClass = 'agendamento-card__icon--higiene';
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
                            <i data-lucide="dog"></i>
                        </div>

                        <div class="agendamento-card__title-wrap">
                            <h2><?= \Core\e($a['cachorro_nome']) ?></h2>
                            <span class="agendamento-card__status-badge <?= $statusClass ?>" data-status-badge>
                                <i data-status-icon data-lucide="<?= $statusIcon ?>"></i>
                                <span data-status-rotulo><?= \Core\e($statusLabel) ?></span>
                            </span>
                            <p>Tutor: <?= \Core\e($a['proprietario_nome']) ?></p>
                        </div>

                        <form method="post" action="/agendamentos/<?= (int) $a['id'] ?>/status" class="agendamento-card__status-form">
                            <?= \Core\Csrf::campo() ?>
                            <select
                                id="agendamento-status-<?= (int) $a['id'] ?>"
                                name="status"
                                class="agendamento-card__status-select"
                                data-agendamento-status-select
                                aria-label="Alterar status do agendamento de <?= \Core\e($a['cachorro_nome']) ?>">
                                <option value="agendado" <?= $status === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                                <option value="andamento" <?= $status === 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                                <option value="concluido" <?= $status === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelado" <?= $status === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </form>
                    </div>

                    <div class="agendamento-card__info">
                        <div class="agendamento-card__line agendamento-card__line--date">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="calendar-range"></i>
                            </span>
                            <span><?= date('d/m/Y', strtotime($a['data'])) ?></span>
                        </div>

                        <div class="agendamento-card__line agendamento-card__line--time">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="clock-3"></i>
                            </span>
                            <span><?= date('H:i', strtotime($a['hora'])) ?></span>
                        </div>

                        <div class="agendamento-card__line agendamento-card__line--service">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="scissors"></i>
                            </span>
                            <span class="agendamento-card__service-name"><?= \Core\e($a['servico_nome']) ?></span>
                            <strong class="agendamento-card__service-price">R$ <?= number_format((float) ($a['servico_preco'] ?? 0), 2, ',', '.') ?></strong>
                        </div>

                        <div class="agendamento-card__description">
                            <span class="cliente-card__description-icon" aria-hidden="true">
                                <i data-lucide="message-square-text"></i>
                            </span>
                            <span><?= !empty($a['observacoes'])
                                        ? \Core\e($a['observacoes'])
                                        : 'Nenhuma descrição informada' ?></span>
                        </div>

                        <button
                            type="button"
                            class="cliente-card__details-btn agendamento-card__details-btn"
                            data-agendamento-detalhes
                            data-pet="<?= \Core\e($a['cachorro_nome']) ?>"
                            data-tutor="<?= \Core\e($a['proprietario_nome']) ?>"
                            data-servico="<?= \Core\e($a['servico_nome']) ?>"
                            data-valor="R$ <?= number_format((float) ($a['servico_preco'] ?? 0), 2, ',', '.') ?>"
                            data-data="<?= date('d/m/Y', strtotime($a['data'])) ?>"
                            data-hora="<?= date('H:i', strtotime($a['hora'])) ?>"
                            data-status="<?= \Core\e($statusLabel) ?>"
                            data-observacoes="<?= \Core\e((string) ($a['observacoes'] ?? '')) ?>">
                            <span>Ver detalhes</span>
                            <i data-lucide="chevron-right" aria-hidden="true"></i>
                        </button>
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
                              data-confirm-title="Você tem certeza que deseja deletar este agendamento?"
                              data-confirm-message="Esta ação não poderá ser desfeita.">
                            <?= \Core\Csrf::campo() ?>
                            <button type="submit" class="agendamento-card__action agendamento-card__action--delete">
                                <i data-lucide="trash-2"></i>
                                <span>Excluir</span>
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

<div class="cliente-detalhes-backdrop hidden" id="agendamentoDetalhesBackdrop" aria-hidden="true">
    <div class="cliente-detalhes-modal pet-detalhes-modal agendamento-detalhes-modal" role="dialog" aria-modal="true" aria-labelledby="agendamentoDetalhesTitulo">
        <div class="cliente-detalhes-header">
            <h3 id="agendamentoDetalhesTitulo">Detalhes do agendamento</h3>
        </div>
        <button type="button" class="cliente-detalhes-close" id="agendamentoDetalhesClose" aria-label="Fechar modal">&times;</button>

        <div class="cliente-detalhes-body pet-detalhes-body">
            <div class="cliente-detalhes-user">
                <div class="cliente-detalhes-avatar" aria-hidden="true">
                    <i data-lucide="calendar-clock"></i>
                </div>
                <div class="cliente-detalhes-identity">
                    <h4 id="agendamentoDetalhesPet">Pet</h4>
                    <p id="agendamentoDetalhesTutor">Tutor</p>
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-section">
                <h5>Informações do atendimento</h5>
                <div class="pet-detalhes-grid">
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Serviço</span>
                        <strong id="agendamentoDetalhesServico">Não informado</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Valor</span>
                        <strong id="agendamentoDetalhesValor">Não informado</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Data</span>
                        <strong id="agendamentoDetalhesData">Não informada</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Horário</span>
                        <strong id="agendamentoDetalhesHora">Não informado</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Status</span>
                        <strong id="agendamentoDetalhesStatus">Não informado</strong>
                    </div>
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-section">
                <h5>Observações</h5>
                <div class="cliente-detalhes-texto" id="agendamentoDetalhesObservacoes">Sem observações.</div>
            </div>

            <div class="cliente-detalhes-actions">
                <button type="button" class="cliente-detalhes-fechar" id="agendamentoDetalhesFechar">Fechar</button>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($agendamentoModalAberto)): ?>
    <?php
    $agendamento = $agendamentoFormulario ?? [];
    $modalAgendamento = true;
    require __DIR__ . '/form.php';
    ?>
<?php endif; ?>

<script src="/assets/js/agendamentos-filtro.js"></script>

<script nonce="<?= \Core\Csp::nonce() ?>">
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('[data-agendamento-status-select]').forEach((select) => {
            select.addEventListener('change', () => select.form?.requestSubmit());
        });

        const backdrop = document.getElementById('agendamentoDetalhesBackdrop');
        const closeButton = document.getElementById('agendamentoDetalhesClose');
        const closeAction = document.getElementById('agendamentoDetalhesFechar');
        const fields = {
            pet: document.getElementById('agendamentoDetalhesPet'),
            tutor: document.getElementById('agendamentoDetalhesTutor'),
            servico: document.getElementById('agendamentoDetalhesServico'),
            valor: document.getElementById('agendamentoDetalhesValor'),
            data: document.getElementById('agendamentoDetalhesData'),
            hora: document.getElementById('agendamentoDetalhesHora'),
            status: document.getElementById('agendamentoDetalhesStatus'),
            observacoes: document.getElementById('agendamentoDetalhesObservacoes'),
        };

        if (!backdrop) return;

        const closeModal = () => {
            backdrop.classList.add('hidden');
            backdrop.setAttribute('aria-hidden', 'true');
        };

        document.querySelectorAll('[data-agendamento-detalhes]').forEach((button) => {
            button.addEventListener('click', () => {
                fields.pet.textContent = button.dataset.pet || 'Pet';
                fields.tutor.textContent = button.dataset.tutor || 'Tutor não informado';
                fields.servico.textContent = button.dataset.servico || 'Não informado';
                fields.valor.textContent = button.dataset.valor || 'Não informado';
                fields.data.textContent = button.dataset.data || 'Não informada';
                fields.hora.textContent = button.dataset.hora || 'Não informado';
                fields.status.textContent = button.dataset.status || 'Não informado';
                fields.observacoes.textContent = button.dataset.observacoes || 'Sem observações.';
                backdrop.classList.remove('hidden');
                backdrop.setAttribute('aria-hidden', 'false');
            });
        });

        closeButton?.addEventListener('click', closeModal);
        closeAction?.addEventListener('click', closeModal);
        backdrop.addEventListener('click', (event) => {
            if (event.target === backdrop) closeModal();
        });
        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && backdrop.getAttribute('aria-hidden') === 'false') {
                closeModal();
            }
        });
    });
</script>

<?php if (!empty($agendamentoModalAberto)): ?>
    <script nonce="<?= \Core\Csp::nonce() ?>">
        document.addEventListener('DOMContentLoaded', function () {
            const backdrop = document.getElementById('agendamentoModalBackdrop');
            const closeButton = document.getElementById('agendamentoModalClose');
            const cancelButton = document.getElementById('agendamentoModalCancel');
            const errorNotice = document.getElementById('agendamentoErroAviso');

            if (!backdrop) return;

            const closeModal = () => {
                backdrop.classList.add('hidden');
                backdrop.setAttribute('aria-hidden', 'true');
            };

            closeButton?.addEventListener('click', closeModal);
            cancelButton?.addEventListener('click', closeModal);
            backdrop.addEventListener('click', (event) => {
                if (event.target === backdrop) closeModal();
            });
            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') closeModal();
            });

            if (errorNotice) {
                window.setTimeout(() => {
                    errorNotice.classList.add('agenda-aviso--saindo');
                    window.setTimeout(() => errorNotice.remove(), 180);
                }, 3000);
            }
        });
    </script>
<?php endif; ?>
