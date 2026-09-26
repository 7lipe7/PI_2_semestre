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
<div class="agendamento-form-page">
    <div class="agendamento-form-card">
        <div class="agendamento-form-header">
            <div class="agendamento-form-icon" aria-hidden="true">
                <i data-lucide="scissors"></i>
            </div>
            <div>
                <h1><?= $editando ? 'Editar serviço' : 'Novo serviço' ?></h1>
                <p><?= $editando ? 'Atualize os dados do serviço' : 'Cadastre um item do catálogo' ?></p>
            </div>
        </div>

        <?php if ($erro !== null): ?>
            <p class="form-erro"><strong><?= \Core\e($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post" action="<?= $editando ? '/servicos/' . (int) $servico['id'] : '/servicos' ?>" class="agendamento-form">
            <div class="campo">
                <label for="nome">Nome</label>
                <input
                    id="nome"
                    type="text"
                    name="nome"
                    value="<?= \Core\e($servico['nome'] ?? '') ?>"
                    placeholder="Ex.: Banho com hidratação"
                    required
                >
            </div>

            <div class="campo">
                <label for="preco">Valor (R$)</label>
                <input
                    id="preco"
                    type="text"
                    name="preco"
                    inputmode="decimal"
                    value="<?= \Core\e($precoFormatado) ?>"
                    placeholder="Ex.: 45,00"
                    required
                >
            </div>

            <div class="campo campo--full">
                <label for="descricao">Descrição</label>
                <textarea
                    id="descricao"
                    name="descricao"
                    rows="3"
                    placeholder="O que está incluído no serviço..."
                ><?= \Core\e($servico['descricao'] ?? '') ?></textarea>
            </div>

            <div class="campo">
                <label for="ativo">Situação</label>
                <select id="ativo" name="ativo">
                    <option value="1" <?= $ativoAtual === 1 ? 'selected' : '' ?>>Ativo (aparece na marcação)</option>
                    <option value="0" <?= $ativoAtual === 0 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="agendamento-form__actions">
                <button type="submit" class="botao"><?= $editando ? 'Atualizar serviço' : 'Cadastrar serviço' ?></button>
                <a href="/servicos" class="btn btn-light agendamento-form__cancel">Cancelar</a>
            </div>
        </form>
    </div>
</div>
