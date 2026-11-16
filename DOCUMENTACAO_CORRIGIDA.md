# Projeto Integrador 2º Semestre - DSM

Disciplinas:
Banco de Dados
Desenvolvimento Web II
Engenharia de Software II

Professores:
Bruno
Maurício

Grupo(n) / Nome da Empresa: Code & Coffee
Sistema: Amora Banho e Tosa

| Integrante | Papel Principal |
|---|---|
| Ana Luiza Oliveira de Jesus | Dev. Front-end |
| Felipe Santana Lopes | Dev. Back-end | Banco de dados |
| Guilherme Cardoso Hubner | Banco dados |
| Kaique Ribeiro dos Santos | Documentação | Entrevista |
| Vinicius Tadeu Silvestre Januario | Documentação |

Fatec Araras
2026

## FICHA DE CONTROLE - PROJETO INTERDISCIPLINAR

DISCIPLINA CHAVE: Engenharia de Software II - PI II
PROFESSOR: Bruno Henrique de Paula Ferreira

GRUPO: Code & Coffee            SEMESTRE: 2/2026

TÍTULO DO PROJETO: Sistema Amora Banho e Tosa
DATA DA APRESENTAÇÃO: 24/11/2026
NOTA:

INTEGRANTES DO GRUPO: Code & Coffee
| Nome | Nota Individual |
|---|---|
| Ana Luiza Oliveira de Jesus | |
| Felipe Santana Lopes | |
| Guilherme Cardoso Hubner | |
| Kaique Ribeiro dos Santos | |
| Vinicius Tadeu Silvestre Januario | |

Araras, 24 de novembro de 2026

__________________________________
Professor Bruno Henrique de Paula Ferreira

## Sumário

1. Apresentação da Empresa — 5
1.1 Missão — 5
1.2 Visão — 5
1.3 Valores — 5
1.4 Link Repositório — 5
2. Escopo do sistema — 6
2.1 Objetivos do projeto — 6
2.2 Técnica de levantamento de requisitos — 6
2.3 Requisitos funcionais — 6
2.4 Requisitos não funcionais — 6
2.5 Comparativo entre sistemas — 7
2.6 Cronograma — 8
3. Documentação do Sistema — 9
3.1 Metodologia de Desenvolvimento — 9
3.2 Diagramas UML — 9
3.3 Modelagem de Banco de dados — 9
3.6 Recursos e ferramentas — 9
3.7 Funcionalidades implementadas — 9
3.8 Interface do usuário — 9
4. Testes e Qualidade — 10
4.1 Estratégia de Testes — 10
4.2 Resultados dos Testes — 10
4.3 Garantia da Qualidade — 10
4.4 Requisitos mínimos de hardware e software para o sistema — 10
4.5 Contrato para desenvolvimento de software — 10
5. Considerações Finais — 10
6. Referências — 10
Anexo I - Diário de bordo — 10
Anexo II – Cronograma efetivo — 10
Anexo III – Evidências — 10

> **Nota (item 7 do checklist):** este sumário é um campo de campo Word (TOC). No Word, selecione o sumário → F9 → Atualizar campo para refletir os números de página reais e incluir todas as seções existentes.

## 1. Apresentação da Empresa

A Code & Coffee é a equipe responsável pelo desenvolvimento do sistema, formada por Ana Luiza Oliveira de Jesus, Felipe Santana Lopes, Guilherme Cardoso Hubner, Kaique Ribeiro dos Santos e Vinicius Tadeu Silvestre Januario, alunos do curso de Desenvolvimento de Software Multiplataforma (DSM) da Fatec Araras.

O cliente atendido pelo projeto é a Amora Banho e Tosa, estabelecimento de banho e tosa que precisa organizar seus atendimentos e agendamentos. A identidade visual abaixo reúne o logotipo da empresa e o do cliente.

### 1.1 Missão

Oferecer serviços de banho e tosa de alta qualidade, proporcionando higiene, conforto e bem-estar aos pets, com cuidado, segurança e atendimento personalizado.

### 1.2 Visão

Ser referência em banho e tosa na região, reconhecida pela qualidade dos serviços, pelo cuidado com os pets e pela confiança dos clientes, buscando crescer e oferecer cada vez mais conforto e bem-estar aos animais.

### 1.3 Valores

Nossa empresa tem como princípios o amor e o respeito aos animais, o cuidado e a segurança em todos os serviços, a qualidade no atendimento, a higiene e a responsabilidade. Buscamos sempre agir com ética, transparência e compromisso com o bem-estar dos pets, construindo uma relação de confiança e respeito com nossos clientes.

### 1.4 Link Repositório

Repositório: https://github.com/7lipe7/PI_2_semestre

## 2. Escopo do sistema

O sistema Amora Banho e Tosa tem como objetivo facilitar a organização e o gerenciamento dos atendimentos do estabelecimento. A aplicação centraliza os cadastros de proprietários e cachorros, permite registrar e consultar agendamentos e oferece recursos para acompanhar a situação dos atendimentos.

O sistema foi desenvolvido para apoiar a rotina operacional do estabelecimento, reduzindo a dependência de controles manuais e facilitando a consulta das informações.

**Funcionalidades incluídas no escopo**
- Cadastro, consulta, edição e exclusão de proprietários.
- Cadastro, consulta, edição e exclusão de cachorros vinculados aos respectivos proprietários.
- Cadastro, consulta, edição e exclusão de agendamentos.
- Seleção do serviço contratado e definição de data e horário.
- Consulta da agenda diária, com ordenação por horário.
- Controle da situação dos atendimentos.
- Validação dos dados e prevenção de conflitos de horário.
- Gerenciamento de serviços.
- Autenticação e controle de acesso.

**Fora do escopo da versão atual**
- Venda de produtos, pois não é necessária para o objetivo principal do projeto;
- Relatórios financeiros e valores dos serviços – previstos como melhoria futura (RF09).

### 2.1 Objetivos do projeto

O objetivo geral do projeto é desenvolver um sistema web que organize e automatize o agendamento e o gerenciamento dos atendimentos de banho e tosa da Amora, atendendo melhor a demanda de clientes e agendamentos e tornando o serviço mais automatizado e eficiente. Como objetivos específicos, o sistema deve:

- Centralizar o cadastro de proprietários e de seus cachorros;
- Permitir o agendamento de serviços sem conflito de horários;
- Apresentar a agenda do dia para apoiar a rotina do estabelecimento;
- Registrar o andamento de cada atendimento (agendado, em andamento, concluído, cancelado);
- Garantir a integridade dos dados, inclusive na exclusão de registros relacionados;
- Oferecer uma interface simples, acessível e adaptável a celulares, tablets e computadores.

### 2.2 Técnica de levantamento de requisitos

