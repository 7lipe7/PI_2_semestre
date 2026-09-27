<?php

/** Lista de agendamentos. Variável: $agendamentos (com data, hora, status, cachorro_nome...) */
$agendamentos = $agendamentos ?? [];
$quantidadeAgendados = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? 'agendado') === 'agendado'));
$quantidadeAndamento = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? '') === 'andamento'));
$quantidadeConcluidos = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? '') === 'concluido'));
$quantidadeCancelados = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? '') === 'cancelado'));
$quantidadeTotal = count($agendamentos);
// Receita = apenas serviços já concluídos (o card deve refletir o que já foi faturado)
$receitaConcluida = array_sum(array_map(
    fn(array $a): float => ($a['status'] ?? '') === 'concluido' ? (float) ($a['servico_preco'] ?? 0) : 0.0,
    $agendamentos
));
?>
<div class="clientes-page agendamentos-page">
    <div class="agendamentos-resumo" aria-label="Resumo dos agendamentos">
        <div class="agendamentos-resumo__card agendamentos-resumo__card--agendado">
            <span class="agendamentos-resumo__label">Agendados</span>
            <strong data-status-count="agendado"><?= $quantidadeAgendados ?></strong>
        </div>
        <div class="agendamentos-resumo__card agendamentos-resumo__card--andamento">
            <span class="agendamentos-resumo__label">Em andamento</span>
            <strong data-status-count="andamento"><?= $quantidadeAndamento ?></strong>
        </div>
        <div class="agendamentos-resumo__card agendamentos-resumo__card--concluido">
            <span class="agendamentos-resumo__label">Concluídos</span>
            <strong data-status-count="concluido"><?= $quantidadeConcluidos ?></strong>
        </div>
        <div class="agendamentos-resumo__card agendamentos-resumo__card--receita">
            <span class="agendamentos-resumo__label">Receita (concluídos)</span>
            <strong>R$ <?= number_format($receitaConcluida, 2, ',', '.') ?></strong>
        </div>
    </div>

    <div class="agendamentos-filtros" role="group" aria-label="Filtrar agendamentos">
        <button type="button" class="agendamentos-filtro agendamentos-filtro--ativo" data-agendamento-filtro="todos" aria-pressed="true">Todas <span><?= $quantidadeTotal ?></span></button>
        <button type="button" class="agendamentos-filtro" data-agendamento-filtro="agendado" aria-pressed="false">Agendados <span><?= $quantidadeAgendados ?></span></button>
        <button type="button" class="agendamentos-filtro" data-agendamento-filtro="andamento" aria-pressed="false">Em andamento <span><?= $quantidadeAndamento ?></span></button>
        <button type="button" class="agendamentos-filtro" data-agendamento-filtro="concluido" aria-pressed="false">Concluídos <span><?= $quantidadeConcluidos ?></span></button>
        <button type="button" class="agendamentos-filtro" data-agendamento-filtro="cancelado" aria-pressed="false">Cancelados <span><?= $quantidadeCancelados ?></span></button>
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
                $statusClass = 'agendamento-card__badge--' . $status;
                $statusIcon = [
                    'agendado' => 'calendar-days',
                    'andamento' => 'clock-3',
                    'concluido' => 'circle-check',
                    'cancelado' => 'circle-x',
                ][$status] ?? 'calendar-days';
                $servicoNome = strtolower(trim((string) ($a['servico_nome'] ?? '')));
                $servicoIcon = match ($servicoNome) {
                    'banho' => 'bath',
                    'tosa' => 'scissors',
                    'banho e tosa' => 'paw-print',
                    default => 'bone',
                };
                $servicoClass = match ($servicoNome) {
                    'banho' => 'agendamento-card__icon--banho',
                    'tosa' => 'agendamento-card__icon--tosa',
                    default => 'agendamento-card__icon--banho-e-tosa',
                };
                ?>
                <article class="agendamento-card" data-agendamento-status="<?= \Core\e($status) ?>">
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

                        <form method="post" action="/agendamentos/<?= (int) $a['id'] ?>/status" class="agendamento-card__status-form">
                            <label class="visually-hidden" for="status-<?= (int) $a['id'] ?>">Status do agendamento</label>
                            <select
                                id="status-<?= (int) $a['id'] ?>"
                                name="status"
                                class="agendamento-card__status-select <?= $statusClass ?>"
                                data-status-atual="<?= \Core\e($status) ?>">
                                <option value="agendado" <?= $status === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                                <option value="andamento" <?= $status === 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                                <option value="concluido" <?= $status === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                                <option value="cancelado" <?= $status === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                            <noscript>
                                <button type="submit" class="botao">Salvar status</button>
                            </noscript>
                        </form>

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
                            onsubmit="return confirm('Excluir DEFINITIVAMENTE este agendamento? O histórico dele também será apagado.')">
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

