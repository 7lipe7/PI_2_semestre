# Projeto Integrador — Sistema Web

Sistema web desenvolvido como parte do **Projeto Integrador (PI)** da **Fatec**, com o objetivo de aplicar na prática conceitos de desenvolvimento web, APIs, banco de dados e arquitetura de software.

## Tecnologias utilizadas

* **PHP 8.4** — desenvolvimento do back-end e API
* **JavaScript** — consumo da API e interação com a interface
* **HTML5** — estrutura das páginas
* **CSS3** — estilização
* **Supabase** — banco de dados PostgreSQL
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
   │ Requisições
   ▼
Supabase / PostgreSQL
```

O JavaScript é responsável por realizar as requisições HTTP para a API desenvolvida em PHP. A API processa as requisições e realiza a comunicação com o banco de dados do Supabase.

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
├── docker-compose.yml
├── passos.txt
└── README.md
```

## Executando o projeto

### 1. Clonar o repositório

```bash
git clone URL_DO_REPOSITORIO
cd NOME_DO_PROJETO
```

### 2. Construir os containers

```bash
docker compose build
```

### 3. Iniciar o projeto

```bash
docker compose up -d
```

### 4. Verificar os containers

```bash
docker compose ps
```

### 5. Acessar o sistema

Após iniciar os containers, acesse no navegador:

```text
http://localhost
```

Caso a aplicação esteja configurada para outra porta, utilize a porta definida no `docker-compose.yml`.

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

O projeto utiliza o **Supabase** como serviço de banco de dados.

O banco utilizado é baseado em **PostgreSQL**.

As informações de conexão devem ser configuradas por meio de variáveis de ambiente.

> Nunca envie chaves privadas, senhas ou outras credenciais do Supabase diretamente para o GitHub.

## Docker

O ambiente PHP é executado dentro de um container Docker.

O `Dockerfile` é responsável pela configuração do ambiente PHP e pela instalação das dependências necessárias para comunicação com o PostgreSQL.

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
* Integrar a aplicação com um banco PostgreSQL;
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
# Projeto Integrador — Sistema Web

Sistema web desenvolvido como parte do **Projeto Integrador (PI)** da **Fatec**, com o objetivo de aplicar na prática conceitos de desenvolvimento web, APIs, banco de dados e arquitetura de software.

## Tecnologias utilizadas

* **PHP 8.4** — desenvolvimento do back-end e API
* **JavaScript** — consumo da API e interação com a interface
* **HTML5** — estrutura das páginas
* **CSS3** — estilização
* **Supabase** — banco de dados PostgreSQL
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
   │ Requisições
   ▼
Supabase / PostgreSQL
```

O JavaScript é responsável por realizar as requisições HTTP para a API desenvolvida em PHP. A API processa as requisições e realiza a comunicação com o banco de dados do Supabase.

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
├── docker-compose.yml
├── passos.txt
└── README.md
```

## Executando o projeto

### 1. Clonar o repositório

```bash
git clone URL_DO_REPOSITORIO
cd NOME_DO_PROJETO
```

### 2. Construir os containers

```bash
docker compose build
```

### 3. Iniciar o projeto

```bash
docker compose up -d
```

### 4. Verificar os containers

```bash
docker compose ps
```

### 5. Acessar o sistema

Após iniciar os containers, acesse no navegador:

```text
http://localhost
```

Caso a aplicação esteja configurada para outra porta, utilize a porta definida no `docker-compose.yml`.

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

O projeto utiliza o **Supabase** como serviço de banco de dados.

O banco utilizado é baseado em **PostgreSQL**.

As informações de conexão devem ser configuradas por meio de variáveis de ambiente.

> Nunca envie chaves privadas, senhas ou outras credenciais do Supabase diretamente para o GitHub.

## Docker

O ambiente PHP é executado dentro de um container Docker.

O `Dockerfile` é responsável pela configuração do ambiente PHP e pela instalação das dependências necessárias para comunicação com o PostgreSQL.

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
* Integrar a aplicação com um banco PostgreSQL;
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