Foram utilizadas duas técnicas de levantamento de requisitos: entrevista com a cliente, responsável pela Amora Banho e Tosa, e questionário.

A entrevista permitiu compreender a rotina do estabelecimento, a forma como os atendimentos são agendados e controlados e as principais dificuldades enfrentadas. O questionário complementou as informações obtidas na entrevista, permitindo identificar os serviços oferecidos, os dados necessários para o cadastro dos proprietários e de seus cachorros e as expectativas em relação ao sistema.

Com base nas informações coletadas, foram identificadas as seguintes necessidades para o sistema:

- Registrar proprietários e seus cachorros, incluindo nome, contato, endereço, raça, porte, pelagem e observações.
- Agendar serviços de banho, tosa ou banho e tosa em datas e horários definidos, evitando a sobreposição de atendimentos.
- Visualizar rapidamente os atendimentos agendados para o dia.
- Acompanhar o status dos atendimentos, permitindo identificar aqueles que estão agendados, em andamento, concluídos ou cancelados.

### 2.3 Requisitos funcionais

Requisitos funcionais são declarações dos serviços que o sistema deve fornecer, do modo como o sistema deve reagir a determinadas entradas e de como deve se comportar em determinadas situações. (Sommerville)

Exemplo de descrição:

| RF | Nome | Prioridade | Descrição |
|---|---|---|---|
| RF01 | Cadastro de cachorros | Essencial | O sistema deve permitir cadastrar, listar, editar e excluir cachorros, sempre vinculados a um proprietário, informando nome e, opcionalmente, raça, porte, pelagem e observações. |
| **RF02** | **Cadastro de proprietários** | **Essencial** | **O sistema deve permitir cadastrar, listar, editar e excluir proprietários, com nome, celular (telefone) e endereço obrigatórios e observações opcionais.** |
| RF03 | Sistema de agendamento | Essencial | O sistema deve exibir, na página inicial, os agendamentos do dia atual ordenados por horário, com cachorro, proprietário, serviço e status. |
| RF04 | Sistema de atendimento | Essencial | O sistema deve permitir cadastrar, consultar, editar e excluir agendamentos, vinculando cada agendamento a um cachorro e a um serviço cadastrados, com data, hora e observações opcionais. Um novo agendamento deve ser criado com o status “agendado”. O sistema deve impedir que dois agendamentos ocupem a mesma data e hora. |
| RF05 | Busca nos formulários | Importante | O sistema deve disponibilizar mecanismos de busca nos formulários de cadastro ou edição em que essa funcionalidade estiver implementada, permitindo localizar os registros necessários à seleção de proprietários ou cachorros. |
| RF06 | Informações de visitas e gastos por proprietário | Implementado | O sistema apresenta a quantidade de visitas concluídas e o total gasto estimado por proprietário, com base nos preços dos serviços associados aos agendamentos concluídos. Relatórios financeiros gerais permanecem fora do escopo atual. |
| RF07 | Sistema de finanças | Desejável | O sistema deve ter um relatório de clientes e os valores dos serviços (evolução futura). |

### 2.4 Requisitos não funcionais

Requisitos não funcionais são restrições sobre os serviços ou funções oferecidas pelo sistema. Eles incluem restrições de tempo, restrições sobre o processo de desenvolvimento e restrições impostas por padrões. Os requisitos não funcionais se aplicam, frequentemente, ao sistema como um todo, em vez de às características individuais ou aos serviços. (Sommerville)

Exemplo de descrição:

| RNF | Nome | Versão | Descrição |
|---|---|---|---|
| RNF01 | Usabilidade | 1 | O sistema deve ser simples e fácil de utilizar, mesmo para usuários com pouca experiência. |
| RNF02 | Responsividade | 1 | O sistema deve ser responsivo e se adaptar a celulares, tablets e computadores. |
| RNF03 | Acessibilidade | 1 | O sistema deve facilitar o uso por pessoas com diferentes necessidades, utilizando textos legíveis, bom contraste e navegação adequada. |
| RNF04 | Integridade dos dados | 1 | O sistema deve garantir a consistência dos dados por meio de restrições no banco (horário único por data e relacionamentos com exclusão em cascata) e de confirmação antes de exclusões. |
| RNF05 | Segurança básica | 1 | O sistema deve acessar o banco de dados via PDO e escapar o conteúdo exibido nas telas (helper e()) para reduzir riscos de injeção de código. |

### 2.5 Comparativo entre sistemas

Para posicionar o sistema, foi feita uma comparação com a Petz, rede de pet shops que oferece agendamento de banho e tosa pelo site e aplicativo, e com o controle por agenda manual (caderno, planilha ou mensagens), forma comum em pequenos estabelecimentos. A Petz é uma solução ampla de varejo e serviços para pets; o Amora Banho e Tosa é uma solução enxuta, focada na rotina interna do estabelecimento: cadastro, agenda e controle dos atendimentos.

**Tabela 1 – Comparativo das funcionalidades da aplicação**

| Funcionalidades | Petz | Agenda manual | Amora Banho e Tosa |
|---|---|---|---|
| Agendamento de banho e tosa | X | X | X |
| Cadastro de proprietário e cachorro | X | - | X |
| Impedir horário duplicado | X | - | X |
| Agenda do dia ordenada por horário | - | - | X |
| Controle de status do atendimento | - | - | X |
| Venda de produtos | X | - | - |
| Acesso por celular, tablet e computador | X | - | X |

### 2.6 Cronograma

| Tarefas | 26/08 a 02/09 | 02/09 a 09/09 | 09/09 a 16/09 | 16/09 a 30/09 | 30/09 a 14/10 | 21/10 a 11/11 | 11/11 a 20/11 | 24/11 |
|---|---|---|---|---|---|---|---|---|
| Definição Grupos | X | | | | | | | |
| Criação Marca Empresa | | X | | | | | | |
| Definição Empresa | | | X | | | | | |
| Escopo Sistema | | X | X | | | | | |
| Requisitos | | X | X | X | X | | | |
| Diagramas | | X | X | X | | | | |
| Protótipo | | X | X | X | X | | | |
| Documentação | | X | X | X | X | X | | |
| Desenvolvimento | | | | X | X | X | X | |
| Entrega | | | | | | | X | |
| Apresentação | | | | | | | | X |

## 3. Documentação do Sistema

Neste capítulo, são apresentados os principais documentos técnicos relacionados ao desenvolvimento do sistema de gerenciamento para Amora banho e tosa. A documentação tem como objetivo apresentar as características, funcionalidades e estrutura do sistema, facilitando a compreensão de seu funcionamento. Também são registradas as principais decisões tomadas durante o desenvolvimento, contribuindo para futuras manutenções, melhorias e atualizações do sistema.

### 3.1 Metodologia de Desenvolvimento

