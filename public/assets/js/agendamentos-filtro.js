/**
 * Seletor de categoria da listagem de agendamentos.
 *
 * Os cards já chegam renderizados com data-agendamento-categoria, então o
 * filtro acontece só no navegador: nenhum request é feito ao trocar de aba.
 * A classe "agendamento-card--oculto" esconde o card (display: none).
 */
document.addEventListener('DOMContentLoaded', function () {
    const botoes = document.querySelectorAll('[data-agendamento-filtro]');

    if (botoes.length === 0) {
        return;
    }

    const cards = document.querySelectorAll('[data-agendamento-card]');
    const vazio = document.getElementById('agendamentosEmptyFilter');
    const contador = document.getElementById('agendamentosContagem');

    function aplicar(categoria) {
        let visiveis = 0;

        cards.forEach((card) => {
            const pertence = categoria === 'todos' || card.dataset.agendamentoCategoria === categoria;

            card.classList.toggle('agendamento-card--oculto', !pertence);

            if (pertence) {
                visiveis++;
            }
        });

        botoes.forEach((botao) => {
            const ativo = botao.dataset.agendamentoFiltro === categoria;

            botao.classList.toggle('is-ativo', ativo);
            botao.setAttribute('aria-pressed', ativo ? 'true' : 'false');
        });

        if (vazio) {
            vazio.classList.toggle('hidden', visiveis > 0);
        }

        if (contador) {
            contador.value = `${visiveis} agendamento${visiveis === 1 ? '' : 's'}`;
        }
    }

    botoes.forEach((botao) => {
        botao.addEventListener('click', function () {
            aplicar(botao.dataset.agendamentoFiltro);
        });
    });
});
