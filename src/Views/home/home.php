<?php
/**
 * Agenda do dia. Variáveis: $dataHoje, $agendamentos
 */
?>
<h1>Agenda de <?= date('d/m/Y', strtotime($dataHoje)) ?></h1>

<?php if (empty($agendamentos)): ?>
    <p>Nenhum atendimento marcado para hoje.</p>
    <a href="/agendamentos/novo">Marcar banho e tosa</a>
<?php else: ?>
    <ul>
        <?php foreach ($agendamentos as $a): ?>
            <li>
                <?= date('H:i', strtotime($a['hora'])) ?> —
                <?= \Core\e($a['cachorro_nome']) ?>
                (<?= \Core\e($a['proprietario_nome']) ?>) —
                <?= \Core\e($a['servico_nome']) ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

