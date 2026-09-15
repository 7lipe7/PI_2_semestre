<?php
/** Form de serviço. Variáveis: $servico (null=cadastrar), $erro */
$editando = !empty($servico['id']);

// Na edição mostra formatado (45,00); na validação re-mostra o que foi digitado
if ($editando) {
    $precoFormatado = number_format((float) $servico['preco'], 2, ',', '');
} elseif (isset($servico['preco'])) {
    $precoFormatado = (string) $servico['preco'];
} else {
    $precoFormatado = '';
}

$ativoAtual = (int) ($servico['ativo'] ?? 1);
?>
<h1><?= $editando ? 'Editar serviço' : 'Novo serviço' ?></h1>

<?php if ($erro !== null): ?>
    <p><strong><?= \Core\e($erro) ?></strong></p>
<?php endif; ?>

<form method="post" action="<?= $editando ? '/servicos/' . (int) $servico['id'] : '/servicos' ?>">
    <p>
        <label for="nome">Nome</label><br>
        <input
            id="nome"
            type="text"
            name="nome"
            value="<?= \Core\e($servico['nome'] ?? '') ?>"
            placeholder="Ex.: Banho com hidratação"
            required
        >
    </p>

    <p>
        <label for="preco">Valor (R$)</label><br>
        <input
            id="preco"
            type="text"
            name="preco"
            inputmode="decimal"
            value="<?= \Core\e($precoFormatado) ?>"
            placeholder="Ex.: 45,00"
            required
        >
    </p>

    <p>
        <label for="descricao">Descrição</label><br>
        <textarea
            id="descricao"
            name="descricao"
            rows="3"
            placeholder="O que está incluído no serviço..."
        ><?= \Core\e($servico['descricao'] ?? '') ?></textarea>
    </p>

    <p>
        <label for="ativo">Situação</label><br>
        <select id="ativo" name="ativo">
            <option value="1" <?= $ativoAtual === 1 ? 'selected' : '' ?>>Ativo (aparece na marcação)</option>
            <option value="0" <?= $ativoAtual === 0 ? 'selected' : '' ?>>Inativo</option>
        </select>
    </p>

    <p>
        <button type="submit"><?= $editando ? 'Atualizar serviço' : 'Cadastrar serviço' ?></button>
    </p>
</form>
