<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Agendamento
{
    public static function all(): array
    {
        return getConnection()
            ->query(
                'SELECT
                    a.*,
                    c.nome AS cachorro_nome,
                    p.nome AS proprietario_nome,
                    s.nome AS servico
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
                s.nome AS servico
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
                s.nome AS servico
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