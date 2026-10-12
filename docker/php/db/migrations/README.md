# Migrations do banco

Arquivos numerados para atualizar bancos que **já existem**. O `init.sql` (pasta acima) só roda na primeira subida do container, com volume limpo.

| Arquivo | O que faz |
|---|---|
| `001_status_andamento.sql` | Adiciona o status `andamento` ao ENUM de `agendamentos` e do histórico |
| `002_historico_fk_cascade.sql` | `fk_historico_agendamento` passa de `RESTRICT` para `CASCADE` (excluir agendamento apaga o histórico dele) |
| `003_login_tentativas.sql` | Cria `login_tentativas`, o contador de tentativas para o bloqueio de força bruta do login |
| `004_indices_busca.sql` | Cria os índices de busca por nome e de filtro por status/data nas listagens |

## Regra

Mudou o schema? Faça **as duas** coisas:

1. Atualize o `docker/php/db/init.sql`;
2. Crie o próximo arquivo numerado aqui (ex.: `004_....sql`), com comandos idempotentes sempre que possível (`ALTER ... MODIFY`, `IF NOT EXISTS`).

## Como aplicar

```bash
docker compose exec -T db sh -c 'exec mysql -u root -p"$MYSQL_ROOT_PASSWORD" "$MYSQL_DATABASE"' < docker/php/db/migrations/001_status_andamento.sql
```

Rode **na ordem numérica** — por exemplo, sem a `003` o login quebra com
`Table 'petshop.login_tentativas' doesn't exist`, porque o PHP consulta essa
tabela a cada tentativa de login.

Nunca use `docker compose down -v` para "consertar" o banco: o `-v` apaga o volume `db_data` com todos os dados. Em caso de dúvida, rode os arquivos na ordem numérica.