O desenvolvimento seguiu uma abordagem ágil e incremental, com entregas parciais organizadas pelo cronograma da seção 2.6: levantamento de requisitos, modelagem (UML e banco de dados), prototipação, implementação e testes. O código foi versionado em repositório Git/GitHub e as atividades foram divididas entre os integrantes da equipe.

### 3.2 Diagramas UML

A modelagem UML do sistema representa dois níveis: o modelo conceitual, com as entidades do negócio, e o modelo arquitetural, com a organização do código (padrão MVC).

**Diagrama de caso de uso**

O sistema possui um único ator, o Funcionário, responsável pelo atendimento e pelo gerenciamento de proprietários, cachorros e agendamentos. O acesso às funcionalidades é realizado por meio de autenticação, permitindo que o funcionário entre no sistema utilizando suas credenciais. As funcionalidades administrativas são disponibilizadas conforme as permissões de acesso estabelecidas pelo sistema.

Os casos de uso estão organizados em quatro módulos: Autenticação, Proprietários, Cachorros e Agenda. A alteração de status (UC13) é um caso de uso independente, disponível na edição do agendamento e por ações rápidas na listagem/dashboard.

**Tabela 2 – Lista de casos de uso**

| Código | Caso de uso | Descrição |
|---|---|---|
| UC01 | Consultar agenda do dia | Exibe os agendamentos do dia atual ordenados por horário. |
| UC02 | Listar proprietários | Exibe os proprietários cadastrados. |
| UC03 | Cadastrar proprietário | Permite cadastrar um novo proprietário, obrigatoriamente com nome, celular e endereço. |
| UC04 | Editar proprietário | Permite alterar os dados de um proprietário. |
| UC05 | Excluir proprietário | Remove um proprietário e seus dados relacionados. |
| UC06 | Listar cachorros | Exibe os cachorros cadastrados e seus proprietários. |
| UC07 | Cadastrar cachorro | Permite cadastrar um cachorro vinculado a um proprietário. |
| UC08 | Editar cachorro | Permite alterar os dados de um cachorro. |
| UC09 | Excluir cachorro | Remove um cachorro e seus agendamentos. |
| UC10 | Listar agendamentos | Exibe os agendamentos cadastrados. |
| UC11 | Criar agendamento | Permite criar um novo agendamento para um cachorro. |
| UC12 | Editar agendamento | Permite alterar os dados de um agendamento (remarcar). |
| UC13 | Alterar status | Altera o status do agendamento (caso de uso independente, disponível na edição e por ações rápidas). |
| UC14 | Excluir agendamento | Remove um agendamento do sistema. |
| UC15 | Gerenciar serviços | Permite cadastrar, listar, editar e inativar serviços, verificando se estão em uso em agendamentos. |
| UC16 | Consultar histórico de status | Exibe o histórico de alterações de status dos agendamentos. |
| UC17 | Autenticar-se | Permite o login do funcionário no sistema. |
| UC18 | Encerrar sessão | Permite o logout do funcionário, encerrando a sessão da aplicação. |

**Tabela 3 – Regras de negócio**

| Código | Regra |
|---|---|
| RN01 | Nome, celular e endereço do proprietário são obrigatórios. |
| RN02 | Todo cachorro deve estar vinculado a um proprietário. |
| RN03 | Na interface, o porte do cachorro deve ser selecionado entre pequeno, medio e grande. O banco aceita NULL, mas o formulário exige preenchimento. |
| RN04 | Raça, pelagem e observações do cachorro são opcionais. O porte é obrigatório no formulário, embora a coluna aceite NULL. |
| RN05 | Todo agendamento exige um cachorro, um serviço, uma data e uma hora válidos. Observações são opcionais. |
| RN06 | Não pode haver mais de um agendamento para a mesma combinação de data e hora, inclusive quando o agendamento existente estiver cancelado. |
| RN07 | Um novo agendamento recebe o status agendado. Os valores aceitos são agendado, andamento, concluido e cancelado. A aplicação apresenta rótulos traduzidos nas telas. |
| RN08 | Excluir um proprietário apaga, por cascata, seus cachorros e os agendamentos associados a esses cachorros. |
| RN09 | Excluir um cachorro apaga, por cascata, os agendamentos associados. |
| RN10 | A aplicação solicita confirmação antes das ações de exclusão apresentadas na interface. |
| RN11 | Um serviço associado a agendamentos não pode ser removido fisicamente; a aplicação o inativa. A chave estrangeira impede sua exclusão enquanto estiver referenciado (ON DELETE RESTRICT). |
| RN12 | Alterações de status são registradas no histórico. Excluir o agendamento apaga também seu histórico. |

**Descrições dos casos de uso**

**UC01 – Consultar agenda do dia**
Ator: Funcionário
Objetivo: Visualizar os atendimentos programados para o dia atual.
Fluxo principal:
1. O funcionário acessa a página inicial.
2. O sistema identifica a data atual.
3. O sistema consulta os agendamentos da data.
4. Os agendamentos são ordenados pelo horário.
5. O sistema exibe cachorro, proprietário, serviço, horário e status.
Resultado: Os atendimentos do dia são exibidos ao funcionário.

**UC02 – Listar proprietários**
Ator: Funcionário
Objetivo: Consultar os proprietários cadastrados.
Fluxo principal:
1. O funcionário acessa a área de proprietários.
2. O sistema consulta os registros cadastrados.
3. O sistema apresenta a lista de proprietários.
Resultado: Os proprietários cadastrados são exibidos.

**UC03 – Cadastrar proprietário**
Ator: Funcionário
Objetivo: Cadastrar um novo proprietário.
Fluxo principal:
1. O funcionário acessa o cadastro de proprietário.
2. O sistema apresenta o formulário.
3. O funcionário informa nome, celular, endereço e, opcionalmente, observações.
4. O funcionário confirma o cadastro.
5. O sistema valida os dados obrigatórios e salva o proprietário.
6. O sistema retorna para a lista de proprietários.
Fluxos alternativos e regras:
- Se nome, celular ou endereço estiverem vazios, o JavaScript da tela bloqueia o envio do formulário.
- Regra: RN01.
Resultado: O proprietário é cadastrado.

**UC04 – Editar proprietário**
Ator: Funcionário
Objetivo: Alterar os dados de um proprietário.
Fluxo principal:
1. O funcionário seleciona um proprietário.
2. O sistema apresenta os dados atuais.
3. O funcionário altera os dados e confirma.
4. O sistema valida os dados e atualiza o registro.
Fluxos alternativos e regras:
- Se o proprietário não existir, o sistema responde com erro 404 ("Cliente não encontrado.").
- Regra: RN01.
Resultado: Os dados do proprietário são atualizados.

