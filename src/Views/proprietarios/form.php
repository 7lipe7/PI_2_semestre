<?php
/** Form de cliente. Variáveis: $cliente (null=cadastrar), $erro */
$editando = $cliente !== null;
?>
<h1><?= $editando ? 'Editar cliente' : 'Novo cliente' ?></h1>

<?php if ($erro !== null): ?>
    <p><strong><?= \Core\e($erro) ?></strong></p>
<?php endif; ?>

<form method="post" action="<?= $editando ? '/clientes/' . $cliente['id'] : '/clientes' ?>">

    <p>
        <label>Nome *<br>
            <input type="text" name="nome" value="<?= \Core\e($cliente['nome'] ?? '') ?>" required>
        </label>
    </p>

    <p>
        <label>Celular *<br>
            <input type="tel" name="celular" value="<?= \Core\e($cliente['celular'] ?? '') ?>" required>
        </label>
    </p>

    <p>
        <label>Observações<br>
            <textarea name="observacoes" rows="2"><?= \Core\e($cliente['observacoes'] ?? '') ?></textarea>
        </label>
    </p>

    <button>Salvar</button>
    <a href="/clientes">Cancelar</a>
</form>

