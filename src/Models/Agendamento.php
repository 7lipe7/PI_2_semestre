<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Agendamento
{
    /** Lista canônica dos status aceitos (usada também pelo controller). */
    public const STATUS = ['agendado', 'andamento', 'concluido', 'cancelado'];

    /**
     * Atualiza apenas o status (ação rápida do card).
     */
    public static function updateStatus(int $id, string $status): void
    {
        $stmt = getConnection()->prepare(
            'UPDATE agendamentos
             SET status = :status
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'     => $id,
            ':status' => $status,
        ]);
    }

    /**
     * Verifica se a data + hora já estão ocupadas.
     * Na edição, passe o id do próprio agendamento para ignorá-lo na checagem.
     */
    public static function horarioOcupado(string $data, string $hora, ?int $ignorarId = null): bool
    {
        $sql    = 'SELECT COUNT(*) FROM agendamentos WHERE data = :data AND hora = :hora';
        $params = [':data' => $data, ':hora' => $hora];

        if ($ignorarId !== null) {
            $sql .= ' AND id <> :id';
            $params[':id'] = $ignorarId;
        }

        $stmt = getConnection()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    public static function all(): array
    {
        return getConnection()
            ->query(
                'SELECT
                    a.*,
                    c.nome AS cachorro_nome,
                    p.nome AS proprietario_nome,
                    s.nome AS servico_nome,
                    s.preco AS servico_preco
                FROM agendamentos a
                INNER JOIN cachorros c
                    ON c.id = a.cachorro_id
                INNER JOIN proprietarios p
                    ON p.id = c.proprietario_id
                INNER JOIN servicos s
                    ON s.id = a.servico_id
                ORDER BY a.data, a.hora;'
            )
            ->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = getConnection()->prepare(
            'SELECT
                a.*,
                c.nome AS cachorro_nome,
                p.nome AS proprietario_nome,
                s.nome AS servico_nome
            FROM agendamentos a
            INNER JOIN cachorros c
                ON c.id = a.cachorro_id
            INNER JOIN proprietarios p
                ON p.id = c.proprietario_id
            INNER JOIN servicos s
                ON s.id = a.servico_id
            WHERE a.id = :id
            LIMIT 1;'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $agendamento = $stmt->fetch();

        return $agendamento ? $agendamento : null;
    }

    /**
     * Agenda de um dia específico (usado pelo dashboard da home).
     * Mesmos joins do all(), mas filtrando pela data.
     */
    public static function byData(string $data): array
    {
        $stmt = getConnection()->prepare(
            'SELECT
                a.*,
                c.nome AS cachorro_nome,
                p.nome AS proprietario_nome,
                s.nome AS servico_nome
            FROM agendamentos a
            INNER JOIN cachorros c
                ON c.id = a.cachorro_id
            INNER JOIN proprietarios p
                ON p.id = c.proprietario_id
            INNER JOIN servicos s
                ON s.id = a.servico_id
            WHERE a.data = :data
            ORDER BY a.hora'
        );

        $stmt->execute([
            ':data' => $data
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Marca um novo horário.
     * ATENÇÃO: se a data+hora já estiverem ocupadas, o MySQL recusa
     * (constraint UNIQUE de data+hora) e o PDO lança PDOException —
     * quem trata esse caso é o controller.
     */
    public static function create(
        int $cachorroId,
        int $servicoId,
        string $data,
        string $hora,
        ?string $observacoes = null,
        string $status = 'agendado'
    ): int {
        $stmt = getConnection()->prepare(
                'INSERT INTO agendamentos
                     (cachorro_id, servico_id, data, hora, status, observacoes)
                 VALUES
                     (:cachorro_id, :servico_id, :data, :hora, :status, :observacoes)'
        );

        $stmt->execute([
            ':cachorro_id' => $cachorroId,
            ':servico_id'  => $servicoId,
            ':data'        => $data,
            ':hora'        => $hora,
            ':status'      => $status,
            ':observacoes' => $observacoes,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    /**
     * Atualiza um agendamento.
     */
    public static function update(
        int $id,
        int $cachorroId,
        int $servicoId,
        string $data,
        string $hora,
        ?string $observacoes,
        string $status
    ): void {
        $stmt = getConnection()->prepare(
            'UPDATE agendamentos
             SET cachorro_id = :cachorro_id,
                 servico_id  = :servico_id,
                 data        = :data,
                 hora         = :hora,
                 status      = :status,
                 observacoes = :observacoes
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'          => $id,
            ':cachorro_id' => $cachorroId,
            ':servico_id'  => $servicoId,
            ':data'        => $data,
            ':hora'        => $hora,
            ':status'      => $status,
            ':observacoes' => $observacoes,
        ]);
    }

    // uso da view vw_historico_agendamentos, que traz os dados de cachorro, proprietário e serviço
    public static function historico(): array
{
    return getConnection()
        ->query(
            'SELECT *
             FROM vw_historico_agendamentos
             ORDER BY alterado_em DESC'
        )
        ->fetchAll();
}
    public static function delete(int $id): void
    {
        $stmt = getConnection()->prepare(
            'DELETE FROM agendamentos
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }
}