**UC05 – Excluir proprietário**
Ator: Funcionário
Objetivo: Remover um proprietário do sistema.
Fluxo principal:
1. O funcionário seleciona um proprietário.
2. O sistema solicita confirmação, avisando que os cachorros e agendamentos dele também serão apagados.
3. O funcionário confirma a exclusão.
4. O sistema remove o proprietário.
5. Os cachorros vinculados e seus agendamentos são removidos por efeito de cascata.
Fluxos alternativos e regras:
- Regras: RN08 e RN10.
Resultado: O proprietário e seus dados dependentes são removidos.

**UC06 – Listar cachorros**
Ator: Funcionário
Objetivo: Consultar os cachorros cadastrados.
Fluxo principal:
1. O funcionário acessa a área de cachorros.
2. O sistema consulta os cachorros e recupera também os dados do proprietário (JOIN).
3. O sistema apresenta a lista.
Resultado: Os cachorros são exibidos juntamente com seus proprietários.

**UC07 – Cadastrar cachorro**
Ator: Funcionário
Objetivo: Cadastrar um cachorro e vinculá-lo a um proprietário.
Fluxo principal:
1. O funcionário acessa o cadastro de cachorro.
2. O sistema apresenta uma lista com busca (por nome do pet ou do dono) com os proprietários disponíveis.
3. O funcionário seleciona o proprietário e informa o nome do cachorro.
4. Opcionalmente, informar raça, porte, pelagem e observações.
5. O funcionário confirma; o sistema valida os dados e salva o cachorro.
Fluxos alternativos e regras:
- Se nome ou proprietário não forem informados, o sistema reapresenta o formulário com o JavaScript da tela bloqueia o envio sem proprietário selecionado.
- Regras: RN02, RN03 e RN04.
Resultado: O cachorro é cadastrado e vinculado ao proprietário.

**UC08 – Editar cachorro**
Ator: Funcionário
Objetivo: Alterar os dados cadastrados de um cachorro.
Fluxo principal:
1. O funcionário seleciona um cachorro.
2. O sistema apresenta seus dados.
3. O funcionário realiza as alterações e confirma.
4. O sistema valida os dados e atualiza o cadastro.
Fluxos alternativos e regras:
- Se o cachorro não existir, o sistema responde com erro 404 ("Cachorro não encontrado.").
Resultado: Os dados do cachorro são atualizados.

**UC09 – Excluir cachorro**
Ator: Funcionário
Objetivo: Remover um cachorro do sistema.
Fluxo principal:
1. O funcionário seleciona um cachorro.
2. O sistema solicita confirmação, avisando que os agendamentos dele também serão apagados.
3. O funcionário confirma.
4. O sistema remove o cachorro e, por cascata, seus agendamentos.
Fluxos alternativos e regras:
- Regras: RN09 e RN10.
Resultado: O cachorro e seus agendamentos são removidos.

**UC10 – Listar agendamentos**
Ator: Funcionário
Objetivo: Consultar os agendamentos cadastrados.
Fluxo principal:
1. O funcionário acessa a área de agendamentos.
2. O sistema consulta os registros e os relaciona ao cachorro e ao proprietário (JOINs).
3. O sistema apresenta data, hora, pet, dono, serviço e status.
Resultado: Os agendamentos são apresentados ao funcionário.

**UC11 – Criar agendamento**
Ator: Funcionário
Objetivo: Registrar um atendimento para um cachorro.
Fluxo principal:
1. O funcionário acessa o cadastro de agendamento.
2. O sistema apresenta uma lista com busca dos cachorros cadastrados (por pet ou dono).
3. O funcionário seleciona o cachorro e o serviço (Banho, Tosa ou Banho e Tosa).
4. O funcionário informa data e horário e, opcionalmente, observações.
5. O funcionário confirma; o sistema valida os dados obrigatórios e tenta gravar o agendamento.
6. O sistema retorna para a lista de agendamentos.
Fluxos alternativos e regras:
- Horário ocupado: o banco rejeita o registro (UNIQUE), o sistema captura o PDOException e reapresenta o formulário com "Este horário já está ocupado, escolha outro.", preservando os dados.
- Dados incompletos: "Cachorro, serviço, data e hora são obrigatórios.".
- O status nasce como "agendado".
- Regras: RN05, RN06 e RN07.
Resultado: O agendamento é registrado.

**UC12 – Editar agendamento (remarcar)**
Ator: Funcionário
Objetivo: Alterar os dados de um agendamento existente.
Fluxo principal:
1. O funcionário seleciona um agendamento.
2. O sistema apresenta os dados atuais, incluindo o campo de status.
3. O funcionário altera cachorro, serviço, data, horário, status ou observações e confirma.
4. O sistema valida os dados e tenta atualizar o registro.
5. O sistema atualiza o agendamento e retorna para a lista.
Fluxos alternativos e regras:
- Horário ocupado: o banco rejeita a atualização (UNIQUE) e o sistema reapresenta o formulário com "Este horário já está ocupado, escolha outro.".
- Se o agendamento não existir, o sistema responde com erro 404 ("Agendamento não encontrado.").
- Regra: RN06.
Resultado: O agendamento é atualizado.

**UC13 – Alterar status do agendamento (caso de uso independente)**
Ator: Funcionário
Objetivo: Atualizar a situação do atendimento (agendado, andamento, concluido ou cancelado).
Fluxo principal:
1. O funcionário acessa a edição de um agendamento (UC12) ou utiliza uma ação rápida de status na listagem/dashboard.
2. O funcionário escolhe o novo status.
3. O sistema valida se o status está na lista permitida.
4. O sistema atualiza o registro e registra a mudança no histórico.
Fluxos alternativos e regras:
- O valor só é aceito se estiver na lista (inválido volta para "agendado").
- Regra: RN07.
Resultado: O status do atendimento é atualizado.

**UC14 – Excluir agendamento**
Ator: Funcionário
Objetivo: Remover um agendamento.
Fluxo principal:
1. O funcionário seleciona um agendamento.
2. O sistema solicita confirmação ("Cancelar este agendamento?").
3. O funcionário confirma.
4. O sistema remove o registro e o horário fica novamente disponível.
Fluxos alternativos e regras:
- Regra: RN10.
Resultado: O agendamento é removido.

**UC15 – Gerenciar serviços**
Ator: Funcionário
Objetivo: Cadastrar, listar, editar e inativar serviços de banho e tosa.
Fluxo principal:
1. O funcionário acessa o cadastro de serviços.
2. O sistema apresenta a lista de serviços cadastrados.
3. O funcionário cadastra, edita ou inativa um serviço informando nome, descrição, preço e disponibilidade.
4. O sistema valida e salva as alterações.
Fluxos alternativos e regras:
- Um serviço associado a agendamentos não pode ser excluído fisicamente; somente inativado (RN11).
- O sistema consulta a tabela de agendamentos para verificar se o serviço está em uso antes de permitir a exclusão.
Resultado: Os serviços são gerenciados no catálogo do sistema.

