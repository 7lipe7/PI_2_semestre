/**
 * Dashboard: troca de status do atendimento sem recarregar a página.
 *
 * Os botões são <form> de verdade — sem JS o POST continua funcionando e o
 * controller redireciona de volta para a agenda. Aqui interceptamos o submit,
 * pedimos JSON ao controller (Accept: application/json) e reescrevemos o item
 * da lista com o que veio na resposta.
 *
 * Nada é montado com innerHTML: o servidor manda rótulo/ícone/status já
 * prontos, e o JS só troca texto e atributos.
 */

document.addEventListener('DOMContentLoaded', function () {
    const itens = document.querySelectorAll('[data-agenda-item]');
    const aviso = document.getElementById('agendaAviso');

    if (itens.length === 0) {
        return;
    }

    let avisoTimer = null;

    function mostrarAviso(mensagem, tipo) {
        if (!aviso) {
            return;
        }

        aviso.textContent = mensagem;

        aviso.classList.remove(
            'hidden',
            'agenda-aviso--erro',
            'agenda-aviso--ok'
        );

        aviso.classList.add(
            tipo === 'erro'
                ? 'agenda-aviso--erro'
                : 'agenda-aviso--ok'
        );

        clearTimeout(avisoTimer);

        avisoTimer = setTimeout(() => {
            aviso.classList.add('hidden');
        }, 4000);
    }

    function criarIcone(nome) {
        const icone = document.createElement('i');

        icone.dataset.lucide = nome;
        icone.setAttribute('aria-hidden', 'true');

        return icone;
    }

    /**
     * Reconstrói os botões do item conforme as ações do novo status.
     */
    function renderAcoes(item, acoes) {
        const container = item.querySelector('[data-agenda-acoes]');

        if (!container) {
            return;
        }

        container.replaceChildren();

        if (!Array.isArray(acoes) || acoes.length === 0) {
            container.classList.add('agenda-lista__acoes--vazio');
            return;
        }

        container.classList.remove('agenda-lista__acoes--vazio');

        const agendamentoId = item.dataset.agendamentoId;
        const data = item.dataset.agendamentoData ?? '';

        acoes.forEach((acao) => {
            const form = document.createElement('form');

            form.method = 'post';
            form.action = `/agendamentos/${agendamentoId}/status`;
            form.className = 'agenda-lista__acao-form';
            form.dataset.agendaAcaoForm = '';

            const campos = [
                ['_csrf', window.AGENDA_CSRF ?? ''],
                ['status', acao.status],
                ['redirecionar', '/'],
                ['data', data]
            ];

            campos.forEach(([nome, valor]) => {
                const campo = document.createElement('input');

                campo.type = 'hidden';
                campo.name = nome;
                campo.value = valor;

                form.append(campo);
            });

            const botao = document.createElement('button');

            botao.type = 'submit';
            botao.className =
                `agenda-lista__acao agenda-lista__acao--${acao.status}`;

            botao.dataset.agendaAcao = acao.status;

            const texto = document.createElement('span');

            texto.textContent = acao.rotulo;

            botao.append(
                criarIcone(acao.icone),
                texto
            );

            form.append(botao);
            container.append(form);
        });

        renderIcones();
    }

    /**
     * Atualiza badge, classes e resumo do dia depois da troca.
     */
    function aplicarStatus(item, dados) {
        const anterior = item.dataset.agendamentoStatus;

        const classes = [
            'agendado',
            'andamento',
            'concluido',
            'cancelado'
        ];

        item.dataset.agendamentoStatus = dados.status;
        item.dataset.agendamentoCategoria = dados.categoria;

        classes.forEach((status) => {
            item.classList.remove(
                `agenda-lista__item--${status}`
            );
        });

        item.classList.add(
            `agenda-lista__item--${dados.status}`
        );

        const badge = item.querySelector(
            '[data-agenda-status]'
        );

        if (badge) {
            badge.className =
                `agenda-lista__status agenda-lista__status--${dados.status}`;

            badge.title = dados.rotulo;

            const icone = badge.querySelector(
                '[data-agenda-status-icon]'
            );

            if (icone) {
                icone.dataset.lucide = dados.icone;
            }

            const rotulo = badge.querySelector(
                '[data-agenda-status-rotulo]'
            );

            if (rotulo) {
                rotulo.textContent = dados.rotulo;
            }
        }

        renderAcoes(item, dados.acoes);

        moverResumo(anterior, dados.status);

        renderIcones();
    }

    /**
     * Decrementa a contagem antiga e incrementa a nova
     * no resumo do dia.
     */
    function moverResumo(de, para) {
        if (!de || de === para) {
            return;
        }

        [
            'agendado',
            'andamento',
            'concluido',
            'cancelado'
        ].forEach((status) => {
            const alvo = document.querySelector(
                `[data-agenda-resumo="${status}"]`
            );

            if (!alvo) {
                return;
            }

            let total = parseInt(
                alvo.textContent,
                10
            ) || 0;

            if (status === de) {
                total = Math.max(0, total - 1);
            }

            if (status === para) {
                total += 1;
            }

            alvo.textContent = total;
        });
    }

    function renderIcones() {
        // Os <i data-lucide> novos só viram SVG depois do createIcons()
        if (window.lucide) {
            window.lucide.createIcons();
        }
    }

    itens.forEach((item) => {
        item.addEventListener('submit', function (event) {
            const form = event.target.closest(
                '[data-agenda-acao-form]'
            );

            if (!form) {
                return;
            }

            event.preventDefault();

            const botao = form.querySelector(
                '[data-agenda-acao]'
            );

            if (!botao) {
                return;
            }

            botao.disabled = true;

            item.classList.add(
                'agenda-lista__item--carregando'
            );

            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then((resposta) =>
                    resposta.json().then((dados) => ({
                        ok: resposta.ok,
                        dados
                    }))
                )
                .then(({ ok, dados }) => {
                    if (
                        !ok ||
                        !dados ||
                        dados.erro
                    ) {
                        throw new Error(
                            (dados && dados.erro) ||
                            'Não foi possível mudar o status.'
                        );
                    }

                    aplicarStatus(item, dados);

                    mostrarAviso(
                        `Status alterado para ${dados.rotulo}.`,
                        'ok'
                    );
                })
                .catch((erro) => {
                    mostrarAviso(
                        erro.message ||
                        'Não foi possível mudar o status.',
                        'erro'
                    );
                })
                .finally(() => {
                    botao.disabled = false;

                    item.classList.remove(
                        'agenda-lista__item--carregando'
                    );
                });
        });
    });

    renderIcones();
});