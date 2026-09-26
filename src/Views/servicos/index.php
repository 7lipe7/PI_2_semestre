<?php
/** Catálogo de serviços. Variáveis: $servicos, $aviso (opcional) */
$aviso = $aviso ?? null;
$totalServicos = count($servicos ?? []);
$labelServicos = $totalServicos === 1 ? 'serviço' : 'serviços';
?>
<div class="clientes-page servicos-page">
    <div class="clientes-toolbar d-flex align-items-center justify-content-between gap-3 flex-wrap" style="margin-left: 0; padding-left: 0;">
        <div class="clientes-quantidade" aria-live="polite">
            <span class="clientes-quantidade-icon" aria-hidden="true">
                <i data-lucide="scissors"></i>
            </span>
            <span><?= $totalServicos ?></span>
            <span><?= $labelServicos ?></span>
        </div>

        <a href="/servicos/novo" class="btn clientes-new-btn">
            <i data-lucide="plus"></i>
            <span>Novo serviço</span>
        </a>
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
                            <a href="/servicos/<?= (int) $s['id'] ?>/editar" class="btn btn-sm btn-outline-primary">Editar</a>

                            <form method="post"
                                  action="/servicos/<?= (int) $s['id'] ?>/excluir"
                                  class="d-inline"
                                  onsubmit="return confirm(<?= json_encode(
                                      'Excluir o serviço ' . $s['nome'] . '? Se já tiver agendamento, será apenas inativado.',
                                      JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                                  ) ?>)">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