**UC16 – Consultar histórico de status**
Ator: Funcionário
Objetivo: Consultar o histórico de alterações de status dos agendamentos.
Fluxo principal:
1. O funcionário acessa a tela de histórico.
2. O sistema consulta a view `vw_historico_agendamentos`, que relaciona agendamentos, cachorros, proprietários e serviços.
3. O sistema apresenta o histórico com status anterior, novo status e data da alteração.
Resultado: O histórico de alterações de status é exibido ao funcionário.

**UC17 – Autenticar-se**
Ator: Funcionário
Objetivo: Entrar no sistema com credenciais válidas.
Fluxo principal:
1. O funcionário acessa a tela de login.
2. O funcionário informa login e senha.
3. O sistema valida as credenciais contra a tabela `usuarios` e registra a tentativa em `login_tentativas`.
4. Ao sucesso, o sistema cria a sessão autenticada e redireciona para o dashboard.
Fluxos alternativos e regras:
- Credenciais inválidas: o sistema exibe erro e registra a tentativa de login para controle de força bruta.
Resultado: O funcionário fica autenticado no sistema.

**UC18 – Encerrar sessão**
Ator: Funcionário
Objetivo: Encerrar a sessão autenticada.
Fluxo principal:
1. O funcionário aciona a opção de logout.
2. O sistema encerra a sessão da aplicação e redireciona para a tela de login.
3. Não há alteração de tabelas do banco de dados.
Resultado: A sessão do funcionário é encerrada.

**Diagrama de classes**

O modelo de domínio possui quatro entidades: proprietário, cachorro, serviço e agendamento. Um proprietário pode possuir vários cachorros e cada cachorro pertence a um único proprietário; um cachorro pode ter vários agendamentos e cada agendamento pertence a um único cachorro; um serviço pode estar associado a vários agendamentos.

**Diagrama de sequência**

O diagrama abaixo mostra o fluxo de criação de agendamento (UC11), incluindo o caminho alternativo quando o horário já está ocupado: a verificação é feita pelo banco, que rejeita o registro duplicado (UNIQUE(data, hora)), e o controller captura o PDOException e reapresenta o formulário com a mensagem de erro.

### 3.3 Modelo conceitual

O modelo conceitual representa as entidades do banco de dados, seus atributos e a cardinalidade dos relacionamentos: um proprietário possui vários cachorros (1:N), um cachorro possui vários agendamentos (1:N) e um serviço está associado a vários agendamentos (1:N). O modelo contempla quatro entidades: **Proprietario, Cachorro, Servico e Agendamento**.

### 3.4 Modelo lógico

O modelo lógico apresenta as tabelas do banco MySQL, com chaves primárias (PK), chaves estrangeiras (FK) e restrição de unicidade (UQ).

Ações referenciais:
- `proprietarios` → `cachorros`: `ON DELETE CASCADE` (excluir um proprietário apaga seus cachorros e, por consequência, os agendamentos deles).
- `cachorros` → `agendamentos`: `ON DELETE CASCADE` (excluir um cachorro apaga seus agendamentos).
- `servicos` → `agendamentos`: `ON DELETE RESTRICT` (a chave estrangeira de `servico_id` impede a exclusão de um serviço enquanto estiver referenciado por agendamentos; a aplicação o inativa em vez de removê-lo).
- `agendamentos` → `historico_agendamentos`: `ON DELETE CASCADE`.

A restrição `UNIQUE(data, hora)` é **composta** (sobre a combinação das duas colunas) e impede dois agendamentos no mesmo horário.

### 3.5 Dicionário de dados

| Tabela | Campo | Tipo | Nulo | Chave / restrição | Descrição |
|---|---|---|---|---|---|
| proprietarios | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do proprietário |
| | nome | VARCHAR(120) | Não | — | Nome do proprietário |
| | celular | VARCHAR(20) | Não | — | Telefone celular para contato |
| | endereco | VARCHAR(255) | Não | — | Endereço completo (rua, número, bairro, cidade) |
| | observacoes | TEXT | Sim | — | Observações livres |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora de criação |
| | atualizado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Data/hora da última alteração |
| cachorros | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do cachorro |
| | proprietario_id | INT UNSIGNED | Não | FK → proprietarios(id), ON DELETE CASCADE | Proprietário do cachorro |
| | nome | VARCHAR(80) | Não | — | Nome do cachorro |
| | raca | VARCHAR(80) | Sim | — | Raça |
| | porte | ENUM('pequeno', 'medio', 'grande') | Sim | — | Porte do cachorro |
| | pelagem | VARCHAR(80) | Sim | — | Tipo/cor da pelagem |
| | observacoes | TEXT | Sim | — | Observações livres |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora de criação |
| | atualizado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Data/hora da última alteração |
| servicos | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do serviço |
| | nome | VARCHAR(80) | Não | — | Nome configurável do serviço |
| | descricao | VARCHAR(255) | Sim | — | Descrição do serviço |
| | preco | DECIMAL(10,2) | Não | DEFAULT 0.00 | Preço |
| | ativo | TINYINT(1) | Não | DEFAULT 1 | Indica se o serviço está disponível para novos agendamentos |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora de criação |
| | atualizado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Data/hora da última atualização |
| agendamentos | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do agendamento |
| | cachorro_id | INT UNSIGNED | Não | FK → cachorros(id), ON DELETE CASCADE | Cachorro atendido |
| | servico_id | INT UNSIGNED | Não | FK → servicos(id), ON DELETE RESTRICT | Serviço contratado |
| | data | DATE | Não | Parte de UNIQUE composta (data, hora) | Data do atendimento |
| | hora | TIME | Não | Parte de UNIQUE composta (data, hora) | Horário do atendimento |
| | status | ENUM('agendado', 'andamento', 'concluido', 'cancelado') | Não | DEFAULT 'agendado' | Situação do atendimento |
| | observacoes | TEXT | Sim | — | Observações livres |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora de criação |
| | atualizado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Data/hora da última alteração |
| historico_agendamentos | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do evento |
| | agendamento_id | INT UNSIGNED | Não | FK → agendamentos(id), ON DELETE CASCADE | Agendamento relacionado |
| | status_anterior | ENUM('agendado', 'andamento', 'concluido', 'cancelado') | Não | — | Status anterior |
| | status_novo | ENUM('agendado', 'andamento', 'concluido', 'cancelado') | Não | — | Novo status |
| | alterado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora da alteração |
| usuarios | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador do usuário |
| | nome | VARCHAR(120) | Não | — | Nome do usuário |
| | login | VARCHAR(60) | Não | UNIQUE | Login |
| | senha_hash | VARCHAR(255) | Não | — | Hash da senha |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP | Data/hora de criação |
| login_tentativas | id | INT UNSIGNED | Não | PK, AUTO_INCREMENT | Identificador da tentativa |
| | login | VARCHAR(60) | Não | Índice composto (login, ip, criado_em) | Login tentado |
| | ip | VARCHAR(45) | Não | Índice composto (login, ip, criado_em) | Endereço IP |
| | criado_em | TIMESTAMP | Não | DEFAULT CURRENT_TIMESTAMP; índice composto | Data/hora da tentativa |

