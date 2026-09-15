<?php
/** Form de cliente. Variáveis: $cliente (null=cadastrar), $erro */
$cliente = $cliente ?? [
    'id' => null,
    'nome' => '',
    'celular' => '',
    'observacoes' => '',
];

$erro = $erro ?? null;
$editando = $cliente !== null && !empty($cliente['id']);
?>
<div class="cliente-modal-backdrop">
    <div class="cliente-modal" role="dialog" aria-modal="true" aria-labelledby="clienteModalTitulo">
        <div class="cliente-modal__header">
            <h2 id="clienteModalTitulo"><?= $editando ? 'Editar cliente' : 'Novo cliente' ?></h2>
            <a href="/clientes" class="cliente-modal__close" aria-label="Fechar">&times;</a>
        </div>

        <?php if ($erro !== null): ?>
            <div class="cliente-modal__erro">
                <strong><?= \Core\e($erro) ?></strong>
            </div>
        <?php endif; ?>

        <form method="post" action="<?= $editando ? '/clientes/' . $cliente['id'] : '/clientes' ?>" class="cliente-modal__form">
            <div class="campo campo--full">
                <label for="nome">Nome <span class="campo-required">*</span></label>
                <input
                    id="nome"
                    type="text"
                    name="nome"
                    value="<?= \Core\e($cliente['nome'] ?? '') ?>"
                    placeholder="Ex: Mariana Souza"
                    required
                >
            </div>

            <div class="campo campo--full">
                <label for="celular">Telefone / WhatsApp <span class="campo-required">*</span></label>
                <input
                    id="celular"
                    type="tel"
                    name="celular"
                    value="<?= \Core\e($cliente['celular'] ?? '') ?>"
                    placeholder="(11) 99999-9999"
                    required
                >
            </div>

            <div class="campo campo--full">
                <label for="observacoes">Descrição</label>
                <textarea
                    id="observacoes"
                    name="observacoes"
                    rows="4"
                    placeholder="Algumas observações sobre o cliente..."
                ><?= \Core\e($cliente['observacoes'] ?? '') ?></textarea>
            </div>

            <div class="cliente-modal__actions">
                <button type="submit" class="cliente-modal__submit" disabled>
                    <?= $editando ? 'Atualizar cliente' : 'Cadastrar cliente' ?>
                </button>
                <a href="/clientes" class="cliente-modal__cancel">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('.cliente-modal__form');
        const submitBtn = document.querySelector('.cliente-modal__submit');

        if (!form || !submitBtn) {
            return;
        }

        const nomeInput = document.getElementById('nome');
        const celularInput = document.getElementById('celular');

        const formatarTelefone = (valor) => {
            const apenasDigitos = valor.replace(/\D/g, '').slice(0, 11);

            if (apenasDigitos.length <= 2) {
                return apenasDigitos;
            }

            if (apenasDigitos.length <= 6) {
                return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2)}`;
            }

            if (apenasDigitos.length <= 10) {
                return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2, 6)}-${apenasDigitos.slice(6)}`;
            }

            return `(${apenasDigitos.slice(0, 2)}) ${apenasDigitos.slice(2, 7)}-${apenasDigitos.slice(7)}`;
        };

        const validarFormulario = () => {
            const nomeOk = nomeInput.value.trim() !== '';
            const celularOk = celularInput.value.trim() !== '';
            submitBtn.disabled = !(nomeOk && celularOk);
        };

        celularInput.addEventListener('input', function () {
            this.value = formatarTelefone(this.value);
            validarFormulario();
        });

        nomeInput.addEventListener('input', validarFormulario);
        validarFormulario();
    });
</script>

