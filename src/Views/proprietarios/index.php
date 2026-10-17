<?php
/** Lista de clientes com contagem de visitas. */
$busca = trim((string) ($busca ?? ''));
$totalClientes = count($clientes ?? []);
$labelClientes = $totalClientes === 1 ? 'cliente' : 'clientes';
$editando = !empty($formCliente) && !empty($formCliente['id']);
$clienteForm = $formCliente ?? ['id' => null, 'nome' => '', 'celular' => '', 'observacoes' => ''];
?>
<div class="clientes-page">
    <div class="clientes-toolbar d-flex align-items-center justify-content-start gap-3 flex-wrap" style="margin-left: 0; padding-left: 0;">
        <div class="clientes-search-wrap flex-grow-1">
            <form method="get" action="/clientes" class="clientes-search-form d-flex align-items-center">
                <span class="clientes-search-icon" aria-hidden="true">
                    <i data-lucide="search"></i>
                </span>
                <input
                    type="search"
                    name="busca"
                    value="<?= \Core\e($busca) ?>"
                    class="clientes-search-input"
                    placeholder="Buscar o tutor..."
                >
            </form>
        </div>

        <div class="clientes-quantidade" aria-live="polite">
            <span class="clientes-quantidade-icon" aria-hidden="true">
                <i data-lucide="users"></i>
            </span>
            <span><?= $totalClientes ?></span>
            <span><?= $labelClientes ?></span>
        </div>
    </div>

    <?php if (empty($clientes)): ?>
        <div class="clientes-empty">
            <p>Nenhum cliente encontrado.</p>
        </div>
    <?php else: ?>
        <div class="clientes-grid" id="clientesGrid">
            <?php foreach ($clientes as $c): ?>
                <?php
                $visitas = (int) ($c['visitas'] ?? 0);
                $labelVisitas = $visitas === 1 ? 'visita' : 'visitas';
                $totalGasto = (float) ($c['total_gasto'] ?? 0);
                $telefone = preg_replace('/\D/', '', (string) ($c['celular'] ?? ''));

                if (strlen($telefone) === 11) {
                    $telefoneFormatado = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
                } elseif (strlen($telefone) === 10) {
                    $telefoneFormatado = '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
                } else {
                    $telefoneFormatado = (string) ($c['celular'] ?? '');
                }
                ?>
                <article class="cliente-card" data-nome="<?= \Core\e(strtolower((string) $c['nome'])) ?>">
                    <div class="cliente-card__header">
                        <div class="cliente-card__avatar" aria-hidden="true">
                            <i data-lucide="user-round"></i>
                        </div>

                        <div class="cliente-card__title-wrap">
                            <h2><?= \Core\e($c['nome']) ?></h2>
                        </div>

                        <div class="cliente-card__actions" aria-label="Ações do cliente">
                            <a href="/clientes/<?= $c['id'] ?>/editar" class="cliente-card__action cliente-card__action--edit" aria-label="Editar cliente">
                                <i data-lucide="pencil"></i>
                            </a>

                            <form method="post" action="/clientes/<?= $c['id'] ?>/excluir"
                                  class="cliente-card__delete-form"
                                  data-confirm="Excluir <?= \Core\e($c['nome']) ?>?">
                                <?= \Core\Csrf::campo() ?>
                                <button type="submit" class="cliente-card__action cliente-card__action--delete" aria-label="Excluir cliente">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="cliente-card__info">
                        <div class="cliente-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="phone"></i>
                            </span>
                            <span><?= \Core\e($telefoneFormatado) ?></span>
                        </div>
                        
                        <?php
                        $descricao = trim((string) ($c['observacoes'] ?? ''));
                        $textoDescricao = $descricao !== '' ? $descricao : 'Nenhuma descrição informada';
                        $pets = trim((string) ($c['pets'] ?? ''));
                        $textoPets = $pets !== '' ? $pets : 'Nenhum pet cadastrado';
                        ?>

                        <div class="cliente-card__pets">
                            <span class="cliente-card__pets-icon" aria-hidden="true">
                                <i data-lucide="dog"></i>
                            </span>
                            <span><?= \Core\e($textoPets) ?></span>
                        </div>

                        <div class="cliente-card__description">
                            <span class="cliente-card__description-icon" aria-hidden="true">
                                <i data-lucide="message-square-text"></i>
                            </span>
                            <span><?= \Core\e($textoDescricao) ?></span>
                        </div>

                        <button
                            type="button"
                            class="cliente-card__details-btn"
                            data-id="<?= (int) ($c['id'] ?? 0) ?>"
                            data-nome="<?= \Core\e($c['nome'] ?? '') ?>"
                            data-celular="<?= \Core\e($telefoneFormatado) ?>"
                            data-descricao="<?= \Core\e($descricao !== '' ? $descricao : 'Nenhuma descrição informada.') ?>"
                            data-visitas="<?= (int) ($c['visitas'] ?? 0) ?>"
                        >
                            <span>Ver detalhes</span>
                            <i data-lucide="chevron-right" aria-hidden="true"></i>
                        </button>

                    </div>

                    <div class="cliente-card__footer">
                        <span><?= $visitas ?> <?= $labelVisitas ?></span>
                        <span class="cliente-card__total">R$ <?= number_format($totalGasto, 2, ',', '.') ?> total</span>
                    </div>

                </article>
            <?php endforeach; ?>
        </div>

        <div class="clientes-empty clientes-empty--filter hidden" id="clientesEmptyFilter">
            <p>Nenhum cliente encontrado para essa busca.</p>
        </div>
    <?php endif; ?>
