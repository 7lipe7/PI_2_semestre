<?php
/** Form de pet. Variáveis: $pet, $proprietarios (select de dono), $erro */
$editando = $pet !== null;
?>
<div class="agendamento-form-page">
    <div class="agendamento-form-card">
        <div class="agendamento-form-header">
            <div class="agendamento-form-icon" aria-hidden="true">
                <i data-lucide="dog"></i>
            </div>
            <div>
                <h1><?= $editando ? 'Editar pet' : 'Novo pet' ?></h1>
                <p><?= $editando ? 'Atualize os dados do pet' : 'Cadastre um novo pet no sistema' ?></p>
            </div>
        </div>

        <?php if ($erro !== null): ?>
            <p class="form-erro"><strong><?= \Core\e($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post" action="<?= $editando ? '/pets/' . $pet['id'] : '/pets' ?>" class="agendamento-form">

            <div class="campo">
                <label for="proprietario_id">Dono *</label>
                <select id="proprietario_id" name="proprietario_id" required>
                    <option value="">— escolha o dono —</option>
                    <?php foreach ($proprietarios as $prop): ?>
                        <option value="<?= $prop['id'] ?>"
                            <?= ($pet['proprietario_id'] ?? null) == $prop['id'] ? 'selected' : '' ?>>
                            <?= \Core\e($prop['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="nome">Nome do pet *</label>
                <input id="nome" type="text" name="nome" value="<?= \Core\e($pet['nome'] ?? '') ?>" required>
            </div>

            <div class="campo">
                <label for="raca">Raça</label>
                <input id="raca" type="text" name="raca" value="<?= \Core\e($pet['raca'] ?? '') ?>">
            </div>

            <div class="campo">
                <label for="porte">Porte *</label>
                <select id="porte" name="porte" required>
                    <option value="">—</option>
                    <?php foreach (['pequeno', 'medio', 'grande'] as $porte): ?>
                        <option value="<?= $porte ?>"
                            <?= ($pet['porte'] ?? null) === $porte ? 'selected' : '' ?>>
                            <?= $porte ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="pelagem">Pelagem</label>
                <input id="pelagem" type="text" name="pelagem" value="<?= \Core\e($pet['pelagem'] ?? '') ?>">
            </div>

            <div class="campo">
                <label for="observacoes">Observações (temperamento, alergias, cuidados)</label>
                <textarea id="observacoes" name="observacoes" rows="2"><?= \Core\e($pet['observacoes'] ?? '') ?></textarea>
            </div>

            <div class="agendamento-form__actions">
                <button type="submit" class="botao">Salvar</button>
                <a href="/pets" class="btn btn-light agendamento-form__cancel">Cancelar</a>
            </div>
        </form>
    </div>
</div>

