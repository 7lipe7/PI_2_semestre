<?php
/** Lista de agendamentos. Variável: $agendamentos (com data, hora, status, cachorro_nome...) */
$agendamentos = $agendamentos ?? [];
$quantidadeAgendados = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? 'agendado') === 'agendado'));
$quantidadeAndamento = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? '') === 'andamento'));
$quantidadeConcluidos = count(array_filter($agendamentos, fn(array $a): bool => ($a['status'] ?? '') === 'concluido'));
$receitaTotal = array_sum(array_map(
    fn(array $a): float => (float) ($a['servico_preco'] ?? 0),
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
            <span class="agendamentos-resumo__label">Receita total</span>
            <strong>R$ <?= number_format($receitaTotal, 2, ',', '.') ?></strong>
        </div>
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

                        <select class="agendamento-card__status-select <?= $statusClass ?>" aria-label="Status do agendamento" data-status-atual="<?= \Core\e($status) ?>">
                            <option value="agendado" <?= $status === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                            <option value="andamento" <?= $status === 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                            <option value="concluido" <?= $status === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                            <option value="cancelado" <?= $status === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                        </select>
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

<div class="cliente-modal-backdrop hidden" id="agendamentoModal" aria-hidden="true">
    <div class="cliente-modal agendamento-modal" role="dialog" aria-modal="true" aria-labelledby="agendamentoModalTitulo">
        <div class="cliente-modal__header">
            <h2 id="agendamentoModalTitulo">Marcar banho e tosa</h2>
            <button type="button" class="cliente-modal__close" data-agendamento-modal-close aria-label="Fechar modal">&times;</button>
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
                <button type="button" class="cliente-modal__cancel" data-agendamento-modal-close>Cancelar</button>
            </div>
        </form>
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

    const closeAgendamentoModal = () => {
        if (!agendamentoModal) return;
        agendamentoModal.classList.add('hidden');
        agendamentoModal.setAttribute('aria-hidden', 'true');
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
        agendamentoModal.classList.remove('hidden');
        agendamentoModal.setAttribute('aria-hidden', 'false');

        const modalContent = agendamentoModal.querySelector('.cliente-modal');
        if (!modalContent) return;

        modalContent.innerHTML = `
            <div class="cliente-modal__header">
                <h2 id="agendamentoModalTitulo">${title}</h2>
                <button type="button" class="cliente-modal__close" data-agendamento-modal-close aria-label="Fechar modal">&times;</button>
            </div>
            ${form.outerHTML}
        `;

        bindModalCloseHandlers();
        initPetCombobox();
        lucide.createIcons();

        const firstField = agendamentoModal.querySelector('input, select, textarea');
        firstField?.focus();
    };

    openAgendamentoModal?.addEventListener('click', (event) => {
        event.preventDefault();
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

    document.querySelectorAll('.agendamento-card').forEach((card) => {
        const select = card.querySelector('.agendamento-card__status-select');
        const badge = card.querySelector('[data-status-badge]');
        const badgeText = badge?.querySelector('span');

        if (!select || !badge || !badgeText) return;

        select.addEventListener('change', () => {
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
            }

            badge.className = `agendamento-card__status-badge agendamento-card__badge--${statusNovo}`;
            badgeText.textContent = labels[statusNovo] || statusNovo;
            const badgeIcon = badge.querySelector('[data-status-icon]');
            if (!badgeIcon) return;

            badgeIcon.setAttribute('data-lucide', icons[statusNovo] || 'calendar-days');
            lucide.createIcons();
        });
    });
</script>

