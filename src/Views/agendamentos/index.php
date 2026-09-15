<?php
/** Lista de agendamentos. Variável: $agendamentos (com data, hora, status, cachorro_nome...) */
?>
<h1>Agendamentos</h1>

<a href="/agendamentos/novo">Marcar banho e tosa</a>

<?php if (empty($agendamentos)): ?>
    <p>Nenhum horário marcado.</p>
<?php else: ?>
    <table>
        <thead>
            <tr>
                <th>Data</th><th>Hora</th><th>Pet</th><th>Dono</th>
                <th>Serviço</th><th>Status</th><th></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agendamentos as $a): ?>
                <tr>
                    <td><?= date('d/m', strtotime($a['data'])) ?></td>
                    <td><?= date('H:i', strtotime($a['hora'])) ?></td>
                    <td><?= \Core\e($a['cachorro_nome']) ?></td>
                    <td><?= \Core\e($a['proprietario_nome']) ?></td>
                    <td><?= \Core\e($a['servico_nome']) ?></td>
                    <td><?= \Core\e($a['status']) ?></td>
                    <td>
                        <a href="/agendamentos/<?= $a['id'] ?>/editar">Editar</a>

                        <form method="post" action="/agendamentos/<?= $a['id'] ?>/excluir"
                              onsubmit="return confirm('Cancelar este agendamento?')">
                            <button>Cancelar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