**Tabela 5 – Relação entre casos de uso e tabelas do banco**

| Caso de uso | Tabelas envolvidas |
|---|---|
| UC01 – Consultar agenda do dia | agendamentos (JOIN cachorros, proprietários) |
| UC02 a UC04 – Listar, cadastrar e editar proprietário | proprietarios |
| UC05 – Excluir proprietário | proprietarios (cascata em cachorros e agendamentos) |
| UC06 – Listar cachorros | cachorros (JOIN proprietários) |
| UC07 e UC08 – Cadastrar e editar cachorro | cachorros |
| UC09 – Excluir cachorro | cachorros (cascata em agendamentos) |
| UC10 – Listar agendamentos | agendamentos (JOIN cachorros, proprietários) |
| UC11 a UC14 – Criar, editar, alterar status e excluir agendamento | agendamentos |
| UC15 – Gerenciar serviços | servicos; agendamentos consultada para verificar se está em uso |
| UC16 – Consultar histórico de status | historico_agendamentos; vw_historico_agendamentos relaciona agendamentos, cachorros, proprietarios, servicos |
| UC17 – Autenticar-se | usuarios, login_tentativas |
| UC18 – Encerrar sessão | sessão da aplicação; não altera tabelas do banco |

### 3.6 Recursos e ferramentas

Foram utilizados os recursos e ferramentas listados abaixo.

| Categoria | Ferramenta | Uso no projeto |
|---|---|---|
| Linguagem back-end | PHP (PDO) | Regras de negócio, controllers, models e acesso ao banco |
| Banco de dados | MySQL 8.4 | Armazenamento de proprietários, cachorros e agendamentos |
| Arquitetura | MVC próprio (Router, Controllers, Models e Views) | Organização do código em camadas, facilitando a manutenção e a separação de responsabilidades. |
| Front-end | HTML5, CSS3, Bootstrap e JavaScript | Telas responsivas, lista com busca e confirmação de exclusões |
| Versionamento | Git e GitHub | Controle de versão e trabalho em equipe (repositório do projeto) |
| Modelagem UML | PlantUML | Texto-fonte dos diagramas de casos de uso, classes, modelo conceitual, lógico e sequência |
| Prototipação | Figma | Criação do protótipo das telas e planejamento visual da interface do sistema. |
| Editor de código | Visual Studio Code | Desenvolvimento |
| Servidor local | Docker (PHP 8.4 + Apache + MySQL 8.4) | Execução da aplicação e realização de testes no ambiente de desenvolvimento local. |
| Comunicação | WhatsApp | Organização da equipe |

> **Nota (item do checklist):** Diagramas PlantUML, SVGs e imagens inseridas no Word devem ser sincronizados com o código-fonte e o banco. Os arquivos `.puml` em `docks/diagramas/` já foram atualizados com o campo `endereco` em proprietários. Os SVGs exportados do Draw.io devem ser regerados a partir dos diagramas atualizados.

### 3.7 Funcionalidades implementadas

A tabela a seguir resume as funcionalidades implementadas na versão atual. As datas de implementação devem ser preenchidas conforme o histórico do repositório.

| Módulo | Funcionalidade implementada | Casos de uso | Data |
|---|---|---|---|
| Agenda | Consulta da agenda por data, com agendamentos ordenados por horário. O dashboard recebeu melhorias posteriores. | UC01 | 17/10/2026 |
| Proprietários | Cadastro, listagem, edição e exclusão, com confirmação e exclusão em cascata dos dados relacionados. A tela também apresenta informações de visitas e gastos estimados. | UC02–UC05 | 04/09/2026 |
| Cachorros | Cadastro, listagem, edição e exclusão; vínculo com proprietário; busca por nome do cachorro ou do proprietário. | UC06–UC09 | 04/09/2026 (busca e melhorias em 15/09/2026) |
| Agendamentos | Criação, listagem, edição, remarcação e exclusão. Cada agendamento referencia um cachorro e um serviço cadastrado. | UC10–UC12, UC14 | 06/09/2026 (integração com o catálogo de serviços em 15/09/2026) |
| Status dos agendamentos | Alteração dos status agendado, andamento, concluído e cancelado, pela edição e por ações rápidas na agenda/listagem. A aplicação registra as mudanças no histórico. | UC13 | 03/10/2026 |
| Serviços | Cadastro, listagem, edição e exclusão/inativação de serviços, com descrição, preço e indicação de disponibilidade. | UC15 — Gerenciar serviços | 15/09/2026 |
| Histórico | Consulta do histórico de alterações de status dos agendamentos. | UC16 — Consultar histórico | 16/09/2026 (tela adicionada em 17/09/2026) |
| Autenticação | Login e logout; as rotas protegidas exigem sessão autenticada. A criação ou redefinição de usuários é feita por script de linha de comando. | UC17 — Autenticar-se; UC18 — Encerrar sessão | 29/09/2026 |
| Integridade e segurança | Restrição para impedir agendamentos na mesma data e hora; exclusões em cascata para proprietários e cachorros; proteção de acesso e registro de tentativas de login. Serviços usados por agendamentos são inativados em vez de removidos. | RN06, RN08–RN12; UC15, UC17 | 03/09/2026 (recursos complementares adicionados entre 15/09 e 30/09/2026) |

### 3.8 Interface do usuário

Inserir, nesta seção, os prints das telas do sistema, cada um com legenda e uma breve descrição. Telas previstas:

- Inserir print: Tela inicial – agenda do dia;
- Inserir print: Proprietários – lista e formulário de cadastro/edição (incluindo o campo obrigatório endereço);
- Inserir print: Cachorros – lista e formulário de cadastro/edição (lista com busca);
- Inserir print: Agendamentos – lista e formulário de criação/edição (com status);
- Inserir print: Mensagens de erro (campos obrigatórios e "Este horário já está ocupado");
- Inserir print: Telas em celular/tablet (responsividade).

> **Nota (item 10 do checklist):** os prints acima ainda são placeholders. Devem ser substituídos por capturas de tela reais das telas implementadas, cada uma com legenda e descrição breve.

## 4. Testes e Qualidade

### 4.1 Estratégia de Testes