</div>

<?php if (!empty($modalAberto)): ?>
    <div class="cliente-modal-backdrop" aria-hidden="false">
        <div class="cliente-modal cliente-modal--service-style" role="dialog" aria-modal="true" aria-labelledby="clienteModalTitulo">
            <div class="agendamento-form-card">
                <button type="button" class="cliente-modal__close cliente-modal__close--inside" id="clienteModalClose" aria-label="Fechar modal">
                    <i data-lucide="x"></i>
                </button>

                <div class="agendamento-form-header">
                    <div class="agendamento-form-icon" aria-hidden="true">
                        <i data-lucide="users"></i>
                    </div>
                    <div>
                        <h1 id="clienteModalTitulo"><?= $editando ? 'Editar cliente' : 'Novo cliente' ?></h1>
                        <p><?= $editando ? 'Atualize os dados do cliente' : 'Cadastre um novo tutor' ?></p>
                    </div>
                </div>

                <?php if (!empty($erro)): ?>
                    <div class="cliente-modal__erro">
                        <strong><?= \Core\e($erro) ?></strong>
                    </div>
                <?php endif; ?>

                <form method="post" action="<?= $editando ? '/clientes/' . $clienteForm['id'] : '/clientes' ?>" class="agendamento-form cliente-modal__form">
                    <?= \Core\Csrf::campo() ?>
                    <div class="campo">
                        <label for="nome">Nome <span class="campo-required">*</span></label>
                        <input
                            id="nome"
                            type="text"
                            name="nome"
                            value="<?= \Core\e($clienteForm['nome'] ?? '') ?>"
                            placeholder="Ex: Mariana Souza"
                            required
                        >
                    </div>

                    <div class="campo">
                        <label for="celular">Telefone / WhatsApp <span class="campo-required">*</span></label>
                        <input
                            id="celular"
                            type="tel"
                            name="celular"
                            value="<?= \Core\e($clienteForm['celular'] ?? '') ?>"
                            placeholder="(11) 99999-9999"
                            required
                        >
                    </div>

                    <div class="campo campo--full">
                        <label for="observacoes">Descrição</label>
                        <textarea
                            id="observacoes"
                            name="observacoes"
                            rows="4"
                            placeholder="Algumas observações sobre o cliente..."
                        ><?= \Core\e($clienteForm['observacoes'] ?? '') ?></textarea>
                    </div>

                    <div class="agendamento-form__actions cliente-modal__actions">
                        <button type="submit" class="botao cliente-modal__submit" disabled>
                            <i data-lucide="save" aria-hidden="true"></i>
                            <span><?= $editando ? 'Atualizar cliente' : 'Cadastrar cliente' ?></span>
                        </button>
                        <button type="button" class="btn btn-light agendamento-form__cancel cliente-modal__cancel" id="clienteModalCancel">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="cliente-detalhes-backdrop hidden" id="clienteDetalhesBackdrop" aria-hidden="true">
    <div class="cliente-detalhes-modal" role="dialog" aria-modal="true" aria-labelledby="clienteDetalhesTitulo">
        <button type="button" class="cliente-detalhes-close" id="clienteDetalhesClose" aria-label="Fechar modal">&times;</button>

        <div class="cliente-detalhes-header">
            <h3 id="clienteDetalhesTitulo">Detalhes do cliente</h3>
        </div>

        <div class="cliente-detalhes-body">
            <div class="cliente-detalhes-user">
                <div class="cliente-detalhes-avatar" aria-hidden="true">
                    <i data-lucide="user-round"></i>
                </div>
                <div class="cliente-detalhes-identity">
                    <h4 id="clienteDetalhesNome">Nome do cliente</h4>
                    <p id="clienteDetalhesTelefone">(00) 00000-0000</p>
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-section">
                <h5>Descrição</h5>
                <div class="cliente-detalhes-texto" id="clienteDetalhesDescricao">
                    Sem descrição cadastrada.
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-visitas" id="clienteDetalhesVisitas">
                0 visita ao banho e tosa<span>0 visita ao banho e tosa</span>
            </div>

            <div class="cliente-detalhes-actions">
                <button type="button" class="cliente-detalhes-fechar" id="clienteDetalhesFechar">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script nonce="<?= \Core\Csp::nonce() ?>">
    document.addEventListener('DOMContentLoaded', function () {
        const modalBackdrop = document.querySelector('.cliente-modal-backdrop');
        const modalCloseBtn = document.getElementById('clienteModalClose');
        const modalCancelBtn = document.getElementById('clienteModalCancel');

        const closeClienteModal = () => {
            if (!modalBackdrop) return;
            modalBackdrop.classList.add('hidden');
            modalBackdrop.setAttribute('aria-hidden', 'true');
        };

        if (modalCloseBtn) {
            modalCloseBtn.addEventListener('click', closeClienteModal);
        }

        if (modalCancelBtn) {
            modalCancelBtn.addEventListener('click', closeClienteModal);
        }

        if (modalBackdrop) {
            modalBackdrop.addEventListener('click', (event) => {
                if (event.target === modalBackdrop) {
                    closeClienteModal();
                }
            });
        }

        const form = document.querySelector('.cliente-modal__form');
        const submitBtn = document.querySelector('.cliente-modal__submit');

        if (form && submitBtn) {
            const nomeInput = document.getElementById('nome');
            const celularInput = document.getElementById('celular');

            const formatarTelefone = (valor) => {
                const apenasDigitos = valor.replace(/\D/g, '').slice(0, 11);

                if (apenasDigitos.length <= 2) {
                    return apenasDigitos;
                }

                if (apenasDigitos.length <= 6) {
                    return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2)}`;
                }

                if (apenasDigitos.length <= 10) {
                    return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2, 6)}-${apenasDigitos.slice(6)}`;
                }

                return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2, 7)}-${apenasDigitos.slice(7)}`;
            };

            const validarFormulario = () => {
                const nomeOk = nomeInput.value.trim() !== '';
                const celularOk = celularInput.value.trim() !== '';
                submitBtn.disabled = !(nomeOk && celularOk);
            };

            celularInput.addEventListener('input', function () {
                this.value = formatarTelefone(this.value);
                validarFormulario();
            });

            nomeInput.addEventListener('input', validarFormulario);
            validarFormulario();
        }

        const searchInput = document.querySelector('.clientes-search-input');
        const cards = document.querySelectorAll('.cliente-card');
        const emptyFilter = document.getElementById('clientesEmptyFilter');
        const detailsBackdrop = document.getElementById('clienteDetalhesBackdrop');
        const detailsCloseBtn = document.getElementById('clienteDetalhesClose');
        const detailsCloseAction = document.getElementById('clienteDetalhesFechar');

        const detailsNome = document.getElementById('clienteDetalhesNome');
        const detailsTelefone = document.getElementById('clienteDetalhesTelefone');
        const detailsDescricao = document.getElementById('clienteDetalhesDescricao');
        const detailsVisitas = document.getElementById('clienteDetalhesVisitas');

        const closeDetailsModal = () => {
            if (!detailsBackdrop) return;
            detailsBackdrop.classList.add('hidden');
            detailsBackdrop.setAttribute('aria-hidden', 'true');
        };

        const openDetailsModal = (button) => {
            if (!detailsBackdrop || !detailsNome || !detailsTelefone || !detailsDescricao || !detailsVisitas) return;

            detailsNome.textContent = button.dataset.nome || 'Nome do cliente';
            detailsTelefone.textContent = button.dataset.celular || '(00) 00000-0000';
            detailsDescricao.textContent = button.dataset.descricao || 'Sem descrição cadastrada.';

            const visitas = Number(button.dataset.visitas || 0);
            detailsVisitas.textContent = `${visitas} ${visitas === 1 ? 'visita ao banho e tosa' : 'visitas ao banho e tosa'}`;

            detailsBackdrop.classList.remove('hidden');
            detailsBackdrop.setAttribute('aria-hidden', 'false');
        };

        document.querySelectorAll('.cliente-card__details-btn').forEach((button) => {
            button.addEventListener('click', () => openDetailsModal(button));
        });

        if (detailsCloseBtn) {
            detailsCloseBtn.addEventListener('click', closeDetailsModal);
        }

        if (detailsCloseAction) {
            detailsCloseAction.addEventListener('click', closeDetailsModal);
        }

        if (detailsBackdrop) {
            detailsBackdrop.addEventListener('click', (event) => {
                if (event.target === detailsBackdrop) {
                    closeDetailsModal();
                }
            });
        }

        if (!searchInput || cards.length === 0) {
            return;
        }

        const applyFilter = () => {
            const termo = searchInput.value.trim().toLowerCase();
            let totalVisivel = 0;

            cards.forEach((card) => {
                const nome = (card.dataset.nome || '').toLowerCase();
                const match = termo === '' || nome.includes(termo);

                card.style.display = match ? '' : 'none';
                if (match) totalVisivel++;
            });

            if (emptyFilter) {
                emptyFilter.classList.toggle('hidden', totalVisivel !== 0);
            }
        };

        searchInput.addEventListener('input', applyFilter);
        applyFilter();
    });
</script>

