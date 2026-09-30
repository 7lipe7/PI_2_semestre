<?php

/** Form de agendamento. Variáveis: $agendamento, $cachorros, $erro */
$agendamento = $agendamento ?? [];
$cachorros = $cachorros ?? [];
$erro = $erro ?? null;

$editando = !empty($agendamento['id']);
$valor = fn(string $campo, string $padrao = '') => \Core\e((string) ($agendamento[$campo] ?? $padrao));

$cachorroSelecionado = null;
if (!empty($agendamento['cachorro_id'])) {
    foreach ($cachorros as $c) {
        if ((int) $c['id'] === (int) $agendamento['cachorro_id']) {
            $cachorroSelecionado = $c;
            break;
        }
    }
}

$statusAtual = $valor('status', 'agendado');
?>
<div class="agendamento-form-page">
    <div class="agendamento-form-card">
        <div class="agendamento-form-header">
            <div class="agendamento-form-icon" aria-hidden="true">
                <i data-lucide="calendar-clock"></i>
            </div>
            <div>
                <h1><?= $editando ? 'Remarcar agendamento' : 'Marcar banho e tosa' ?></h1>
                <p><?= $editando ? 'Atualize os dados do atendimento' : 'Preencha os dados do agendamento' ?></p>
            </div>
        </div>

        <?php if ($erro !== null): ?>
            <p class="form-erro"><strong><?= \Core\e($erro) ?></strong></p>
        <?php endif; ?>

        <form method="post" action="<?= $editando ? '/agendamentos/' . (int) $agendamento['id'] : '/agendamentos' ?>" class="agendamento-form">
            <?= \Core\Csrf::campo() ?>
            <div class="combobox">
                <label for="pet-busca">Pet</label>

                <select
                    id="pet-busca"
                    class="combobox-busca"
                    placeholder="Ex.: Thor, ou o nome do dono..."
                    autocomplete="off"
                    value="<?= $cachorroSelecionado !== null
                        ? \Core\e($cachorroSelecionado['nome'] . ' (dono: ' . $cachorroSelecionado['proprietario_nome'] . ')')
                        : '' ?>"
                >

                <input
                    type="hidden"
                    name="cachorro_id"
                    id="cachorro_id"
                    value="<?= $cachorroSelecionado !== null ? (int) $cachorroSelecionado['id'] : '' ?>"
                >

                <div id="pet-selecionado" class="combobox-selecionado">
                    <?= $cachorroSelecionado !== null
                        ? \Core\e($cachorroSelecionado['nome'] . ' — ' . $cachorroSelecionado['proprietario_nome'])
                        : '' ?>
                </div>

                <ul id="pet-lista" class="combobox-lista" hidden></ul>
            </div>

            <?php if (!empty($servicos)): ?>
                <div class="campo">
                    <label for="servico">Serviço</label>
                    <select name="servico_id" id="servico" required>
                        <option value="">Selecione um serviço</option>
                        <?php foreach ($servicos as $servico): ?>
                            <option
                                value="<?= (int) $servico['id'] ?>"
                                <?= (int) ($agendamento['servico_id'] ?? 0) === (int) $servico['id'] ? 'selected' : '' ?>>
                                <?= \Core\e($servico['nome']) ?> — R$ <?= number_format((float) $servico['preco'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            <?php else: ?>
                <p class="form-erro">Nenhum serviço ativo no catálogo. <a href="/servicos/novo">Cadastrar serviço</a></p>
            <?php endif; ?>

            <div class="campo">
                <label for="data">Data</label>
                <input type="date" name="data" id="data" required value="<?= $valor('data') ?>">
            </div>

            <div class="campo">
                <label for="hora">Horário</label>
                <input type="time" name="hora" id="hora" required value="<?= substr($valor('hora'), 0, 5) ?>">
            </div>

            <?php if ($editando): ?>
                <div class="campo">
                    <label for="status">Status</label>
                    <select name="status" id="status">

                        <option value="agendado" <?= $statusAtual === 'agendado' ? 'selected' : '' ?>>Agendado</option>
                        <option value="andamento" <?= $statusAtual === 'andamento' ? 'selected' : '' ?>>Em andamento</option>
                        <option value="concluido" <?= $statusAtual === 'concluido' ? 'selected' : '' ?>>Concluído</option>
                        <option value="cancelado" <?= $statusAtual === 'cancelado' ? 'selected' : '' ?>>Cancelado</option>

                    </select>
                </div>
            <?php endif; ?>

            <div class="campo campo--full">
                <label for="observacoes">Observações</label>
                <textarea name="observacoes" id="observacoes" rows="3"><?= $valor('observacoes') ?></textarea>
            </div>

            <div class="agendamento-form__actions">
                <button type="submit" class="botao"><?= $editando ? 'Salvar alterações' : 'Marcar agendamento' ?></button>
                <a href="/agendamentos" class="btn btn-light agendamento-form__cancel">Cancelar</a>
            </div>


       
        </form>
    </div>
</div>

<?php
$petsJson = json_encode(
    array_map(fn(array $c): array => [
        'id' => (int) $c['id'],
        'nome' => $c['nome'],
        'raca' => $c['raca'],
        'proprietario_nome' => $c['proprietario_nome'],
        'proprietario_celular' => $c['proprietario_celular'] ?? null,
    ], $cachorros),
    JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
);
?>
<script>
    window.CACHORROS = <?= $petsJson ?>;
</script>
<script src="/assets/js/combobox.js"></script>