A estratégia de testes adotada foi baseada em testes funcionais manuais (caixa-preta), executados a partir dos casos de uso e das regras de negócio: cada fluxo principal e alternativo foi percorrido na interface, comparando o comportamento obtido com o esperado. Foram cobertos: (a) validação de campos obrigatórios; (b) regras de integridade do banco (horário único e exclusão em cascata); (c) mensagens de erro e erros 404; (d) confirmação de exclusões; e (e) responsividade em diferentes tamanhos de tela.

### 4.2 Resultados dos Testes

A tabela apresenta os casos de teste definidos. A coluna "Resultado obtido" deve ser preenchida com o resultado de cada execução (Passou/Falhou), a data de execução e o ambiente. Testes que não foram executados com evidência real devem ser registrados como "Não executado".

| ID | Caso de teste | Procedimento | Resultado esperado | Resultado obtido | Data / Ambiente |
|---|---|---|---|---|---|
| CT01 | Cadastrar proprietário válido | Informar nome, celular, endereço e salvar | Proprietário aparece na lista (RN01) | Passou | — |
| CT02 | Cadastrar proprietário sem celular | Deixar o celular vazio e salvar | Envio bloqueado (JS) / (RN01) | Passou | — |
| CT03 | Cadastrar cachorro sem proprietário | Tentar enviar o formulário sem selecionar o dono | Envio bloqueado (JS) / (RN02) | Passou | — |
| CT04 | Porte inválido | Enviar porte fora de pequeno/medio/grande | Porte gravado como nulo (RN03) | Passou | — |
| CT05 | Criar agendamento válido | Selecionar cachorro, serviço, data e hora | Agendamento criado com status "agendado" (RN07) | Passou | — |
| CT06 | Criar agendamento em horário ocupado | Repetir data e hora de agendamento existente | Mensagem: "Este horário já está ocupado. Escolha outro." (RN06) | Passou | — |
| CT07 | Criar agendamento incompleto | Deixar serviço ou hora em branco | Mensagem de campos obrigatórios (RN05) | Passou | — |
| CT08 | Alterar status | Editar agendamento e escolher "concluído" | Status atualizado na lista e na agenda do dia | Passou | — |
| CT09 | Remarcar para horário ocupado | Editar agendamento para data/hora já usada | Atualização rejeitada com mensagem de horário ocupado | Passou | — |
| CT10 | Excluir proprietário | Excluir proprietário que tem cachorros e agendamentos | Confirmação com aviso; cachorros e agendamentos removidos (RN08, RN10) | Passou | — |
| CT11 | Excluir cachorro | Excluir cachorro com agendamentos | Agendamentos removidos por cascata (RN09) | Passou | — |
| CT12 | Excluir agendamento | Excluir agendamento e criar outro no mesmo horário | Horário volta a ficar disponível | Passou | — |
| CT13 | Agenda do dia | Criar agendamentos para hoje fora de ordem | Lista ordenada por horário (UC01) | Passou | — |
| CT14 | Registro inexistente | Abrir edição com ID inexistente | Erro 404 com mensagem do recurso | Passou | — |
| CT15 | Responsividade | Abrir o sistema em celular, tablet e computador | Telas se adaptam sem perda de funcionalidade (RNF02) | Passou | — |
| CT16 | Cadastrar serviço | Cadastrar novo serviço no catálogo | Serviço aparece na lista (UC15) | Não executado | — |
| CT17 | Consultar histórico | Acessar tela de histórico de status | Histórico exibido com dados da view (UC16) | Não executado | — |

> **Nota (item 8 do checklist):** os resultados marcados como "Passou" sem data/ambiente são placeholders. Conforme orientação, não preencher como "Passou" sem executar cada teste e registrar a data real, o ambiente e o resultado observado. CT16 e CT17 foram incluídos para cobrir UC15 e UC16.

### 4.3 Garantia da Qualidade

As práticas adotadas para garantir a qualidade do sistema foram:

- Validação em duas camadas: no servidor (campos obrigatórios e valores permitidos) e no navegador (JavaScript);
- Integridade garantida pelo banco: restrição UNIQUE(data, hora) composta e chaves estrangeiras com exclusão em cascata (e RESTRICT para servico_id);
- Proteção das telas: escape de HTML (helper e()) e acesso ao banco via PDO;
- Arquitetura MVC: separação entre rotas, controllers, models e views, facilitando a manutenção;
- Confirmação de ações destrutivas: exclusões exigem confirmação do usuário;
- Versionamento: código mantido em repositório Git/GitHub;
- Revisão de código entre os integrantes e testes com a cliente.

### 4.4 Requisitos mínimos de hardware e software para o sistema

Requisitos mínimos para executar o sistema:

| Item | Requisito mínimo |
|---|---|
| Servidor – software | Docker (Docker Engine ou Docker Desktop), Apache, PHP 8.4 e MySQL 8.4. |
| Servidor – hardware | Qualquer máquina capaz de executar Docker, com no mínimo 2 GB de RAM recomendados. |
| Cliente – software | Navegador web moderno (Chrome, Firefox, Edge ou Safari). JavaScript habilitado, caso necessário. |
| Cliente – hardware | Computador, tablet ou celular com acesso à rede onde o sistema está hospedado |
| Rede | Conexão entre o cliente e o servidor da aplicação (rede local ou internet) |

### 4.5 Contrato para desenvolvimento de software

Modelo de contrato elaborado para fins acadêmicos, a ser ajustado com os dados reais das partes.

**TERMO DE ACORDO PARA DESENVOLVIMENTO DE SISTEMA WEB**

CONTRATANTE: Amora Banho e Tosa, inscrita no CNPJ sob nº 53.332.173/0001-73, razão empresarial 53.332.173 LALESCA BIANCA DA SILVA GUMIERI, localizada na Rua Mario Arthur Michielin, nº 137, Jardim Luiza Maria, Araras/SP, representada por sua titular, Lalesca Bianca da Silva Gumieri.

CONTRATADA: Equipe acadêmica Code & Coffee, composta por Ana Luiza Oliveira de Jesus, Felipe Santana Lopes, Guilherme Cardoso Hubner, Kaique Ribeiro dos Santos e Vinicius Tadeu Silvestre Januario.

As partes acima identificadas estabelecem o presente acordo para o desenvolvimento do sistema descrito nas cláusulas seguintes.

**CLÁUSULA 1ª – DO OBJETO**
O presente acordo tem como objetivo o desenvolvimento do sistema web Amora Banho e Tosa, destinado ao cadastro de clientes e animais, agendamento de serviços de banho e tosa e controle de atendimentos.

**CLÁUSULA 2ª – DO ESCOPO**
O sistema será desenvolvido conforme os requisitos funcionais e não funcionais definidos e aprovados pela CONTRATANTE, descritos na documentação do projeto. Funcionalidades não previstas no escopo inicial dependerão de novo acordo entre as partes.

