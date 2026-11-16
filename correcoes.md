# Correções Pendentes — Documentação PI 2º Semestre

Arquivo fonte validado: `docks/Padrão Documentação ES2_226 - 20_10.docx`
Referência de consistência: código-fonte em `src/`, schema em `docker/php/db/init.sql`, migrations em `docker/php/db/migrations/` e diagramas em `docks/diagramas/`.

---

## 1. Campo `endereco` ausente em proprietários (CRÍTICO)

### Problema
O código e o banco têm `endereco` como `NOT NULL` e obrigatório, mas o Word não menciona esse campo em nenhum lugar relacionado a proprietários.

| Local no Word | Linha | O que diz | Problema |
|--------------|-------|-----------|----------|
| RF02 — Cadastro de proprietários | 131 | "nome e telefone obrigatórios e observações opcionais" | Falta `endereco` obrigatório |
| UC03 — Cadastrar proprietário | 374 | "informar nome e celular e, opcionalmente, observações" | Falta `endereco` |
| Dicionário — tabela `proprietarios` | 559–589 | Lista: id, nome, celular, observacoes, criado_em, atualizado_em | **Campo `endereco` completamente ausente** |

### Correção necessária
- No dicionário de dados (seção 3.5), adicionar à tabela `proprietarios`:
  - `endereco` | `VARCHAR(255)` | Não | — | Endereço completo (rua, número, bairro, cidade)
- Atualizar RF02 para incluir `endereco` como obrigatório.
- Atualizar fluxo do UC03 para incluir preenchimento de endereço.

---

## 2. Dicionário de dados — tabela `agendamentos` com erros (CRÍTICO)

### Problema
Várias divergências entre o Word e o schema real do banco (`init.sql` + `modelo-logico.puml`).

| Linha Word | Campo | O que o Word diz | O que está no banco | Problema |
|-----------|-------|-----------------|---------------------|----------|
| 683–687 | `servico` | `id, nome, descricao, preco, ativo` / valores `banho, tosa, banho_e_tosa` | `servico_id INT UNSIGNED NOT NULL` com FK para `servicos(id)`, ON DELETE RESTRICT | **Campo inexistente no banco; não é enum** |
| 691, 696 | `data`, `hora` | `UNIQUE(data, hora)` em cada coluna | `UNIQUE(data, hora)` composta | Restrição composta (OK, mas descrever como composta) |
| 701 | `status` | `agendado, concluído, cancelado` | `agendado, andamento, concluido, cancelado` | **Falta `andamento`** |
| 709, 714 | `criado_em`, `atualizado_em` | `DATETIME` | `TIMESTAMP` | **Tipo errado** |

### Correção necessária
- Corrigir `servico` para `servico_id INT UNSIGNED Não FK → servicos(id), ON DELETE RESTRICT`.
- Adicionar `andamento` ao ENUM de `status`.
- Corrigir `criado_em` e `atualizado_em` para `TIMESTAMP`.
- Descrever `UNIQUE(data, hora)` como restrição composta.

---

## 3. Casos de uso incompletos — faltam UC15 a UC18 (CRÍTICO)

### Problema
O diagrama `.puml` e o `CORRECOES_DOCUMENTACAO.md` incluem UC15–UC18, mas o Word só lista até UC14.

| UC | Nome | No Word? | No .puml? |
|----|------|----------|-----------|
| UC15 | Gerenciar serviços | ❌ Não | ✅ Sim |
| UC16 | Consultar histórico de status | ❌ Não | ✅ Sim |
| UC17 | Autenticar-se | ❌ Não | ✅ Sim |
| UC18 | Encerrar sessão | ❌ Não | ✅ Sim |

A Tabela 5 (Relação UC × Tabelas, linhas 788–806) também não menciona `servicos`, `historico_agendamentos`, `usuarios` e `login_tentativas`.

### Correção necessária
- Adicionar UC15–UC18 na Tabela 2 (Lista de casos de uso).
- Atualizar a Tabela 5 para incluir:
  - UC15 — Gerenciar serviços: `servicos`; `agendamentos` consultada para verificar se está em uso.
  - UC16 — Consultar histórico: `historico_agendamentos`; `vw_historico_agendamentos` relaciona `agendamentos`, `cachorros`, `proprietarios`, `servicos`.
  - UC17 — Autenticar-se: `usuarios`, `login_tentativas`.
  - UC18 — Encerrar sessão: sessão da aplicação; não altera tabelas do banco.

---

## 4. UC13 modelado como «include» de UC12 (INCONSISTÊNCIA)

### Problema
- **Word** (linha 310, 526): "Alterar status (UC13) é realizada durante a edição do agendamento (UC12), sendo, por isso, modelada como um caso de uso incluído («include»)." e "Não existe tela exclusiva: o campo de status só aparece na edição".
- **Diagrama .puml**: UC13 é um caso de uso independente.
- **Código**: `POST /agendamentos/{id}/status` é uma rota independente, com botões rápidos no dashboard.

O `CORRECOES_DOCUMENTACAO.md` (seção 6) determina: "Mostre UC13 como independente de UC12".

### Correção necessária
- Alterar a descrição de UC13 na Tabela 2 e no fluxo detalhado para indicar que é um caso de uso independente, disponível na edição e por ações rápidas.
- Remover a afirmação de que UC13 é «include» de UC12.

---

## 5. Modelo conceitual omite `Servico` (INCONSISTÊNCIA)

### Problema
- **Word** (linha 545): "três entidades: proprietário, cachorro e agendamento".
- **Banco e .puml**: 7 tabelas, incluindo `servicos`.

### Correção necessária
- Incluir `Servico` como entidade no modelo conceitual com as três relações 1:N (proprietário→cachorro, cachorro→agendamento, serviço→agendamento).

