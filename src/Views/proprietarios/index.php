<?php
/** Lista de clientes. Variável: $clientes (id, nome, celular, observacoes) */
?>
<h1>Clientes</h1>

<a href="/clientes/novo">Novo cliente</a>

<?php if (empty($clientes)): ?>
    <p>Nenhum cliente cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Nome</th><th>Celular</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($clientes as $c): ?>
                <tr>
                    <td><?= \Core\e($c['nome']) ?></td>
                    <td><?= \Core\e($c['celular']) ?></td>
                    <td>
                        <a href="/clientes/<?= $c['id'] ?>/editar">Editar</a>

                        <!-- excluir é POST (com confirm avisando do CASCADE) -->
                        <form method="post" action="/clientes/<?= $c['id'] ?>/excluir"
                              onsubmit="return confirm('Excluir <?= \Core\e($c['nome']) ?>? Os cachorros e agendamentos dele também serão apagados!')">
                            <button>Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

