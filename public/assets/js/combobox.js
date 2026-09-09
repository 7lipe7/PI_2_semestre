/**
 * Combobox de busca: filtra cachorros por nome do pet OU do dono.
 * Os dados vêm da view (window.CACHORROS, injetado pelo PHP).
 */
const busca    = document.getElementById('pet-busca');
const lista    = document.getElementById('pet-lista');
const hiddenId = document.getElementById('cachorro_id');
const selecao  = document.getElementById('pet-selecionado');

const CACHORROS = window.CACHORROS ?? [];

/** minúsculas + sem acento: "joao" encontra "João" */
function normalizar(texto) {
    return String(texto ?? '')
        .toLowerCase()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '');
}

function textoItem(c) {
    return `${c.nome} (dono: ${c.proprietario_nome})`;
}

function textoSelecao(c) {
    const celular = c.proprietario_celular ? ` · ${c.proprietario_celular}` : '';
    return `✔ ${c.nome} — ${c.proprietario_nome}${celular}`;
}

function mostrarLista(filtro) {
    const termo = normalizar(filtro);

    const resultados = CACHORROS.filter(c =>
        termo === '' ||
        normalizar(c.nome).includes(termo) ||
        normalizar(c.proprietario_nome).includes(termo)
    );

    if (resultados.length === 0) {
        lista.innerHTML = '<li class="combobox-vazio">Nenhum pet encontrado </li>';
        lista.hidden = false;
        return;
    }

    lista.innerHTML = resultados.map(c => `
        <li class="combobox-item" data-id="${c.id}">
            <span class="combobox-pet"> ${c.nome}</span>
            <span class="combobox-dono">${c.proprietario_nome} · ${c.raca ?? 'raça não informada'}</span>
        </li>
    `).join('');

    lista.hidden = false;
}

// Digitar → filtra e limpa a seleção anterior
busca.addEventListener('input', () => {
    hiddenId.value = '';
    selecao.textContent = '';
    mostrarLista(busca.value);
});

// Focar → mostra a lista (com filtro, se houver algo digitado)
busca.addEventListener('focus', () => mostrarLista(busca.value));

// Clicar num item → confirma a escolha
lista.addEventListener('click', (e) => {
    const item = e.target.closest('.combobox-item');
    if (!item) return;

    const cachorro = CACHORROS.find(c => String(c.id) === item.dataset.id);
    if (!cachorro) return;

    hiddenId.value = cachorro.id;
    busca.value = textoItem(cachorro);
    selecao.textContent = textoSelecao(cachorro);

    lista.hidden = true;
});

// Clicar fora → fecha a lista
document.addEventListener('click', (e) => {
    if (!e.target.closest('.combobox')) lista.hidden = true;
});

// Blinda o submit: sem pet escolhido, não envia
document.querySelector('form').addEventListener('submit', (e) => {
    if (!hiddenId.value) {
        e.preventDefault();
        alert('Escolha um pet na busca para marcar o agendamento!');
        busca.focus();
    }
});