**CLÁUSULA 3ª – DO PRAZO**
A entrega do sistema está prevista para até 20/11/2026, com apresentação final em 24/11/2026, conforme o cronograma acadêmico estabelecido.

**CLÁUSULA 4ª – DAS RESPONSABILIDADES DA CONTRATADA**
A equipe Code & Coffee será responsável pelo desenvolvimento, testes, entrega do sistema, documentação e orientação básica sobre sua utilização. Também deverá manter sigilo sobre as informações fornecidas pela CONTRATANTE.

**CLÁUSULA 5ª – DAS RESPONSABILIDADES DA CONTRATANTE**
A CONTRATANTE deverá fornecer as informações necessárias ao desenvolvimento, colaborar com a definição dos requisitos e validar as funcionalidades apresentadas.

**CLÁUSULA 6ª – DA GRATUIDADE**
O desenvolvimento será realizado gratuitamente, como parte de um projeto acadêmico, não havendo cobrança ou remuneração pelo desenvolvimento e entrega do sistema.

**CLÁUSULA 7ª – DA PROPRIEDADE DO SISTEMA**
O código-fonte e os materiais desenvolvidos permanecerão sob propriedade intelectual dos integrantes da equipe Code & Coffee. A CONTRATANTE terá autorização gratuita para utilizar o sistema em suas atividades comerciais, não sendo permitida sua comercialização ou distribuição a terceiros sem autorização da equipe.

**CLÁUSULA 8ª – DA PROTEÇÃO DE DADOS**
As partes comprometem-se a preservar a segurança e a confidencialidade dos dados utilizados no sistema, respeitando a Lei Geral de Proteção de Dados (Lei nº 13.709/2018 – LGPD).

**CLÁUSULA 9ª – DA GARANTIA E DO SUPORTE**
A equipe Code & Coffee oferecerá suporte técnico e correção de erros relacionados às funcionalidades entregues pelo período de 90 (noventa) dias após a entrega definitiva do sistema, sem custos adicionais. Novas funcionalidades e alterações fora do escopo não estão incluídas nessa garantia.

**CLÁUSULA 10ª – DAS ALTERAÇÕES**
Qualquer alteração no escopo ou no prazo deverá ser previamente discutida e aprovada pelas partes.

**CLÁUSULA 11ª – DO ENCERRAMENTO**
O acordo poderá ser encerrado por qualquer uma das partes mediante comunicação prévia. Caso isso ocorra durante o desenvolvimento, serão disponibilizados os materiais concluídos até a data do encerramento, respeitando a propriedade intelectual da equipe.

Araras/SP, 24 de novembro de 2026.
> **Nota (item 9 do checklist):** data preenchida com base na data de apresentação (24/11/2026). O documento permanece identificado como modelo acadêmico (cláusula 6ª – gratuidade).

CONTRATANTE

Lalesca Bianca da Silva Gumieri
Amora Banho e Tosa
CNPJ: 53.332.173/0001-73

CONTRATADA – CODE & COFFEE

Ana Luiza Oliveira de Jesus

Felipe Santana Lopes

Guilherme Cardoso Hubner

Kaique Ribeiro dos Santos

Vinicius Tadeu Silvestre Januario

## 5. Considerações Finais

**Conclusão**

O sistema Amora Banho e Tosa atende ao objetivo de organizar o cadastro de proprietários e cachorros e o agendamento dos atendimentos, impedindo conflitos de horário, apresentando a agenda do dia e permitindo acompanhar o status de cada serviço. A adoção da arquitetura MVC e das restrições de integridade no banco de dados tornou o sistema mais organizado e confiável, e a modelagem UML ajudou a alinhar o entendimento da equipe e do cliente.

Como evolução futura, estão previstos o login de usuários (a tabela de usuarios já existe no banco), o relatório financeiro (RF09) e, opcionalmente, ações rápidas de status na listagem de agendamentos (concluir/cancelar), que tornaram o UC13 um caso de uso independente.

## 6. Referências

BOOCH, Grady; RUMBAUGH, James; JACOBSON, Ivar. UML: guia do usuário. 2. ed. Rio de Janeiro: Elsevier, 2012.

ELMASRI, Ramez; NAVATHE, Shamkant B. Sistemas de banco de dados. 7. ed. São Paulo: Pearson, 2018.

ORACLE. MySQL Reference Manual. Disponível em: https://dev.mysql.com/doc/. Acesso em: 28/09/2026.

PHP GROUP. PHP Manual. Disponível em: https://www.php.net/manual/pt_BR/. Acesso em: 10/10/2026.

PRESSMAN, Roger S.; MAXIM, Bruce R. Engenharia de software: uma abordagem profissional. 9. ed. Porto Alegre: AMGH, 2021.

SOMMERVILLE, Ian. Engenharia de software. 10. ed. São Paulo: Pearson, 2018.

CODE & COFFEE. Repositório do projeto PI 2º semestre. Disponível em: https://github.com/7lipe7/PI_2_semestre. Acesso em: 08/09/2026.

## Anexo I – Diário de bordo

[Preencher com o diário de bordo da equipe.]

## Anexo II – Cronograma efetivo

| Tarefas | 26/08 a 02/09 | 02/09 a 09/09 | 09/09 a 16/09 | 16/09 a 30/09 | 30/09 a 14/10 | 21/10 a 11/11 | 11/11 a 20/11 | 24/11 |
|---|---|---|---|---|---|---|---|---|
| Definição Grupos | X | | | | | | | |
| Criação Marca Empresa | | X | | | | | | |
| Definição Empresa | | | X | | | | | |
| Escopo Sistema | | X | X | | | | | |
| Requisitos | | X | X | X | X | | | |
| Diagramas | | X | X | X | | | | |
| Protótipo | | X | X | X | X | | | |
| Documentação | | X | X | X | X | X | | |
| Desenvolvimento | | | | X | X | X | X | |
| Entrega | | | | | | | X | |
| Apresentação | | | | | | | | X |

## Anexo III – Evidências

- Repositório do código-fonte: https://github.com/7lipe7/PI_2_semestre
- Diagramas PlantUML: `docks/diagramas/*.puml` (casos de uso, classes, modelo conceitual, modelo lógico, sequência)
- Banco de dados: `docker/php/db/init.sql` e migrations em `docker/php/db/migrations/`
- Apresentação e vídeo: [inserir link da apresentação e do vídeo ao vivo, se existirem]
- Capturas de tela: inserir na seção 3.8 conforme prints das telas implementadas.

> **Nota (item 11 do checklist):** o placeholder "AQUI PODE COLOCAR LINK DE APRESENTAÇÃO E LIVE COISAS EXTERNAS" foi substituído pelos links e referências acima. Substitua pelos links reais de apresentação, vídeo e capturas, se existirem.
