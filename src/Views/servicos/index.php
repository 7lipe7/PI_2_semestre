<?php
/** Catálogo de serviços. Variáveis: $servicos, $aviso (opcional) */
$aviso = $aviso ?? null;
$erro = $erro ?? null;
$servicoForm = $servicoForm ?? [];
$servicoEditForm = $servicoEditForm ?? [];
$servicoModalAberto = $servicoModalAberto ?? false;
$servicoEditModalAberto = $servicoEditModalAberto ?? false;
$totalServicos = count($servicos ?? []);
$labelServicos = $totalServicos === 1 ? 'serviço' : 'serviços';
?>
<div class="clientes-page servicos-page">
    <div class="servicos-intro">
        <div>
            <p class="servicos-eyebrow">Catálogo de serviços</p>
            <h2>Serviços oferecidos</h2>
            <p class="servicos-description">Organize os cuidados disponíveis para os pets dos seus clientes.</p>
        </div>
        <div class="clientes-quantidade" aria-live="polite">
            <span class="clientes-quantidade-icon" aria-hidden="true">
                <i data-lucide="scissors"></i>
            </span>
            <strong><?= $totalServicos ?></strong>
            <span><?= $labelServicos ?> cadastrados</span>
        </div>
    </div>

    <?php if ($aviso !== null): ?>
        <p class="form-erro"><strong><?= \Core\e($aviso) ?></strong></p>
    <?php endif; ?>

    <?php if (empty($servicos)): ?>
        <div class="clientes-empty">
            <p>Nenhum serviço cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="servicos-list" role="list">
            <div class="servicos-list__header" aria-hidden="true">
                <span>Serviço</span>
                <span>Valor</span>
                <span>Situação</span>
                <span>Ações</span>
            </div>
            <?php foreach ($servicos as $s): ?>
                <article class="servico-row" role="listitem">
                    <div class="servico-row__identity">
                        <div class="servico-row__name">
                            <h3><?= \Core\e($s['nome']) ?></h3>
                            <p><?= \Core\e(trim((string) ($s['descricao'] ?? '')) !== '' ? (string) $s['descricao'] : 'Sem descrição' ) ?></p>
                        </div>
                    </div>

                    <div class="servico-row__price">
                        <span>Valor</span>
                        <strong>R$ <?= number_format((float) $s['preco'], 2, ',', '.') ?></strong>
                    </div>

                    <div class="servico-row__status">
                        <?php if ((int) $s['ativo'] === 1): ?>
                            <span class="servico-status servico-status--ativo">Ativo</span>
                        <?php else: ?>
                            <span class="servico-status servico-status--inativo">Inativo</span>
                        <?php endif; ?>
                    </div>

                    <div class="servico-card__actions">
                        <button
                            type="button"
                            class="servico-card__action servico-card__action--edit"
                            data-servico-editar
                            data-id="<?= (int) $s['id'] ?>"
                            data-nome="<?= \Core\e($s['nome']) ?>"
                            data-preco="<?= number_format((float) $s['preco'], 2, ',', '.') ?>"
                            data-descricao="<?= \Core\e((string) ($s['descricao'] ?? '')) ?>"
                            data-ativo="<?= (int) ($s['ativo'] ?? 1) ?>"
                            aria-label="Editar serviço <?= \Core\e($s['nome']) ?>"
                        >
                            <i data-lucide="pencil"></i>
                        </button>

                        <form method="post"
                              action="/servicos/<?= (int) $s['id'] ?>/excluir"
                              class="d-inline"
                              data-confirm-title="<?= \Core\e('Você tem certeza que deseja deletar ' . $s['nome'] . '?') ?>"
                              data-confirm-message="Se este serviço já estiver vinculado a agendamentos, ele será apenas inativado.">
                            <?= \Core\Csrf::campo() ?>
                            <button type="submit" class="servico-card__action servico-card__action--delete" aria-label="Excluir serviço <?= \Core\e($s['nome']) ?>">
                                <i data-lucide="trash-2"></i>
                            </button>
                        </form>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php if ($erro !== null): ?>
    <div class="app-notice app-notice--error app-notice--modal-error" id="appNotice" role="alert" aria-live="assertive">
        <i data-lucide="circle-alert" aria-hidden="true"></i>
        <span><?= \Core\e($erro) ?></span>
        <button type="button" class="app-notice__close" aria-label="Fechar notificação">
            <i data-lucide="x" aria-hidden="true"></i>
        </button>
    </div>
<?php endif; ?>

