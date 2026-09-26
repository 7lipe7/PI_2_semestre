# Projeto Integrador — Pet Shop

Sistema web desenvolvido como parte do **Projeto Integrador (PI)** da **Fatec**, com o objetivo de aplicar na prática conceitos de desenvolvimento web, APIs, banco de dados e arquitetura de software.

## Tecnologias utilizadas

* **PHP 8.4** — desenvolvimento do back-end e API
* **JavaScript** — consumo da API e interação com a interface
* **HTML5** — estrutura das páginas
* **CSS3** — estilização
* **MySQL 8.4** — banco de dados
* **Docker** — criação e execução do ambiente
* **Apache** — servidor web
* **Git/GitHub** — versionamento do projeto

## Arquitetura

O projeto utiliza uma arquitetura baseada na separação entre front-end, API e banco de dados.

```text
Navegador
   │
   │ JavaScript / Fetch
   ▼
API PHP
   │
   │ PDO / SQL
   ▼
MySQL (container Docker)
```

O JavaScript é responsável por realizar as requisições HTTP para a API desenvolvida em PHP. A API processa as requisições e realiza a comunicação com o banco de dados MySQL por meio de PDO.

## Estrutura do projeto

```text
.
├── docker/
│   └── php/
│       └── Dockerfile
│
├── public/
│   ├── assets/
│   │   ├── css/
│   │   └── js/
│   │
│   └── index.php
│
├── src/
│   └── config/
│       └── database.php
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

Ajuste as credenciais do banco de dados no arquivo `.env`, se necessário.

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

## API

A API PHP funciona como intermediária entre o front-end e o banco de dados.

Exemplo de requisição utilizando JavaScript:

```javascript
fetch('/api/encomendas')
    .then(response => response.json())
    .then(data => {
        console.log(data)
    })
    .catch(error => {
        console.error(error)
    })
```

### Exemplo de POST

```javascript
fetch('/api/encomendas', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        cliente: 'João',
        produto: 'Produto exemplo',
        quantidade: 1
    })
})
```

## Banco de dados

O projeto utiliza o **MySQL 8.4**, executado em um container Docker junto com a aplicação PHP.

* O banco é criado automaticamente ao subir os containers, com o nome definido em `DB_NAME` no arquivo `.env`;
* Os dados ficam persistidos no volume `db_data` do Docker;
* Para acessar o MySQL a partir de um cliente externo (DBeaver, HeidiSQL, mysql client), utilize:

```text
Host: localhost
Porta: 3307
Usuário: valor de DB_USER no .env
Senha: valor de DB_PASSWORD no .env
Banco: valor de DB_NAME no .env
```

Para acessar o MySQL diretamente no container:

```bash
docker compose exec db mysql -u root -p
```

A conexão do PHP com o banco é feita por **PDO** (extensões `pdo_mysql`), configurada em `src/config/database.php`.

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

O status pode ser alterado pelo seletor no card da listagem ou pela tela de edição.

### Migrations

O schema "oficial" é o `docker/php/db/init.sql` (usado apenas na primeira subida do container, com volume limpo). Mudou o schema? Faça **as duas** coisas:

1. Atualize o `init.sql` (quem clonar o projeto de novo recebe o schema certo);
2. Crie o arquivo em `docker/php/db/migrations/` para quem já tem o banco populado:

```text
docker/php/db/migrations/003_minha_mudanca.sql
```

Aplicar manualmente em um banco que já existe:

```bash
docker compose exec -T db sh -c 'exec mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' < docker/php/db/migrations/003_minha_mudanca.sql
```

> ⚠️ Não use `docker compose down -v` para "consertar" o banco: o `-v` apaga o volume `db_data` e todos os dados. Migrations são o caminho seguro.

## Limitações e próximos passos

Pontos conscientes do escopo atual, registrados para evolução do projeto:

* **Fuso horário** — datas e horas são gravadas como estão no formulário (sem conversão de fuso);
* **Índices** — as buscas (nome do pet/dono, data) usam `LIKE` e filtros sem índice dedicado; suficiente para a base atual, precisa de índice quando a base crescer;
* **Usuários e permissões** — o sistema não tem login: qualquer pessoa com a URL acessa as telas;
* **Validação de negócio** — a validação é feita no servidor (campos, formato de data/hora, horário ocupado), sem as mensagens inline do front-end;
* **Migrations** — ainda são aplicadas manualmente (não há ferramenta de versionamento de schema, como Phinx ou Doctrine Migrations).

## Docker

O ambiente é composto por dois containers:

* **php** — PHP 8.4 + Apache, disponível em `http://localhost:8080`;
* **db** — MySQL 8.4, acessível externamente pela porta `3307`.

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
* Desenvolver uma API utilizando PHP;
* Realizar comunicação entre JavaScript e PHP;
* Integrar a aplicação com um banco MySQL;
* Utilizar Docker para padronização do ambiente;
* Aplicar conceitos de arquitetura de software;
* Utilizar Git e GitHub para versionamento;
* Desenvolver uma aplicação preparada para implantação.

## Desenvolvimento

O projeto está sendo desenvolvido de forma incremental, com implementação e integração das funcionalidades ao longo do desenvolvimento do Projeto Integrador.

## Autores

**Felipe Santana**

Projeto desenvolvido para a **Fatec — Análise e Desenvolvimento de Sistemas / Desenvolvimento de Software Multiplataforma**.

## Licença

Este projeto foi desenvolvido para fins acadêmicos.
