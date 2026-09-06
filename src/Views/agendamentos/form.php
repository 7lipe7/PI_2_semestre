<?php
/** Form de agendamento. Variáveis: $agendamento, $cachorros (para o select), $erro */
$editando = $agendamento !== null;
?>
<h1><?= $editando ? 'Remarcar agendamento' : 'Marcar banho e tosa' ?></h1>

<?php if ($erro !== null): ?>
    <p><strong><?= \Core\e($erro) ?></strong></p>
<?php endif; ?>

<form method="post" action="<?= $editando ? '/agendamentos/' . $agendamento['id'] : '/agendamentos' ?>">

    <p>
        <label>Pet *<br>
            <select name="cachorro_id" required>
                <option value="">— escolha o cachorro —</option>
                <?php foreach ($cachorros as $c): ?>
                    <option value="<?= $c['id'] ?>"
                        <?= ($agendamento['cachorro_id'] ?? null) == $c['id'] ? 'selected' : '' ?>>
                        <?= \Core\e($c['nome']) ?> — <?= \Core\e($c['proprietario_nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Serviço *<br>
            <select name="servico" required>
                <option value="">— escolha —</option>
                <?php foreach (['banho', 'tosa', 'banho_e_tosa'] as $s): ?>
                    <option value="<?= $s ?>"
                        <?= ($agendamento['servico'] ?? null) === $s ? 'selected' : '' ?>>
                        <?= $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Data *<br>
            <input type="date" name="data" value="<?= \Core\e($agendamento['data'] ?? '') ?>" required>
        </label>
    </p>

    <p>
        <label>Hora *<br>
            <input type="time" name="hora" value="<?= \Core\e(substr((string) ($agendamento['hora'] ?? ''), 0, 5)) ?>" required>
        </label>
    </p>

    <?php if ($editando): ?>
        <p>
            <label>Status<br>
                <select name="status">
                    <?php foreach (['agendado', 'concluido', 'cancelado'] as $st): ?>
                        <option value="<?= $st ?>"
                            <?= ($agendamento['status'] ?? null) === $st ? 'selected' : '' ?>>
                            <?= $st ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
    <?php endif; ?>

    <p>
        <label>Observações<br>
            <textarea name="observacoes" rows="2"><?= \Core\e($agendamento['observacoes'] ?? '') ?></textarea>
        </label>
    </p>

    <button>Salvar</button>
    <a href="/agendamentos">Cancelar</a>
</form>

