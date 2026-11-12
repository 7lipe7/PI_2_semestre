# Projeto Integrador — Pet Shop

Sistema web desenvolvido como parte do **Projeto Integrador (PI)** da **Fatec**, com o objetivo de aplicar na prática conceitos de desenvolvimento web, arquitetura MVC, banco de dados e segurança da informação.

## Tecnologias utilizadas

* **PHP 8.4** — back-end em arquitetura MVC (router, controllers, models e views renderizadas no servidor)
* **JavaScript** — melhorias progressivas na interface (filtro de abas, mudança de status sem recarregar a página, combobox); a aplicação funciona sem ele
* **HTML5** — estrutura das páginas
* **CSS3** — estilização
* **MySQL 8.4** — banco de dados
* **Docker** — criação e execução do ambiente
* **Apache** — servidor web
* **Git/GitHub** — versionamento do projeto

## Arquitetura

O projeto usa **MVC** (Model-View-Controller) com um *front controller* único: toda
requisição entra por `public/index.php`, que descobre a rota e entrega para o
controller responsável. Não existe API JSON pública — o PHP monta o HTML e o
JavaScript só entra como melhoria progressiva.

```text
Navegador
   │
   │ HTTP (formulário ou fetch)
   ▼
public/index.php  ──►  src/Core/Router.php  (front controller + .htaccess)
   │
   ├──► src/Controllers/   (regra de tela, validação, redirecionamento)
   │        │
   │        ├──► src/Models/   ── PDO ──► MySQL (container Docker)
   │        │
   │        └──► src/Views/    (HTML renderizado no servidor)
   │                    │
   └──► resposta HTML ◄──┘
```

* **`src/Core/`** — infraestrutura: `Router` (tabela de rotas), `Controller` (classe
  base com `render()` e `redirect()`), `Session`, `Csrf` e `Auth`;
* **`src/Controllers/`** — um por recurso (`Auth`, `Home`, `Proprietario`,
  `Cachorro`, `Agendamento`, `Servico`): lê o `$_POST`, valida e escolhe a view;
* **`src/Models/`** — uma classe por tabela (`Usuario`, `TentativaLogin`,
  `Proprietario`, `Cachorro`, `Servico`, `Agendamento`), responsável pelo SQL;
* **`src/Views/`** — templates PHP dentro de `src/Views/layout/layout.php`, com o
  HTML escapado pelo helper `\Core\e()`;
* **`src/bin/`** — scripts de linha de comando (`criar-usuario.php`,
  `limpar-tentativas.php`), nunca acessíveis pela web.

## Estrutura do projeto

```text
.
├── docker/
│   └── php/
│       ├── Dockerfile
│       └── db/
│           ├── init.sql                 # schema oficial (1ª subida)
│           └── migrations/              # alterações para banco já existente
│
├── public/                              # única pasta exposta pelo Apache
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   ├── .htaccess                        # manda tudo para index.php
│   └── index.php                        # front controller
│
├── src/
│   ├── bin/
│   │   ├── criar-usuario.php            # cria/redefine usuário do painel
│   │   └── limpar-tentativas.php        # limpeza de login_tentativas
│   ├── config/
│   │   └── database.php                 # conexão PDO (lê .env / DATABASE_URL)
│   ├── Core/
│   │   ├── Router.php
│   │   ├── Controller.php
│   │   ├── Session.php
│   │   ├── Csrf.php
│   │   └── Auth.php
│   ├── Controllers/
│   ├── Models/
│   └── Views/
│       └── layout/layout.php            # layout comum (sidebar + header)
│
├── docker-compose.yml
├── .env.example
├── passos.txt
└── README.md
```

## Executando o projeto

### 1. Clonar o repositório

```bash
git clone URL_DO_REPOSITORIO
cd PI_2_semestre
```

### 2. Configurar as variáveis de ambiente

```bash
cp .env.example .env
```

Ajuste as credenciais do banco de dados no arquivo `.env`. Para conectar a um banco remoto,
defina `DATABASE_URL` no formato `mysql://usuario:senha@host:porta/banco?ssl-mode=REQUIRED`.
Quando `DATABASE_URL` estiver preenchida, ela prevalece sobre `DB_HOST`, `DB_PORT`,
`DB_NAME`, `DB_USER` e `DB_PASSWORD`; se estiver vazia, essas variáveis mantêm a conexão local.