<div class="cliente-modal-backdrop hidden" id="agendamentoModal" aria-hidden="true">
    <div class="cliente-modal agendamento-modal cliente-modal--service-style" role="dialog" aria-modal="true" aria-labelledby="agendamentoModalTitulo">
        <div class="agendamento-form-card">
            <button type="button" class="cliente-modal__close cliente-modal__close--inside" data-agendamento-modal-close aria-label="Fechar modal">&times;</button>

            <div class="agendamento-form-header">
                <div class="agendamento-form-icon" aria-hidden="true">
                    <i data-lucide="calendar-plus"></i>
                </div>
                <div>
                    <h1 id="agendamentoModalTitulo">Marcar banho e tosa</h1>
                    <p>Cadastre um novo agendamento</p>
                </div>
            </div>

            <form method="post" action="/agendamentos" class="agendamento-modal__form">
                <div class="campo">
                    <label for="modal-cachorro">Pet</label>
                    <select name="cachorro_id" id="modal-cachorro" required>
                        <option value="">Selecione o pet</option>
                        <?php foreach (($cachorros ?? []) as $cachorro): ?>
                            <option value="<?= (int) $cachorro['id'] ?>">
                                <?= \Core\e($cachorro['nome']) ?> — <?= \Core\e($cachorro['proprietario_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="modal-servico">Serviço</label>
                    <select name="servico_id" id="modal-servico" required>
                        <option value="">Selecione um serviço</option>
                        <?php foreach (($servicos ?? []) as $servico): ?>
                            <option value="<?= (int) $servico['id'] ?>">
                                <?= \Core\e($servico['nome']) ?> — R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="agendamento-modal__row">
                    <div class="campo">
                        <label for="modal-data">Data</label>
                        <input type="date" name="data" id="modal-data" required>
                    </div>
                    <div class="campo">
                        <label for="modal-hora">Horário</label>
                        <input type="time" name="hora" id="modal-hora" required>
                    </div>
                </div>

                <div class="campo campo--full">
                    <label for="modal-observacoes">Observações</label>
                    <textarea name="observacoes" id="modal-observacoes" rows="3" placeholder="Alguma observação sobre o agendamento..."></textarea>
                </div>

                <div class="cliente-modal__actions">
                    <button type="submit" class="cliente-modal__submit">
                        <i data-lucide="calendar-plus"></i>
                        <span>Marcar agendamento</span>
                    </button>
                    <button type="button" class="cliente-modal__cancel" data-agendamento-modal-close>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    const agendamentoModal = document.getElementById('agendamentoModal');
    const openAgendamentoModal = document.querySelector('[data-agendamento-modal-open]');

    const bindModalCloseHandlers = () => {
        agendamentoModal?.querySelectorAll('[data-agendamento-modal-close]').forEach((button) => {
            button.addEventListener('click', closeAgendamentoModal);
        });
    };

    const initPetCombobox = () => {
        const modalForm = agendamentoModal?.querySelector('.agendamento-form, .agendamento-modal__form');
        if (!modalForm || modalForm.dataset.petComboboxInitialized === 'true') return;

        const busca = modalForm.querySelector('#pet-busca');
        if (!busca) return;

        if (busca.tagName === 'SELECT') {
            modalForm.dataset.petComboboxInitialized = 'true';
            modalForm.addEventListener('submit', (event) => {
                if (!busca.value) {
                    event.preventDefault();
                    alert('Escolha um pet para salvar o agendamento!');
                    busca.focus();
                }
            });
            return;
        }

        const lista = modalForm.querySelector('#pet-lista');
        const hiddenId = modalForm.querySelector('#cachorro_id');
        const selecao = modalForm.querySelector('#pet-selecionado');

        if (!lista || !hiddenId || !selecao) return;

        modalForm.dataset.petComboboxInitialized = 'true';

        const CACHORROS = window.CACHORROS ?? [];

        const normalizar = (texto) => String(texto ?? '')
            .toLowerCase()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '');

        const textoItem = (c) => `${c.nome} (dono: ${c.proprietario_nome})`;
        const textoSelecao = (c) => `✔ ${c.nome} — ${c.proprietario_nome}${c.proprietario_celular ? ` · ${c.proprietario_celular}` : ''}`;

        const mostrarLista = (filtro) => {
            const termo = normalizar(filtro);
            const resultados = CACHORROS.filter((c) =>
                termo === '' ||
                normalizar(c.nome).includes(termo) ||
                normalizar(c.proprietario_nome).includes(termo)
            );

            if (resultados.length === 0) {
                lista.innerHTML = '<li class="combobox-vazio">Nenhum pet encontrado</li>';
                lista.hidden = false;
                return;
            }

            lista.innerHTML = resultados.map((c) => `
                <li class="combobox-item" data-id="${c.id}">
                    <span class="combobox-pet">${c.nome}</span>
                    <span class="combobox-dono">${c.proprietario_nome} · ${c.raca ?? 'raça não informada'}</span>
                </li>
            `).join('');

            lista.hidden = false;
        };

        busca.addEventListener('input', () => {
            hiddenId.value = '';
            selecao.textContent = '';
            mostrarLista(busca.value);
        });

        busca.addEventListener('focus', () => {
            const petAtual = CACHORROS.find((c) => String(c.id) === hiddenId.value);
            if (petAtual && busca.value.trim() === textoItem(petAtual)) {
                busca.value = '';
                hiddenId.value = '';
                selecao.textContent = '';
            }
            mostrarLista(busca.value);
        });

        lista.addEventListener('click', (event) => {
            const item = event.target.closest('.combobox-item');
            if (!item) return;

            const cachorro = CACHORROS.find((c) => String(c.id) === item.dataset.id);
            if (!cachorro) return;

            hiddenId.value = cachorro.id;
            busca.value = textoItem(cachorro);
            selecao.textContent = textoSelecao(cachorro);
            lista.hidden = true;
        });

        document.addEventListener('click', (event) => {
            if (!event.target.closest('.combobox')) {
                lista.hidden = true;
            }
        });

        modalForm.addEventListener('submit', (event) => {
            if (!hiddenId.value) {
                event.preventDefault();
                alert('Escolha um pet na busca para salvar o agendamento!');
                busca.focus();
            }
        });
    };

    const buildCreateAgendamentoModal = () => `
        <div class="agendamento-form-card">
            <button type="button" class="cliente-modal__close cliente-modal__close--inside" data-agendamento-modal-close aria-label="Fechar modal">&times;</button>

            <div class="agendamento-form-header">
                <div class="agendamento-form-icon" aria-hidden="true">
                    <i data-lucide="calendar-clock"></i>
                </div>
                <div>
                    <h1 id="agendamentoModalTitulo">Marcar banho e tosa</h1>
                    <p>Preencha os dados do agendamento</p>
                </div>
            </div>

            <form method="post" action="/agendamentos" class="agendamento-modal__form">
                <div class="campo">
                    <label for="modal-cachorro">Pet</label>
                    <select name="cachorro_id" id="modal-cachorro" required>
                        <option value="">Selecione o pet</option>
                        <?php foreach (($cachorros ?? []) as $cachorro): ?>
                            <option value="<?= (int) $cachorro['id'] ?>">
                                <?= \Core\e($cachorro['nome']) ?> — <?= \Core\e($cachorro['proprietario_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="modal-servico">Serviço</label>
                    <select name="servico_id" id="modal-servico" required>
                        <option value="">Selecione um serviço</option>
                        <?php foreach (($servicos ?? []) as $servico): ?>
                            <option value="<?= (int) $servico['id'] ?>">
                                <?= \Core\e($servico['nome']) ?> — R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="agendamento-modal__row">
                    <div class="campo">
                        <label for="modal-data">Data</label>
                        <input type="date" name="data" id="modal-data" required>
                    </div>
                    <div class="campo">
                        <label for="modal-hora">Horário</label>
                        <input type="time" name="hora" id="modal-hora" required>
                    </div>
                </div>

                <div class="campo campo--full">
                    <label for="modal-observacoes">Observações</label>
                    <textarea name="observacoes" id="modal-observacoes" rows="3" placeholder="Alguma observação sobre o agendamento..."></textarea>
                </div>

                <div class="cliente-modal__actions">
                    <button type="submit" class="cliente-modal__submit">
                        <i data-lucide="calendar-plus"></i>
                        <span>Marcar agendamento</span>
                    </button>
                    <button type="button" class="cliente-modal__cancel" data-agendamento-modal-close>
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    `;

    const resetAgendamentoModalToCreate = () => {
        const modalContent = agendamentoModal?.querySelector('.cliente-modal');
        if (!modalContent) return;

        modalContent.classList.add('agendamento-modal', 'cliente-modal--service-style');
        modalContent.innerHTML = buildCreateAgendamentoModal();
        bindModalCloseHandlers();
        initPetCombobox();
        lucide.createIcons();
    };

    const closeAgendamentoModal = () => {
        if (!agendamentoModal) return;
        agendamentoModal.classList.add('hidden');
        agendamentoModal.setAttribute('aria-hidden', 'true');
        resetAgendamentoModalToCreate();
    };

    const openEditAgendamentoModal = async (id) => {
        if (!agendamentoModal) return;

        const response = await fetch(`/agendamentos/${id}/editar`);
        const html = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const formCard = doc.querySelector('.agendamento-form-card');
        const form = formCard?.querySelector('.agendamento-form');

        if (!form) return;

        const title = formCard?.querySelector('h1')?.textContent?.trim() || 'Remarcar agendamento';
        const subtitle = 'Atualize os dados deste agendamento';
        agendamentoModal.classList.remove('hidden');
        agendamentoModal.setAttribute('aria-hidden', 'false');

        const modalContent = agendamentoModal.querySelector('.cliente-modal');
        if (!modalContent) return;

        modalContent.classList.add('agendamento-modal', 'cliente-modal--service-style');
        modalContent.innerHTML = `
            <div class="agendamento-form-card">
                <button type="button" class="cliente-modal__close cliente-modal__close--inside" data-agendamento-modal-close aria-label="Fechar modal">&times;</button>

                <div class="agendamento-form-header">
                    <div class="agendamento-form-icon" aria-hidden="true">
                        <i data-lucide="calendar-clock"></i>
                    </div>
                    <div>
                        <h1 id="agendamentoModalTitulo">${title}</h1>
                        <p>${subtitle}</p>
                    </div>
                </div>

                ${form.outerHTML}
            </div>
        `;

        bindModalCloseHandlers();
        initPetCombobox();
        lucide.createIcons();

        const firstField = agendamentoModal.querySelector('input, select, textarea');
        firstField?.focus();
    };

    openAgendamentoModal?.addEventListener('click', (event) => {
        event.preventDefault();
        resetAgendamentoModalToCreate();
        agendamentoModal?.classList.remove('hidden');
        agendamentoModal?.setAttribute('aria-hidden', 'false');
        agendamentoModal?.querySelector('select')?.focus();
    });

    document.querySelectorAll('[data-agendamento-edit]').forEach((link) => {
        link.addEventListener('click', async (event) => {
            event.preventDefault();
            const id = link.dataset.agendamentoId;
            if (!id) return;
            await openEditAgendamentoModal(Number(id));
        });
    });

    agendamentoModal?.addEventListener('click', (event) => {
        if (event.target === agendamentoModal) closeAgendamentoModal();
    });

    bindModalCloseHandlers();
    initPetCombobox();

    const agendamentoCards = document.querySelectorAll('.agendamento-card');
    const agendamentoFiltros = document.querySelectorAll('[data-agendamento-filtro]');
    const agendamentosEmptyFilter = document.getElementById('agendamentosEmptyFilter');
    let filtroAgendamentosAtual = 'todos';

    const atualizarContagemFiltros = () => {
        const contagens = { todos: agendamentoCards.length, agendado: 0, andamento: 0, concluido: 0, cancelado: 0 };
        agendamentoCards.forEach((card) => {
            const status = card.dataset.agendamentoStatus || 'agendado';
            if (Object.prototype.hasOwnProperty.call(contagens, status)) contagens[status]++;
        });
        agendamentoFiltros.forEach((filtro) => {
            const contador = filtro.querySelector('span');
            const status = filtro.dataset.agendamentoFiltro;
            if (contador && Object.prototype.hasOwnProperty.call(contagens, status)) contador.textContent = contagens[status];
        });
    };

    const aplicarFiltroAgendamentos = () => {
        let totalVisivel = 0;
        agendamentoCards.forEach((card) => {
            const visivel = filtroAgendamentosAtual === 'todos' || card.dataset.agendamentoStatus === filtroAgendamentosAtual;
            card.classList.toggle('agendamento-card--oculto', !visivel);
            if (visivel) totalVisivel++;
        });
        agendamentosEmptyFilter?.classList.toggle('hidden', totalVisivel !== 0);
    };

    agendamentoFiltros.forEach((filtro) => {
        filtro.addEventListener('click', () => {
            filtroAgendamentosAtual = filtro.dataset.agendamentoFiltro || 'todos';
            agendamentoFiltros.forEach((item) => {
                const ativo = item === filtro;
                item.classList.toggle('agendamentos-filtro--ativo', ativo);
                item.setAttribute('aria-pressed', ativo ? 'true' : 'false');
            });
            aplicarFiltroAgendamentos();
        });
    });

    atualizarContagemFiltros();
    aplicarFiltroAgendamentos();

    document.querySelectorAll('.agendamento-card').forEach((card) => {
        const select = card.querySelector('.agendamento-card__status-select');
        const badge = card.querySelector('[data-status-badge]');
        const badgeText = badge?.querySelector('span');

        if (!select || !badge || !badgeText) return;

        select.addEventListener('change', async () => {
            const labels = {
                agendado: 'Agendado',
                andamento: 'Em andamento',
                concluido: 'Concluído',
                cancelado: 'Cancelado'
            };
            const icons = {
                agendado: 'calendar-days',
                andamento: 'clock-3',
                concluido: 'circle-check',
                cancelado: 'circle-x'
            };
            const statusAnterior = select.dataset.statusAtual || 'agendado';
            const statusNovo = select.value;
            select.disabled = true;

            try {
                const response = await fetch(`/agendamentos/${card.querySelector('[data-agendamento-id]')?.dataset.agendamentoId}/status`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ status: statusNovo }),
                });
                if (!response.ok) throw new Error('Não foi possível salvar o status.');
            } catch (error) {
                select.value = statusAnterior;
                select.disabled = false;
                alert(error.message);
                return;
            }
            const contadorAnterior = document.querySelector(`[data-status-count="${statusAnterior}"]`);
            const contadorNovo = document.querySelector(`[data-status-count="${statusNovo}"]`);

            if (statusAnterior !== statusNovo) {
                if (contadorAnterior) {
                    contadorAnterior.textContent = Math.max(0, Number(contadorAnterior.textContent) - 1);
                }
                if (contadorNovo) {
                    contadorNovo.textContent = Number(contadorNovo.textContent) + 1;
                }
                select.dataset.statusAtual = statusNovo;
                card.dataset.agendamentoStatus = statusNovo;
            }

            badge.className = `agendamento-card__status-badge agendamento-card__badge--${statusNovo}`;
            badgeText.textContent = labels[statusNovo] || statusNovo;
            atualizarContagemFiltros();
            aplicarFiltroAgendamentos();
            const badgeIcon = badge.querySelector('[data-status-icon]');
            if (!badgeIcon) {
                select.disabled = false;
                return;
            }

            badgeIcon.setAttribute('data-lucide', icons[statusNovo] || 'calendar-days');
            lucide.createIcons();
            select.disabled = false;
        });
    });
</script>