---

## 6. Modelo lógico — afirmação incorreta sobre FKs (INCONSISTÊNCIA)

### Problema
- **Word** (linha 551): "As chaves estrangeiras usam ON DELETE CASCADE".
- **Banco**: `servico_id` usa `ON DELETE RESTRICT`, não CASCADE.

O `CORRECOES_DOCUMENTACAO.md` (seção 6) alerta explicitamente: "Não afirme que todas as FKs usam CASCADE."

### Correção necessária
- Especificar que a FK de `servico_id` em `agendamentos` usa `ON DELETE RESTRICT`.
- As FKs de `cachorro_id`/`proprietario_id` e `historico_agendamentos.agendamento_id` usam `ON DELETE CASCADE`.

---

## 7. Sumário não atualizado (PENDENTE)

### Problema
- **Word** (linhas 47–77): O sumário aparece como código de campo Word (`TOC \z \o "1-9" ...`) em vez de um sumário atualizado automaticamente com os números de página corretos.

O `CORRECOES_DOCUMENTACAO.md` (checklist item 1) exige: "Sumário atualizado automaticamente e inclui as seções existentes."

### Correção necessária
- Atualizar o sumário no Word (selecionar sumário → F9 → Atualizar campo).

---

## 8. Resultados dos testes sem data (PENDENTE)

### Problema
- **Word** (seção 4.2, linhas 903–977): Todos os 17 casos de teste (CT01–CT17) estão marcados como "Passou" ou "passou", mas **não há data de execução** em nenhum deles.

O `CORRECOES_DOCUMENTACAO.md` (seção 9) orienta: "Não preencha os resultados como 'Passou' sem executar cada teste e registrar a data real, o ambiente e o resultado observado."

### Correção necessária
- Adicionar coluna de data de execução e ambiente a cada caso de teste.
- Ou registrar como "Não executado" os testes que ainda não foram rodados com evidência real.

---

## 9. Contrato com campos de data em branco (PENDENTE)

### Problema
- **Word** (linha 1033): "Araras/SP, _____ de __________________________ de 2026." — Data não preenchida.

O `CORRECOES_DOCUMENTACAO.md` (checklist item 11) alerta para resolver campos pendentes do contrato ou deixar explícito o caráter acadêmico.

### Correção necessária
- Preencher a data do contrato ou identificar explicitamente que se trata de um modelo acadêmico.

---

## 10. Seção 3.8 — Interface sem evidências (PENDENTE)

### Problema
- **Word** (linhas 886–892): Apenas placeholders "Inserir print: Tela inicial – agenda do dia", etc. Não há capturas de tela reais.

### Correção necessária
- Inserir prints das telas com legenda e descrição breve.

---

## 11. Anexo III — Evidências não preenchidas (PENDENTE)

### Problema
- **Word** (linha 1106): "AQUI PODE COLOCAR LINK DE APRESENTAÇÃO E LIVE COISAS EXTERNAS"

### Correção necessária
- Substituir por links reais de apresentação, vídeo e capturas, se existirem.

---

## 12. Pequenos detalhes

| Local | Linha | Problema | Correção |
|-------|-------|----------|----------|
| Dicionário — `porte` | 614 | "pequeno, médio, grande" (com acento) | Banco usa `pequeno, medio, grande` (sem acento) |
| Dicionário — `historico_agendamentos` | 730–735 | Usa `&apos;` (entidade XML não renderizada) | Deveria ser `'` simples |

---

## Checklist antes de entregar (do CORRECOES_DOCUMENTACAO.md)

- [ ] Sumário atualizado automaticamente e inclui as seções existentes.
- [ ] Escopo, RF06, status e serviços correspondem à versão implantada.
- [ ] RF04 descreve agendamentos, sem duplicar o cadastro de cachorros. ✅ Já correto
- [ ] Regras de negócio distinguem campo porte obrigatório na tela de coluna anulável no banco. ✅ Já correto
- [ ] Dicionário documenta as sete tabelas, campos, tipos, nulabilidade, chaves, defaults e ações referenciais corretos. ❌ Pendente (itens 1 e 2)
- [ ] Casos de uso incluem autenticação, serviços, histórico e alteração rápida de status. ❌ Pendente (item 3)
- [ ] Diagramas PlantUML, SVGs e imagens inseridas no Word foram sincronizados. ❌ Pendente
- [ ] Diagrama de sequência identifica a classe `Agendamento` e representa a verificação prévia e a restrição UNIQUE. ✅ Já correto
- [ ] Conflito entre nome da constraint e tratamento de exceção foi corrigido no controller. ✅ Já correto
- [ ] Datas, resultados de testes, contribuições, capturas e anexos refletem evidências reais. ❌ Pendente (itens 8, 10, 11)
- [ ] Campos pendentes do contrato estão resolvidos ou o documento está claramente identificado como modelo acadêmico. ❌ Pendente (item 9)
- [ ] Referências, links e dados comparativos foram verificados. ❌ Pendente

---

## Arquivos de referência no repositório

| Arquivo | Caminho |
|---------|---------|
| Documento Word a ser corrigido | `docks/Padrão Documentação ES2_226 - 20_10.docx` |
| Checklist de correções | `docks/diagramas/CORRECOES_DOCUMENTACAO.md` |
| Schema inicial do banco | `docker/php/db/init.sql` |
| Migrations | `docker/php/db/migrations/001_*.sql` a `005_*.sql` |
| Código fonte | `src/Controllers/`, `src/Models/`, `public/index.php` |
| Diagramas PlantUML | `docks/diagramas/*.puml` |
| Documentação técnica | `README.md`, `passos.txt` |


---