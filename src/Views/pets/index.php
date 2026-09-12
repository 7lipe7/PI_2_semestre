<?php
/** Lista de pets em cards, mantendo o mesmo estilo da tela de clientes. */
$busca = trim((string) ($busca ?? ''));
$totalPets = count($pets ?? []);
$labelPets = $totalPets === 1 ? 'pet' : 'pets';
?>
<div class="clientes-page pets-page">
    <div class="clientes-toolbar d-flex align-items-center justify-content-start gap-3 flex-wrap" style="margin-left: 0; padding-left: 0;">
        <div class="clientes-search-wrap flex-grow-1">
            <form method="get" action="/pets" class="clientes-search-form d-flex align-items-center">
                <span class="clientes-search-icon" aria-hidden="true">
                    <i data-lucide="search"></i>
                </span>
                <input
                    type="search"
                    name="busca"
                    value="<?= \Core\e($busca) ?>"
                    class="clientes-search-input"
                    placeholder="Buscar o pet..."
                >
            </form>
        </div>

        <div class="clientes-quantidade" aria-live="polite">
            <span class="clientes-quantidade-icon" aria-hidden="true">
                <i data-lucide="dog"></i>
            </span>
            <span><?= $totalPets ?></span>
            <span><?= $labelPets ?></span>
        </div>

        <button type="button" class="btn clientes-new-btn" id="novoPetBtn">
            <i data-lucide="plus"></i>
            <span>Novo pet</span>
        </button>
    </div>

    <?php if (empty($pets)): ?>
        <div class="clientes-empty">
            <p>Nenhum pet cadastrado.</p>
        </div>
    <?php else: ?>
        <div class="clientes-grid" id="petsGrid">
            <?php foreach ($pets as $p): ?>
                <?php
                $descricao = trim((string) ($p['observacoes'] ?? ''));
                $descricaoTexto = $descricao !== '' ? $descricao : 'Sem descrição';
                $porte = trim((string) ($p['porte'] ?? ''));
                $porteTexto = $porte !== '' ? ucfirst($porte) : 'Não informado';
                $nomeTutor = trim((string) ($p['proprietario_nome'] ?? ''));
                ?>
                <article class="cliente-card pet-card"
                    data-nome="<?= \Core\e(strtolower((string) $p['nome'])) ?>"
                    data-tutor="<?= \Core\e(strtolower((string) $nomeTutor)) ?>"
                >
                    <div class="cliente-card__header">
                        <div class="cliente-card__avatar" aria-hidden="true">
                            <i data-lucide="paw-print"></i>
                        </div>

                        <div class="cliente-card__title-wrap">
                            <h2><?= \Core\e($p['nome']) ?></h2>
                        </div>

                        <div class="cliente-card__actions" aria-label="Ações do pet">
                            <a href="/pets/<?= $p['id'] ?>/editar" class="cliente-card__action cliente-card__action--edit" aria-label="Editar pet">
                                <i data-lucide="pencil"></i>
                            </a>

                            <form method="post" action="/pets/<?= $p['id'] ?>/excluir"
                                  class="cliente-card__delete-form"
                                  onsubmit="return confirm('Excluir <?= \Core\e($p['nome']) ?>? Os agendamentos dele também serão apagados!')">
                                <button type="submit" class="cliente-card__action cliente-card__action--delete" aria-label="Excluir pet">
                                    <i data-lucide="trash-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="cliente-card__info">
                        <div class="cliente-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="user-round"></i>
                            </span>
                            <span><?= \Core\e($nomeTutor !== '' ? $nomeTutor : 'Tutor não informado') ?></span>
                        </div>

                        <div class="cliente-card__line">
                            <span class="cliente-card__icon" aria-hidden="true">
                                <i data-lucide="badge-check"></i>
                            </span>
                            <span><?= \Core\e('Porte: ' . $porteTexto) ?></span>
                        </div>

                        <div class="cliente-card__description">
                            <span class="cliente-card__description-icon" aria-hidden="true">
                                <i data-lucide="message-square-text"></i>
                            </span>
                            <span><?= \Core\e($descricaoTexto) ?></span>
                        </div>

                        <button
                            type="button"
                            class="cliente-card__details-btn pet-card__details-btn"
                            data-id="<?= (int) ($p['id'] ?? 0) ?>"
                            data-nome="<?= \Core\e($p['nome'] ?? '') ?>"
                            data-tutor="<?= \Core\e($nomeTutor !== '' ? $nomeTutor : 'Tutor não informado') ?>"
                            data-raca="<?= \Core\e((string) ($p['raca'] ?? 'Não informado')) ?>"
                            data-porte="<?= \Core\e($porteTexto) ?>"
                            data-pelagem="<?= \Core\e((string) ($p['pelagem'] ?? 'Não informado')) ?>"
                            data-descricao="<?= \Core\e($descricao !== '' ? $descricao : 'Sem descrição.') ?>"
                        >
                            <span>Ver detalhes</span>
                            <i data-lucide="chevron-right" aria-hidden="true"></i>
                        </button>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>

        <div class="clientes-empty clientes-empty--filter hidden" id="petsEmptyFilter">
            <p>Nenhum pet encontrado para essa busca.</p>
        </div>
    <?php endif; ?>