> O arquivo `.env` **não** é versionado no Git (está no `.gitignore`). Nunca envie senhas ou credenciais para o GitHub.

### 3. Construir os containers

```bash
docker compose build
```

### 4. Iniciar o projeto

```bash
docker compose up -d
```

### 5. Verificar os containers

```bash
docker compose ps
```

### 6. Acessar o sistema

Após iniciar os containers, acesse no navegador:

```text
http://localhost:8080
```

O sistema abre na tela de login. Antes do primeiro acesso é preciso criar um
usuário — não existe cadastro pela interface:

```bash
docker compose exec php php /var/www/src/bin/criar-usuario.php
```

Veja [Autenticação](#autenticação) para o detalhe de cada opção do script.

## Rotas

As rotas ficam declaradas em `public/index.php`, na tabela do `Router`. Os formulários
são HTML comuns (`POST`), e o controller responde com redirect ou re-renderizando a view.

| Método | Rota | Controller | Ação |
|---|---|---|---|
| GET | `/` | `HomeController` | dashboard |
| GET | `/login` | `AuthController` | formulário de login |
| POST | `/login` | `AuthController` | autentica |
| POST | `/logout` | `AuthController` | encerra a sessão |
| GET | `/clientes` | `ProprietarioController` | listar |
| GET | `/clientes/novo` | `ProprietarioController` | formulário |
| POST | `/clientes` | `ProprietarioController` | cadastrar |
| GET | `/clientes/{id}/editar` | `ProprietarioController` | formulário |
| POST | `/clientes/{id}` | `ProprietarioController` | salvar edição |
| POST | `/clientes/{id}/excluir` | `ProprietarioController` | excluir |
| GET | `/pets` | `CachorroController` | listar |
| GET | `/pets/novo` | `CachorroController` | formulário |
| POST | `/pets` | `CachorroController` | cadastrar |
| GET | `/pets/{id}/editar` | `CachorroController` | formulário |
| POST | `/pets/{id}` | `CachorroController` | salvar edição |
| POST | `/pets/{id}/excluir` | `CachorroController` | excluir |
| GET | `/agendamentos` | `AgendamentoController` | listar |
| GET | `/agendamentos/historico` | `AgendamentoController` | histórico de status |
| GET | `/agendamentos/novo` | `AgendamentoController` | formulário |
| POST | `/agendamentos` | `AgendamentoController` | cadastrar |
| GET | `/agendamentos/{id}/editar` | `AgendamentoController` | formulário |
| POST | `/agendamentos/{id}` | `AgendamentoController` | salvar edição |
| POST | `/agendamentos/{id}/excluir` | `AgendamentoController` | excluir |
| POST | `/agendamentos/{id}/status` | `AgendamentoController` | mudar status |
| GET | `/servicos` | `ServicoController` | listar |
| GET | `/servicos/novo` | `ServicoController` | formulário |
| POST | `/servicos` | `ServicoController` | cadastrar |
| GET | `/servicos/{id}/editar` | `ServicoController` | formulário |
| POST | `/servicos/{id}` | `ServicoController` | salvar edição |
| POST | `/servicos/{id}/excluir` | `ServicoController` | excluir |

### A única resposta JSON

`POST /agendamentos/{id}/status` é o único endpoint que devolve JSON — e só quando o
JavaScript pede (`Accept: application/json`). Sem JavaScript, o mesmo formulário
responde com um redirect e a página recarrega normalmente. É a estratégia de
*progressive enhancement*: o recurso funciona nos dois cenários.

```javascript
fetch('/agendamentos/1/status', {
    method: 'POST',
    headers: { 'Accept': 'application/json' },
    body: new FormData(formulario)   // inclui o token CSRF do formulário
})
    .then(response => response.json())
    .then(data => console.log(data.status, data.rotulo, data.categoria));
```

Todo `POST` (exceto `/login`, que ainda não tem sessão) passa pelo gate global de CSRF
em `index.php`, e toda rota diferente de `/login` passa pelo gate de `Auth::exigirLogin()`.

## Banco de dados

O projeto utiliza **MySQL**. Por padrão, o banco roda em um container Docker; a aplicação
também pode conectar a um MySQL remoto por meio de `DATABASE_URL`.

* No modo local, o banco é criado automaticamente ao subir os containers, com o nome definido em `DB_NAME` no arquivo `.env`;
* No modo local, os dados ficam persistidos no volume `db_data` do Docker;
* Para acessar o MySQL a partir de um cliente externo (DBeaver, HeidiSQL, mysql client), utilize:

```text
Host: localhost
Porta: 3308
Usuário: valor de DB_USER no .env
Senha: valor de DB_PASSWORD no .env
Banco: valor de DB_NAME no .env
```

Para acessar o MySQL diretamente no container:

```bash
docker compose exec db mysql -u root -p
```

A conexão do PHP com o banco é feita por **PDO** (extensões `pdo_mysql`), configurada em `src/config/database.php`.

### Estrutura da tabela `proprietarios`

| Coluna | Tipo | Nulo | Descrição |
|--------|------|------|-----------|
| `id` | INT UNSIGNED | NÃO | Chave primária, auto-incremento |
| `nome` | VARCHAR(120) | NÃO | Nome completo do cliente/tutor |
| `celular` | VARCHAR(20) | NÃO | Telefone / WhatsApp com DDD |
| `endereco` | VARCHAR(255) | NÃO | Endereço completo (rua, número, bairro, cidade) |
| `observacoes` | TEXT | SIM | Observações adicionais sobre o cliente |
| `criado_em` | TIMESTAMP | NÃO | Data/hora de criação do registro |
| `atualizado_em` | TIMESTAMP | NÃO | Data/hora da última atualização |

O campo `endereco` é obrigatório (`NOT NULL`). Tanto o backend (`ProprietarioController`) quanto o frontend validam que o campo não esteja vazio antes de salvar. Registros antigos que não tinham endereço são mantidos com valor vazio (`''`) pela migration `005_endereco_proprietarios.sql`.

### Fluxo de status dos agendamentos

```text
agendado ──► andamento ──► concluido
    │             │
    └─────────────┴──────► cancelado
```

* **agendado** — marcado, ainda não iniciado;
* **andamento** — o pet está em atendimento;
* **concluido** — serviço finalizado (é o único que conta na receita);
* **cancelado** — desmarcado.

O status pode ser alterado pelo seletor na tela de edição ou direto do **dashboard**, pelos botões *Iniciar atendimento* e *Concluir*. A mudança é gravada por `POST /agendamentos/{id}/status`: o JavaScript pede a resposta em JSON e atualiza o card na hora, sem recarregar a página; sem JavaScript, o mesmo formulário redireciona de volta para a agenda. O trigger `trg_historico_status_agendamento` registra a transição em `historico_agendamentos`.

### Seletor de categoria (Agendamentos)

A listagem tem abas com a contagem de cada categoria: **Todos**, **Agendado próximo**, **Em andamento**, **Concluídos** e **Cancelados**. O filtro roda só no navegador — cada card já sai do PHP com `data-agendamento-categoria`, então trocar de aba não faz nenhum request ao servidor. As categorias e os rótulos ficam em `Agendamento::CATEGORIAS`, que é a fonte única usada pela view e pelo JavaScript.

### Migrations

O schema "oficial" é o `docker/php/db/init.sql` (usado apenas na primeira subida do container, com volume limpo). Mudou o schema? Faça **as duas** coisas:

1. Atualize o `init.sql` (quem clonar o projeto de novo recebe o schema certo);
2. Crie o arquivo em `docker/php/db/migrations/` para quem já tem o banco populado:

```text
docker/php/db/migrations/005_minha_mudanca.sql
```

| Arquivo | O que faz |
|---|---|
| `001_status_andamento.sql` | Adiciona o status `andamento` ao ENUM de `agendamentos` e do histórico |
| `002_historico_fk_cascade.sql` | `fk_historico_agendamento` passa de `RESTRICT` para `CASCADE` (excluir agendamento apaga o histórico dele) |
| `003_login_tentativas.sql` | Cria `login_tentativas`, o contador de tentativas para o bloqueio de força bruta do login |
| `004_indices_busca.sql` | Cria os índices de busca por nome e de filtro por status/data nas listagens |
| `005_endereco_proprietarios.sql` | Adiciona `endereco` aos proprietários e mantém registros existentes com endereço vazio |

Aplicar manualmente em um banco que já existe — **sempre na ordem numérica**:

```bash
for f in docker/php/db/migrations/*.sql; do
  docker compose exec -T db sh -c 'exec mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' < "$f"
done
```

> Sem a `003` o login quebra com `Table 'petshop.login_tentativas' doesn't exist`,
> porque o PHP consulta essa tabela a cada tentativa.
>
> ⚠️ Não use `docker compose down -v` para "consertar" o banco: o `-v` apaga o volume `db_data` e todos os dados. Migrations são o caminho seguro.

## Autenticação

O painel é fechado: toda rota diferente de `/login` passa por
`\Core\Auth::exigirLogin()` no `index.php` e, sem sessão ativa, é redirecionada para
o login.

* **Sessão** — cookie `PETSHOPSESSID` com `httponly`, `samesite=Strict` e `secure`
  quando a requisição é HTTPS (ou quando `SESSION_SECURE_COOKIE=1`); o id é
  **sempre gerado pelo servidor** (`use_strict_mode` + `use_only_cookies`), o que
  recusa `?PHPSESSID=` na URL;
* **Expiração por inatividade** — 30 minutos (`Session::TIMEOUT_SEGUNDOS`). Ao
  estourar, a sessão é zerada e o id trocado antes do redirect para `/login`;
* **Anti-session-fixation** — o id é regenerado logo após o login ser confirmado;
* **Senha** — só o hash é gravado (`password_hash`/`PASSWORD_DEFAULT`); na
  verificação, `password_needs_rehash` regrava hashes com custo antigo;
* **CSRF** — token obrigatório em todo `POST`, verificado em um gate único
  (`index.php`), com exceção do próprio `/login`;
* **Força bruta** — 5 tentativas malsucedidas por login+IP bloqueiam por 15 minutos
  (`MAX_TENTATIVAS` / `JANELA_BLOQUEIO_MIN`). O contador fica em `login_tentativas`,
  no banco, porque um contador em sessão seria zerado ao descartar o cookie;
* **Mensagens uniformes** — login inexistente e senha errada retornam o mesmo
  texto, para não revelar quais logins existem;
* **Logout** — `Session::destruir()` apaga os dados e o cookie do navegador.

### Criando o primeiro usuário

Não existe tela de cadastro de usuário. Quem administra o sistema cria a conta por
um script de linha de comando (recusado fora do CLI):

```bash
docker compose exec php php /var/www/src/bin/criar-usuario.php
```

Sem argumentos usa `Administrador`/`admin` e pede a senha sem exibi-la. Para
informar nome e login:

```bash
docker compose exec php php /var/www/src/bin/criar-usuario.php "Maria" maria
```

Rodar o script com um login já existente **redefine a senha** daquele usuário.



## Docker

O ambiente é composto por três containers:

* **php** — PHP 8.4 + Apache, disponível em `http://localhost:8080`;
* **db** — MySQL 8.4, acessível externamente pela porta `3308`;
* **limpeza** — executa 1x por dia `src/bin/limpar-tentativas.php`, que remove os registros antigos de `login_tentativas`. Não serve web (não publica porta) e só depende do banco.

Para iniciar o ambiente:

```bash
docker compose up -d
```

Para visualizar os logs:

```bash
docker compose logs -f
```

Para parar os containers:

```bash
docker compose down
```

## Objetivos do projeto

* Aplicar conceitos de desenvolvimento web;
* Estruturar o back-end em MVC (Model-View-Controller) com front controller;
* Comunicar o PHP com o banco MySQL por PDO;
* Usar JavaScript como melhoria progressiva, sem tornar a interface dependente dele;
* Integrar a aplicação com um banco MySQL;
* Utilizar Docker para padronização do ambiente;
* Aplicar conceitos de arquitetura de software;
* Aplicar segurança da informação (autenticação por sessão, CSRF e força bruta);
* Utilizar Git e GitHub para versionamento;
* Desenvolver uma aplicação preparada para implantação.

## Desenvolvimento

O projeto está sendo desenvolvido de forma incremental, com implementação e integração das funcionalidades ao longo do desenvolvimento do Projeto Integrador.

## Autores

**Felipe Santana**

Projeto desenvolvido para a **Fatec — Análise e Desenvolvimento de Sistemas / Desenvolvimento de Software Multiplataforma**.

## Licença

Este projeto foi desenvolvido para fins acadêmicos.
