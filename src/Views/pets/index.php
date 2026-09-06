<?php
/** Lista de pets. Variável: $pets (já vem com proprietario_nome do JOIN) */
?>
<h1>Pets</h1>

<a href="/pets/novo">Novo pet</a>

<?php if (empty($pets)): ?>
    <p>Nenhum pet cadastrado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr><th>Nome</th><th>Raça</th><th>Porte</th><th>Dono</th><th></th></tr>
        </thead>
        <tbody>
            <?php foreach ($pets as $p): ?>
                <tr>
                    <td><?= \Core\e($p['nome']) ?></td>
                    <td><?= \Core\e($p['raca'] ?? '-') ?></td>
                    <td><?= \Core\e($p['porte'] ?? '-') ?></td>
                    <td><?= \Core\e($p['proprietario_nome']) ?></td>
                    <td>
                        <a href="/pets/<?= $p['id'] ?>/editar">Editar</a>

                        <form method="post" action="/pets/<?= $p['id'] ?>/excluir"
                              onsubmit="return confirm('Excluir <?= \Core\e($p['nome']) ?>? Os agendamentos dele também serão apagados!')">
                            <button>Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