<div class="cliente-modal-backdrop <?= $servicoModalAberto ? '' : 'hidden' ?>" id="servicoModalBackdrop" aria-hidden="<?= $servicoModalAberto ? 'false' : 'true' ?>">
    <div class="cliente-modal servico-modal" role="dialog" aria-modal="true" aria-labelledby="servicoModalTitulo">
        <div class="agendamento-form-card">
            <button type="button" class="cliente-modal__close cliente-modal__close--inside" id="servicoModalClose" aria-label="Fechar modal">&times;</button>

            <div class="agendamento-form-header">
                <div class="agendamento-form-icon" aria-hidden="true">
                    <i data-lucide="scissors"></i>
                </div>
                <div>
                    <h1>Novo serviço</h1>
                    <p>Cadastre um item do catálogo</p>
                </div>
            </div>

            <form method="post" action="/servicos" class="agendamento-form" id="servicoForm">
                <?= \Core\Csrf::campo() ?>
                <div class="campo">
                    <label for="servicoNome">Nome</label>
                    <input id="servicoNome" type="text" name="nome" value="<?= \Core\e($servicoForm['nome'] ?? '') ?>" placeholder="Ex.: Banho com hidratação" required>
                </div>

                <div class="campo">
                    <label for="servicoPreco">Valor (R$)</label>
                    <input id="servicoPreco" type="text" name="preco" inputmode="decimal" value="<?= \Core\e($servicoForm['preco'] ?? '') ?>" placeholder="Ex.: 45,00" required>
                </div>

                <div class="campo campo--full">
                    <label for="servicoDescricao">Descrição</label>
                    <textarea id="servicoDescricao" name="descricao" rows="3" placeholder="O que está incluído no serviço..."><?= \Core\e($servicoForm['descricao'] ?? '') ?></textarea>
                </div>

                <div class="campo">
                    <label for="servicoAtivo">Situação</label>
                    <select id="servicoAtivo" name="ativo">
                        <option value="1" <?= (int) ($servicoForm['ativo'] ?? 1) === 1 ? 'selected' : '' ?>>Ativo (aparece na marcação)</option>
                        <option value="0" <?= (int) ($servicoForm['ativo'] ?? 1) === 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="agendamento-form__actions">
                    <button type="submit" class="botao" style="width: 100%; border-radius: 8px;">
                        <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                        <span>Cadastrar serviço</span>
                    </button>
                    <button type="button" class="btn btn-light agendamento-form__cancel" id="servicoCancelBtn">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="cliente-modal-backdrop <?= $servicoEditModalAberto ? '' : 'hidden' ?>" id="servicoEditModalBackdrop" aria-hidden="<?= $servicoEditModalAberto ? 'false' : 'true' ?>">
    <div class="cliente-modal servico-modal" role="dialog" aria-modal="true" aria-labelledby="servicoEditModalTitulo">
        <div class="agendamento-form-card">
            <button type="button" class="cliente-modal__close cliente-modal__close--inside" id="servicoEditModalClose" aria-label="Fechar modal">&times;</button>

            <div class="agendamento-form-header">
                <div class="agendamento-form-icon" aria-hidden="true">
                    <i data-lucide="scissors"></i>
                </div>
                <div>
                    <h1>Editar serviço</h1>
                    <p>Atualize os dados do serviço</p>
                </div>
            </div>

            <form method="post" action="/servicos/<?= (int) ($servicoEditForm['id'] ?? 0) ?>" class="agendamento-form" id="servicoEditForm">
                <?= \Core\Csrf::campo() ?>
                <div class="campo">
                    <label for="servicoEditNome">Nome</label>
                    <input id="servicoEditNome" type="text" name="nome" value="<?= \Core\e($servicoEditForm['nome'] ?? '') ?>" placeholder="Ex.: Banho com hidratação" required>
                </div>

                <div class="campo">
                    <label for="servicoEditPreco">Valor (R$)</label>
                    <input id="servicoEditPreco" type="text" name="preco" inputmode="decimal" value="<?= \Core\e($servicoEditForm['preco'] ?? '') ?>" placeholder="Ex.: 45,00" required>
                </div>

                <div class="campo campo--full">
                    <label for="servicoEditDescricao">Descrição</label>
                    <textarea id="servicoEditDescricao" name="descricao" rows="3" placeholder="O que está incluído no serviço..."><?= \Core\e($servicoEditForm['descricao'] ?? '') ?></textarea>
                </div>

                <div class="campo">
                    <label for="servicoEditAtivo">Situação</label>
                    <select id="servicoEditAtivo" name="ativo">
                        <option value="1" <?= (int) ($servicoEditForm['ativo'] ?? 1) === 1 ? 'selected' : '' ?>>Ativo (aparece na marcação)</option>
                        <option value="0" <?= (int) ($servicoEditForm['ativo'] ?? 1) === 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="agendamento-form__actions">
                    <button type="submit" class="botao" style="width: 100%; border-radius: 8px;">
                        <i data-lucide="save" style="width: 16px; height: 16px;"></i>
                        <span>Atualizar serviço</span>
                    </button>
                    <button type="button" class="btn btn-light agendamento-form__cancel" id="servicoEditCancelBtn">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script nonce="<?= \Core\Csp::nonce() ?>">
    document.addEventListener('DOMContentLoaded', function () {
        const servicoModalBackdrop = document.getElementById('servicoModalBackdrop');
        const servicoModalClose = document.getElementById('servicoModalClose');
        const servicoCancelBtn = document.getElementById('servicoCancelBtn');
        const servicoOpenButtons = document.querySelectorAll('[data-servico-modal-open]');

        const editModalBackdrop = document.getElementById('servicoEditModalBackdrop');
        const editModalClose = document.getElementById('servicoEditModalClose');
        const editCancelBtn = document.getElementById('servicoEditCancelBtn');
        const editForm = document.getElementById('servicoEditForm');
        const editNome = document.getElementById('servicoEditNome');
        const editPreco = document.getElementById('servicoEditPreco');
        const editDescricao = document.getElementById('servicoEditDescricao');
        const editAtivo = document.getElementById('servicoEditAtivo');
        const editButtons = document.querySelectorAll('[data-servico-editar]');

        const openServicoModal = () => {
            if (!servicoModalBackdrop) return;
            servicoModalBackdrop.classList.remove('hidden');
            servicoModalBackdrop.setAttribute('aria-hidden', 'false');
        };

        const closeServicoModal = () => {
            if (!servicoModalBackdrop) return;
            servicoModalBackdrop.classList.add('hidden');
            servicoModalBackdrop.setAttribute('aria-hidden', 'true');
            const serviceForm = document.getElementById('servicoForm');
            if (serviceForm) {
                serviceForm.reset();
                serviceForm.querySelectorAll('input:not([type="hidden"]), textarea').forEach((field) => {
                    field.value = '';
                });
                const ativo = document.getElementById('servicoAtivo');
                if (ativo) {
                    ativo.value = '1';
                }
            }
        };

        const openServicoEditModal = (button) => {
            if (!editModalBackdrop || !editForm || !editNome || !editPreco || !editDescricao || !editAtivo) return;

            const id = button.dataset.id;
            editNome.value = button.dataset.nome || '';
            editPreco.value = button.dataset.preco || '';
            editDescricao.value = button.dataset.descricao || '';
            editAtivo.value = button.dataset.ativo || '1';
            editForm.action = '/servicos/' + id;
            editModalBackdrop.classList.remove('hidden');
            editModalBackdrop.setAttribute('aria-hidden', 'false');
        };

        const closeServicoEditModal = () => {
            if (!editModalBackdrop) return;
            editModalBackdrop.classList.add('hidden');
            editModalBackdrop.setAttribute('aria-hidden', 'true');
            if (editForm) {
                editForm.reset();
            }
        };

        servicoOpenButtons.forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                openServicoModal();
            });
        });

        editButtons.forEach((button) => {
            button.addEventListener('click', function (event) {
                event.preventDefault();
                openServicoEditModal(button);
            });
        });

        if (servicoModalClose) {
            servicoModalClose.addEventListener('click', closeServicoModal);
        }

        if (servicoCancelBtn) {
            servicoCancelBtn.addEventListener('click', closeServicoModal);
        }

        if (editModalClose) {
            editModalClose.addEventListener('click', closeServicoEditModal);
        }

        if (editCancelBtn) {
            editCancelBtn.addEventListener('click', closeServicoEditModal);
        }

        if (servicoModalBackdrop) {
            servicoModalBackdrop.addEventListener('click', function (event) {
                if (event.target === servicoModalBackdrop) {
                    closeServicoModal();
                }
            });
        }

        if (editModalBackdrop) {
            editModalBackdrop.addEventListener('click', function (event) {
                if (event.target === editModalBackdrop) {
                    closeServicoEditModal();
                }
            });
        }

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                if (servicoModalBackdrop && !servicoModalBackdrop.classList.contains('hidden')) {
                    closeServicoModal();
                }
                if (editModalBackdrop && !editModalBackdrop.classList.contains('hidden')) {
                    closeServicoEditModal();
                }
            }
        });
    });
</script>
