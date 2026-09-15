<?php
/** Form de pet. Variáveis: $pet, $proprietarios (select de dono), $erro */
$editando = $pet !== null;
?>
<h1><?= $editando ? 'Editar pet' : 'Novo pet' ?></h1>

<?php if ($erro !== null): ?>
    <p><strong><?= \Core\e($erro) ?></strong></p>
<?php endif; ?>

<form method="post" action="<?= $editando ? '/pets/' . $pet['id'] : '/pets' ?>">

    <p>
        <label>Dono *<br>
            <select name="proprietario_id" required>
                <option value="">— escolha o dono —</option>
                <?php foreach ($proprietarios as $prop): ?>
                    <option value="<?= $prop['id'] ?>"
                        <?= ($pet['proprietario_id'] ?? null) == $prop['id'] ? 'selected' : '' ?>>
                        <?= \Core\e($prop['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Nome do pet *<br>
            <input type="text" name="nome" value="<?= \Core\e($pet['nome'] ?? '') ?>" required>
        </label>
    </p>

    <p>
        <label>Raça<br>
            <input type="text" name="raca" value="<?= \Core\e($pet['raca'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Porte *<br>
            <select name="porte" required>
                <option value="">—</option>
                <?php foreach (['pequeno', 'medio', 'grande'] as $porte): ?>
                    <option value="<?= $porte ?>"
                        <?= ($pet['porte'] ?? null) === $porte ? 'selected' : '' ?>>
                        <?= $porte ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
    </p>

    <p>
        <label>Pelagem<br>
            <input type="text" name="pelagem" value="<?= \Core\e($pet['pelagem'] ?? '') ?>">
        </label>
    </p>

    <p>
        <label>Observações (temperamento, alergias, cuidados)<br>
            <textarea name="observacoes" rows="2"><?= \Core\e($pet['observacoes'] ?? '') ?></textarea>
        </label>
    </p>

    <button>Salvar</button>
    <a href="/pets">Cancelar</a>
</form>

