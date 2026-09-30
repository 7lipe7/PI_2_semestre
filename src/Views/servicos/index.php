<?php
/** Catálogo de serviços. Variáveis: $servicos, $aviso (opcional) */
$aviso = $aviso ?? null;
$totalServicos = count($servicos ?? []);
$labelServicos = $totalServicos === 1 ? 'serviço' : 'serviços';
?>
<div class="clientes-page servicos-page">
    <div class="clientes-toolbar d-flex align-items-center justify-content-start gap-3 flex-wrap" style="margin-left: 0; padding-left: 0;">
        <div class="clientes-quantidade" aria-live="polite">
            <span class="clientes-quantidade-icon" aria-hidden="true">
                <i data-lucide="scissors"></i>
            </span>
            <span><?= $totalServicos ?></span>
            <span><?= $labelServicos ?></span>
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
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th scope="col">Nome</th>
                    <th scope="col">Descrição</th>
                    <th scope="col">Valor</th>
                    <th scope="col">Situação</th>
                    <th scope="col" class="text-end">Ações</th>
                </tr>
            </thead>
        <tbody>
                <?php foreach ($servicos as $s): ?>
                    <tr>
                        <td><?= \Core\e($s['nome']) ?></td>
                        <td class="text-secondary"><?= \Core\e(trim((string) ($s['descricao'] ?? '')) !== '' ? (string) $s['descricao'] : 'Sem descrição') ?></td>
                        <td><strong>R$ <?= number_format((float) $s['preco'], 2, ',', '.') ?></strong></td>
                        <td>
                            <?php if ((int) $s['ativo'] === 1): ?>
                                <span class="badge text-bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge text-bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                data-servico-editar
                                data-id="<?= (int) $s['id'] ?>"
                                data-nome="<?= \Core\e($s['nome']) ?>"
                                data-preco="<?= number_format((float) $s['preco'], 2, ',', '.') ?>"
                                data-descricao="<?= \Core\e((string) ($s['descricao'] ?? '')) ?>"
                                data-ativo="<?= (int) ($s['ativo'] ?? 1) ?>"
                            >
                                Editar
                            </button>

                            <form method="post"
                                  action="/servicos/<?= (int) $s['id'] ?>/excluir"
                                  class="d-inline"
                                  onsubmit="return confirm(<?= json_encode(
                                      'Excluir o serviço ' . $s['nome'] . '? Se já tiver agendamento, será apenas inativado.',
                                      JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                                  ) ?>)">
                                <?= \Core\Csrf::campo() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<div class="cliente-modal-backdrop hidden" id="servicoModalBackdrop" aria-hidden="true">
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
                    <input id="servicoNome" type="text" name="nome" value="" placeholder="Ex.: Banho com hidratação" required>
                </div>

                <div class="campo">
                    <label for="servicoPreco">Valor (R$)</label>
                    <input id="servicoPreco" type="text" name="preco" inputmode="decimal" value="" placeholder="Ex.: 45,00" required>
                </div>

                <div class="campo campo--full">
                    <label for="servicoDescricao">Descrição</label>
                    <textarea id="servicoDescricao" name="descricao" rows="3" placeholder="O que está incluído no serviço..."></textarea>
                </div>

                <div class="campo">
                    <label for="servicoAtivo">Situação</label>
                    <select id="servicoAtivo" name="ativo">
                        <option value="1" selected>Ativo (aparece na marcação)</option>
                        <option value="0">Inativo</option>
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

<div class="cliente-modal-backdrop hidden" id="servicoEditModalBackdrop" aria-hidden="true">
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

            <form method="post" action="/servicos" class="agendamento-form" id="servicoEditForm">
                <?= \Core\Csrf::campo() ?>
                <div class="campo">
                    <label for="servicoEditNome">Nome</label>
                    <input id="servicoEditNome" type="text" name="nome" value="" placeholder="Ex.: Banho com hidratação" required>
                </div>

                <div class="campo">
                    <label for="servicoEditPreco">Valor (R$)</label>
                    <input id="servicoEditPreco" type="text" name="preco" inputmode="decimal" value="" placeholder="Ex.: 45,00" required>
                </div>

                <div class="campo campo--full">
                    <label for="servicoEditDescricao">Descrição</label>
                    <textarea id="servicoEditDescricao" name="descricao" rows="3" placeholder="O que está incluído no serviço..."></textarea>
                </div>

                <div class="campo">
                    <label for="servicoEditAtivo">Situação</label>
                    <select id="servicoEditAtivo" name="ativo">
                        <option value="1">Ativo (aparece na marcação)</option>
                        <option value="0">Inativo</option>
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

<script>
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
