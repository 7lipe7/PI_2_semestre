<?php
/** Catálogo de serviços. Variável: $servicos */
?>
<h1>Serviços</h1>

<a href="/servicos/novo">Novo serviço</a>

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
                    <td><?= \Core\e(trim((string) ($s['descricao'] ?? '')) !== '' ? $s['descricao'] : 'Sem descrição') ?></td>
                    <td>R$ <?= number_format((float) $s['preco'], 2, ',', '.') ?></td>
                    <td><?= (int) $s['ativo'] === 1 ? 'Ativo' : 'Inativo' ?></td>
                    <td>
                        <a href="/servicos/<?= (int) $s['id'] ?>/editar">Editar</a>

                        <form method="post" action="/servicos/<?= (int) $s['id'] ?>/excluir"
                              onsubmit="return confirm('Excluir o serviço <?= \Core\e($s['nome']) ?>?')">
                            <button>Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
