


## 8. INTERFACE DO USUÁRIO (seção 3.8)

Substitua os placeholders Inserir print: ... por capturas de tela reais:

- Tela inicial – agenda do dia
- Proprietários – lista e formulário de cadastro/edição
- Cachorros – lista e formulário de cadastro/edição (lista com busca)
- Agendamentos – lista e formulário de criação/edição (com status)
- Mensagens de erro (campos obrigatórios e Este horário já está ocupado)
- Telas em celular/tablet (responsividade)

Cada print deve ter legenda e breve descrição.

---

## 9. RESULTADOS DOS TESTES (seção 4.2)

Preencha a coluna Resultado obtido de CT01 a CT15 com Passou ou Falhou + data da execução.

---

## 10. CONTRIBUIÇÕES INDIVIDUAIS (seção 5)

Substitua todos os descrever por uma linha curta sobre o que cada integrante fez.

| Integrante | Contribuição |
|------------|--------------|
| Ana Luiza Oliveira de Jesus | [descrever] |
| Felipe Santana Lopes | [descrever] |
| Guilherme Cardoso Hubner | [descrever] |
| Kaique Ribeiro dos Santos | [descrever] |
| Vinicius Tadeu Silvestre Januario | [descrever] |

---

## 11. ANEXOS

### Anexo I – Diário de bordo
Preencha a tabela com Data, Atividade realizada, Responsável(is) e Observações. Preencha também os links de diário individual de cada integrante.

### Anexo II – Cronograma efetivo
Já está preenchido com o cronograma planejado. Atualize com as datas reais de conclusão de cada tarefa.

### Anexo III – Evidências
Substitua o placeholder por links reais de apresentação, vídeo do sistema funcionando, screenshots, etc.

---

## ✅ Checklist final antes da entrega

- [ ] Sumário inclui 3.4 Modelo lógico e 3.5 Dicionário de dados
- [ ] RF04 reescrito (não é duplicado do RF01)
- [ ] RF06 marcado como futuro/fora do escopo
- [ ] RN03: porte sem acento (medio, grande)
- [ ] RN07: status sem andamento, concluido sem acento
- [ ] Dicionário de dados corrigido (tabelas proprietarios, cachorros, agendamentos)
- [ ] Requisitos de hardware/software preenchidos (seção 4.4)
- [ ] Datas preenchidas na tabela 3.7
- [ ] Resultados dos testes preenchidos (seção 4.2)
- [ ] Contribuições individuais descritas (seção 5)
- [ ] Prints de tela inseridos (seção 3.8)
- [ ] Diário de bordo preenchido (Anexo I)
- [ ] Cronograma efetivo atualizado (Anexo II)
- [ ] Evidências com links reais (Anexo III)
- [ ] Link do repositório verificado: https://github.com/7lipe7/PI_2_semestre

---

Após aplicar essas correções, o documento estará consistente com os 4 diagramas que você enviou e com o código do repositório. Se precisar de ajuda para reescrever alguma seção específica inteira, é só avisar.


## 4. DICIONÁRIO DE DADOS (seção 3.5)

Substitua todas as tabelas pelo conteúdo abaixo. As mudanças principais:
- porte: sem acento (medio, grande)
- status: sem andamento, concluido sem acento
- servico: ENUM direto (conforme diagramas) ou servico_id (conforme SQL real)

### Tabela proprietarios

| Campo | Tipo | Nulo | Chave / restrição | Descrição |
|-------|------|------|-------------------|-----------|
| id | INT | Não | PK, auto incremento | Identificador do proprietário |
| nome | VARCHAR(120) | Não | — | Nome do proprietário |
| celular | VARCHAR(20) | Não | — | Telefone celular para contato |
| observacoes | TEXT | Sim | — | Observações livres |
| criado_em | DATETIME | Não | — | Data/hora de criação |
| atualizado_em | DATETIME | Não | — | Data/hora da última alteração |

### Tabela cachorros

| Campo | Tipo | Nulo | Chave / restrição | Descrição |
|-------|------|------|-------------------|-----------|
| id | INT | Não | PK, auto incremento | Identificador do cachorro |
| proprietario_id | INT | Não | FK → proprietarios(id), ON DELETE CASCADE | Proprietário do cachorro |
| nome | VARCHAR(80) | Não | — | Nome do cachorro |
| raca | VARCHAR(80) | Sim | — | Raça |
| porte | ENUM('pequeno','medio','grande') | Sim | pequeno, medio, grande | Porte do cachorro |
| pelagem | VARCHAR(80) | Sim | — | Tipo/cor da pelagem |
| observacoes | TEXT | Sim | — | Observações livres |
| criado_em | DATETIME | Não | — | Data/hora de criação |
| atualizado_em | DATETIME | Não | — | Data/hora da última alteração |

### Tabela agendamentos

