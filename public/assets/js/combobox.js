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
        const vazio = document.createElement('li');
        vazio.className = 'combobox-vazio';
        vazio.textContent = 'Nenhum pet encontrado';
        lista.replaceChildren(vazio);
        lista.hidden = false;
        return;
    }

    // DOM API em vez de innerHTML: nomes de pet/dono com < > & não quebram (nem injetam) o HTML
    const itens = resultados.map(c => {
        const item = document.createElement('li');
        item.className = 'combobox-item';
        item.dataset.id = String(c.id);

        const pet = document.createElement('span');
        pet.className = 'combobox-pet';
        pet.textContent = ` ${c.nome}`;

        const dono = document.createElement('span');
        dono.className = 'combobox-dono';
        dono.textContent = `${c.proprietario_nome} · ${c.raca ?? 'raça não informada'}`;

        item.append(pet, dono);

        return item;
    });

    lista.replaceChildren(...itens);
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
