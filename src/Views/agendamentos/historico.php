<?php

$historico = $historico ?? [];

$statusLabels = [
    'agendado'  => 'Agendado',
    'andamento' => 'Em andamento',
    'concluido' => 'Concluído',
    'cancelado' => 'Cancelado',
];

$statusClasses = [
    'agendado'  => 'bg-warning text-dark',
    'andamento' => 'bg-info text-dark',
    'concluido' => 'bg-success',
    'cancelado' => 'bg-danger',
];


?>

<div class="clientes-page agendamentos-page">

    <!-- Cabeçalho -->
    <div class="clientes-toolbar d-flex align-items-center justify-content-between gap-3 flex-wrap">

        <div>
            <h2 class="mb-1">Histórico de agendamentos</h2>

            <p class="mb-0">
                Alterações de status dos agendamentos
            </p>
        </div>

        <a href="/agendamentos" class="btn clientes-new-btn">
            <i data-lucide="arrow-left"></i>
            <span>Voltar</span>
        </a>

    </div>


    <!-- Sem histórico -->
    <?php if (empty($historico)): ?>

        <div class="clientes-empty">
            <p>Nenhuma alteração de status registrada.</p>
        </div>


    <!-- Histórico -->
    <?php else: ?>

        <div class="row g-3 align-items-start">

            <?php foreach ($historico as $item): ?>

                <?php

                $statusAnterior = (string) $item['status_anterior'];
                $statusNovo     = (string) $item['status_novo'];

                $anterior = $statusLabels[$statusAnterior]
                    ?? ucfirst($statusAnterior);

                $novo = $statusLabels[$statusNovo]
                    ?? ucfirst($statusNovo);

                $classeAnterior = $statusClasses[$statusAnterior]
                    ?? 'bg-secondary';

                $classeNova = $statusClasses[$statusNovo]
                    ?? 'bg-secondary';

                ?>

                <div class="col-12 col-lg-6">

                    <article class="agendamento-card h-auto">

                        <!-- Cabeçalho do card -->
                        <div class="agendamento-card__header">

                            <div
                                class="agendamento-card__icon"
                                aria-hidden="true"
                            >
                                <i data-lucide="history"></i>
                            </div>


                            <div class="agendamento-card__title-wrap">

                                <h2>
                                    <?= \Core\e($item['cachorro']) ?>
                                </h2>

                                <p>
                                    <?= \Core\e($item['proprietario']) ?>
                                </p>

                            </div>

                        </div>


                        <!-- Informações -->
                        <div class="agendamento-card__info">

                            <!-- Serviço -->
                            <div class="agendamento-card__line">

                                <span
                                    class="cliente-card__icon"
                                    aria-hidden="true"
                                >
                                    <i data-lucide="scissors"></i>
                                </span>

                                <span>
                                    <?= \Core\e($item['servico']) ?>
                                </span>

                            </div>


                            <!-- Mudança de status -->
                            <div class="agendamento-card__line">

                                <span
                                    class="cliente-card__icon"
                                    aria-hidden="true"
                                >
                                    <i data-lucide="refresh-cw"></i>
                                </span>


                                <div class="d-flex align-items-center gap-2 flex-wrap">

                                    <!-- Status anterior -->
                                    <span
                                        class="badge <?= $classeAnterior ?>"
                                    >
                                        <?= \Core\e($anterior) ?>
                                    </span>


                                    <!-- Seta -->
                                    <i
                                        data-lucide="arrow-right"
                                        style="width: 18px; height: 18px;"
                                    ></i>


                                    <!-- Novo status -->
                                    <span
                                        class="badge <?= $classeNova ?>"
                                    >
                                        <?= \Core\e($novo) ?>
                                    </span>

                                </div>

                            </div>


                            <!-- Data da alteração -->
                            <div class="agendamento-card__line">

                                <span
                                    class="cliente-card__icon"
                                    aria-hidden="true"
                                >
                                    <i data-lucide="clock"></i>
                                </span>

                                <span>
                                    <?= date(
                                        'd/m/Y H:i',
                                        strtotime($item['alterado_em'])
                                    ) ?>
                                </span>

                            </div>

                        </div>

                    </article>

                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>