| Campo | Tipo | Nulo | Chave / restrição | Descrição |
|-------|------|------|-------------------|-----------|
| id | INT | Não | PK, auto incremento | Identificador do agendamento |
| cachorro_id | INT | Não | FK → cachorros(id), ON DELETE CASCADE | Cachorro atendido |
| servico | ENUM('banho','tosa','banho_e_tosa') | Não | banho, tosa, banho_e_tosa | Serviço contratado |
| data | DATE | Não | UNIQUE(data, hora) | Data do atendimento |
| hora | TIME | Não | UNIQUE(data, hora) | Horário do atendimento |
| status | ENUM('agendado','concluido','cancelado') | Não | agendado, concluido, cancelado | Situação do atendimento (padrão: agendado) |
| observacoes | TEXT | Sim | — | Observações livres |
| criado_em | DATETIME | Não | — | Data/hora de criação |
| atualizado_em | DATETIME | Não | — | Data/hora da última alteração |

> **Nota de consistência:** no init.sql real do repositório, o campo é servico_id (FK para tabela servicos) e o status inclui andamento e concluido (sem acento). Os diagramas enviados mostram servico como ENUM direto e status sem andamento. Escolha um modelo e mantenha consistência entre documento, diagramas e código.

---
# 📋 Correções da Documentação — PI 2º Semestre (DSM)

**Arquivo alvo:** docks/Padrão Documentação ES2_226 - 20_10.docx  
**Base:** 4 diagramas (classes, sequência, ER, modelo lógico) + texto extraído do .docx  
**Como usar:** copie cada bloco abaixo e cole no lugar do conteúdo atual no Word.

---

## 1. SUMÁRIO (adicionar seções omitidas)

O sumário atual pula de 3.3 para 3.6. Substitua o bloco do sumário por:

```
Sumário
1. Apresentação da Empresa ........................ 5
1.1 Missão ...................................... 5
1.2 Visão ....................................... 5
1.3 Valores ..................................... 5
1.4 Link Repositório ............................ 5
2. Escopo do sistema ............................ 6
2.1 Objetivos do projeto ........................ 6
2.2 Técnica de levantamento de requisitos ....... 6
2.3 Requisitos funcionais ....................... 6
2.4 Requisitos não funcionais ................... 6
2.5 Comparativo entre sistemas .................. 7
2.6 Cronograma .................................. 8
3. Documentação do Sistema ...................... 9
3.1 Metodologia de Desenvolvimento .............. 9
3.2 Diagramas UML ............................... 9
3.3 Modelo conceitual ........................... 9
3.4 Modelo lógico ............................... 9
3.5 Dicionário de dados ......................... 9
3.6 Recursos e ferramentas ...................... 9
3.7 Funcionalidades implementadas ............... 9
3.8 Interface do usuário ........................ 9
4. Testes e Qualidade ........................... 10
4.1 Estratégia de Testes ........................ 10
4.2 Resultados dos Testes ....................... 10
4.3 Garantia da Qualidade ....................... 10
4.4 Requisitos mínimos de hardware e software ... 10
4.5 Contrato para desenvolvimento de software ... 10
5. Considerações Finais ......................... 10
6. Referências .................................. 10
Anexo I - Diário de bordo ....................... 10
Anexo II – Cronograma efetivo ................... 10
Anexo III – Evidências .......................... 10
```

---

## 2. REQUISITOS FUNCIONAIS (seção 2.3)

### 2.1 Corrigir RF04 (está duplicado do RF01)

Substituir RF04 por:

```
RF04  Sistema de agendamento  Essencial
O sistema deve permitir cadastrar, consultar, editar e excluir agendamentos,
vinculando cada agendamento a um cachorro cadastrado, definindo serviço
(banho, tosa ou banho e tosa), data, hora e observações. Cada novo agendamento
nasce com status "agendado".
```

### 2.2 Corrigir RF06 (está fora do escopo)

Substituir RF06 por:

```
RF06  Login de usuário  Desejável (versão futura)
O sistema deve permitir o acesso mediante credenciais válidas e impedir o
acesso não autorizado às funcionalidades protegidas. Previsto para evolução
futura; a tabela de usuarios já existe no banco.
```

---

## 3. REGRAS DE NEGÓCIO (seção 3.2 / Tabela 3)

### 3.1 Corrigir RN03 (porte sem acento)

```
RN03  O campo porte do cachorro é opcional e aceita apenas os valores
"pequeno", "medio" ou "grande". Quando não informado, o campo é armazenado
como NULL. Valores fora da lista permitida são rejeitados pelo MySQL quando o
modo SQL estrito está habilitado.
```

### 3.2 Corrigir RN07 (status sem acento e sem "andamento")

```
RN07  Todo agendamento nasce com status agendado. Os status válidos são
agendado, concluido e cancelado (valor inválido volta para `agendado`).
```