</div>

<div class="cliente-modal-backdrop <?= !empty($petModalAberto) ? '' : 'hidden' ?>" id="petModalBackdrop" aria-hidden="<?= !empty($petModalAberto) ? 'false' : 'true' ?>">
    <div class="cliente-modal pet-modal" role="dialog" aria-modal="true" aria-labelledby="petModalTitulo">
        <div class="cliente-modal__header">
            <h2 id="petModalTitulo">Novo pet</h2>
            <button type="button" class="cliente-modal__close" id="petModalClose" aria-label="Fechar modal">&times;</button>
        </div>

        <form method="post" action="/pets" class="cliente-modal__form" id="petForm">
            <div class="cliente-modal__row">
                <div class="campo">
                    <label for="petProprietarioId">Tutor <span class="campo-required">*</span></label>
                    <select id="petProprietarioId" name="proprietario_id" required>
                        <option value="">Selecione um tutor</option>
                        <?php foreach ($proprietarios as $prop): ?>
                            <option value="<?= (int) $prop['id'] ?>"><?= \Core\e($prop['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="petNome">Nome do pet <span class="campo-required">*</span></label>
                    <input id="petNome" type="text" name="nome" placeholder="Ex: Belinha" required>
                </div>
            </div>

            <div class="cliente-modal__row">
                <div class="campo">
                    <label for="petRaca">Raça</label>
                    <input id="petRaca" type="text" name="raca" placeholder="Ex: Poodle">
                </div>

                <div class="campo">
                    <label for="petPorte">Porte <span class="campo-required">*</span></label>
                    <select id="petPorte" name="porte" required>
                        <option value="">Selecione o porte</option>
                        <option value="pequeno">Pequeno</option>
                        <option value="medio">Médio</option>
                        <option value="grande">Grande</option>
                    </select>
                </div>
            </div>

            <div class="cliente-modal__row cliente-modal__row--restante">
                <div class="campo campo--restante">
                    <label for="petPelagem">Pelagem</label>
                    <input id="petPelagem" type="text" name="pelagem" placeholder="Ex: Cacheada">
                </div>

                <div class="campo campo--restante">
                    <label for="petObservacoes">Observações</label>
                    <textarea id="petObservacoes" name="observacoes" rows="4" placeholder="Alergias, restrições, cuidados especiais, comportamento..."></textarea>
                </div>
            </div>

            <div class="cliente-modal__actions">
                <button type="submit" class="cliente-modal__submit" id="petSubmitBtn" disabled>
                    <i data-lucide="save" aria-hidden="true"></i>
                    <span>Cadastrar pet</span>
                </button>
                <button type="button" class="cliente-modal__cancel" id="petCancelBtn">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div class="cliente-detalhes-backdrop hidden" id="petDetalhesBackdrop" aria-hidden="true">
    <div class="cliente-detalhes-modal pet-detalhes-modal" role="dialog" aria-modal="true" aria-labelledby="petDetalhesTitulo">
        <button type="button" class="cliente-detalhes-close" id="petDetalhesClose" aria-label="Fechar modal">&times;</button>

        <div class="cliente-detalhes-header">
            <h3 id="petDetalhesTitulo">Detalhes do pet</h3>
        </div>

        <div class="cliente-detalhes-body pet-detalhes-body">
            <div class="cliente-detalhes-user">
                <div class="cliente-detalhes-avatar" aria-hidden="true">
                    <i data-lucide="paw-print"></i>
                </div>
                <div class="cliente-detalhes-identity">
                    <h4 id="petDetalhesNome">Nome do pet</h4>
                    <p id="petDetalhesTutor">Tutor do pet</p>
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-section">
                <h5>Informações do pet</h5>
                <div class="pet-detalhes-grid">
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Raça</span>
                        <strong id="petDetalhesRaca">Não informado</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Porte</span>
                        <strong id="petDetalhesPorte">Não informado</strong>
                    </div>
                    <div class="pet-detalhes-row">
                        <span class="pet-detalhes-label">Pelagem</span>
                        <strong id="petDetalhesPelagem">Não informado</strong>
                    </div>
                </div>
            </div>

            <div class="cliente-detalhes-separator"></div>

            <div class="cliente-detalhes-section">
                <h5>Observações importantes</h5>
                <div class="cliente-detalhes-texto" id="petDetalhesDescricao">
                    Sem descrição cadastrada.
                </div>
            </div>

            <div class="cliente-detalhes-actions">
                <button type="button" class="cliente-detalhes-fechar" id="petDetalhesFechar">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.querySelector('.clientes-search-input');
        const cards = document.querySelectorAll('.pet-card');
        const emptyFilter = document.getElementById('petsEmptyFilter');

        const petModalBackdrop = document.getElementById('petModalBackdrop');
        const petModalClose = document.getElementById('petModalClose');
        const petCancelBtn = document.getElementById('petCancelBtn');
        const petNewBtn = document.getElementById('novoPetBtn');
        const petForm = document.getElementById('petForm');
        const petSubmitBtn = document.getElementById('petSubmitBtn');

        if (petForm && petSubmitBtn) {
            const petTutorInput = document.getElementById('petProprietarioId');
            const petNomeInput = document.getElementById('petNome');
            const petPorteInput = document.getElementById('petPorte');

            const validarPet = () => {
                const tutorOk = petTutorInput.value !== '';
                const nomeOk = petNomeInput.value.trim() !== '';
                const porteOk = petPorteInput.value !== '';
                petSubmitBtn.disabled = !(tutorOk && nomeOk && porteOk);
            };

            petTutorInput.addEventListener('change', validarPet);
            petNomeInput.addEventListener('input', validarPet);
            petPorteInput.addEventListener('change', validarPet);
            validarPet();
        }

        const openPetCreateModal = () => {
            if (!petModalBackdrop) return;
            petModalBackdrop.classList.remove('hidden');
            petModalBackdrop.setAttribute('aria-hidden', 'false');
        };

        const closePetCreateModal = () => {
            if (!petModalBackdrop) return;
            petModalBackdrop.classList.add('hidden');
            petModalBackdrop.setAttribute('aria-hidden', 'true');
        };

        if (petNewBtn) {
            petNewBtn.addEventListener('click', openPetCreateModal);
        }

        if (petModalClose) {
            petModalClose.addEventListener('click', closePetCreateModal);
        }

        if (petCancelBtn) {
            petCancelBtn.addEventListener('click', closePetCreateModal);
        }

        if (petModalBackdrop) {
            petModalBackdrop.addEventListener('click', (event) => {
                if (event.target === petModalBackdrop) {
                    closePetCreateModal();
                }
            });
        }

        const petBackdrop = document.getElementById('petDetalhesBackdrop');
        const petCloseBtn = document.getElementById('petDetalhesClose');
        const petCloseAction = document.getElementById('petDetalhesFechar');

        const petNome = document.getElementById('petDetalhesNome');
        const petTutor = document.getElementById('petDetalhesTutor');
        const petRaca = document.getElementById('petDetalhesRaca');
        const petPorte = document.getElementById('petDetalhesPorte');
        const petPelagem = document.getElementById('petDetalhesPelagem');
        const petDescricao = document.getElementById('petDetalhesDescricao');

        const closePetModal = () => {
            if (!petBackdrop) return;
            petBackdrop.classList.add('hidden');
            petBackdrop.setAttribute('aria-hidden', 'true');
        };

        const openPetModal = (button) => {
            if (!petBackdrop || !petNome || !petTutor || !petRaca || !petPorte || !petPelagem || !petDescricao) return;

            petNome.textContent = button.dataset.nome || 'Nome do pet';
            petTutor.textContent = button.dataset.tutor || 'Tutor não informado';
            petRaca.textContent = button.dataset.raca || 'Não informado';
            petPorte.textContent = button.dataset.porte || 'Não informado';
            petPelagem.textContent = button.dataset.pelagem || 'Não informado';
            petDescricao.textContent = button.dataset.descricao || 'Sem descrição cadastrada.';

            petBackdrop.classList.remove('hidden');
            petBackdrop.setAttribute('aria-hidden', 'false');

            if (window.lucide) {
                lucide.createIcons();
            }
        };

        document.querySelectorAll('.pet-card__details-btn').forEach((button) => {
            button.addEventListener('click', () => openPetModal(button));
        });

        if (petCloseBtn) {
            petCloseBtn.addEventListener('click', closePetModal);
        }

        if (petCloseAction) {
            petCloseAction.addEventListener('click', closePetModal);
        }

        if (petBackdrop) {
            petBackdrop.addEventListener('click', (event) => {
                if (event.target === petBackdrop) {
                    closePetModal();
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
                const tutor = (card.dataset.tutor || '').toLowerCase();
                const match = termo === '' || nome.includes(termo) || tutor.includes(termo);

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

