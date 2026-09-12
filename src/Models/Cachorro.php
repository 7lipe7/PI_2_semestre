<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Cachorro
{
    public static function all(?string $busca = null): array
    {
        $sql = 'SELECT c.*, p.nome AS proprietario_nome, p.celular AS proprietario_celular
                FROM cachorros c
                INNER JOIN proprietarios p
                    ON p.id = c.proprietario_id';

        $params = [];

        if ($busca !== null && trim($busca) !== '') {
            $sql .= ' WHERE LOWER(c.nome) LIKE :busca OR LOWER(p.nome) LIKE :busca';
            $params[':busca'] = '%' . mb_strtolower(trim($busca), 'UTF-8') . '%';
        }

        $sql .= ' ORDER BY c.nome';

        $stmt = getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = getConnection()->prepare(
            'SELECT c.*, p.nome AS proprietario_nome, p.celular AS proprietario_celular
             FROM cachorros c
             INNER JOIN proprietarios p
                ON p.id = c.proprietario_id
             WHERE c.id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function create(
        int $proprietarioId,
        string $nome,
        ?string $raca,
        ?string $porte,
        ?string $pelagem,
        ?string $observacoes
    ): int {
        $stmt = getConnection()->prepare(
            'INSERT INTO cachorros
                (proprietario_id, nome, raca, porte, pelagem, observacoes)
             VALUES
                (:proprietario_id, :nome, :raca, :porte, :pelagem, :observacoes)'
        );

        $stmt->execute([
            ':proprietario_id' => $proprietarioId,
            ':nome'            => $nome,
            ':raca'            => $raca,
            ':porte'           => $porte,
            ':pelagem'         => $pelagem,
            ':observacoes'     => $observacoes,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        int $proprietarioId,
        string $nome,
        ?string $raca,
        ?string $porte,
        ?string $pelagem,
        ?string $observacoes
    ): void {
        $stmt = getConnection()->prepare(
            'UPDATE cachorros
             SET proprietario_id = :proprietario_id,
                 nome = :nome,
                 raca = :raca,
                 porte = :porte,
                 pelagem = :pelagem,
                 observacoes = :observacoes
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'              => $id,
            ':proprietario_id' => $proprietarioId,
            ':nome'            => $nome,
            ':raca'            => $raca,
            ':porte'           => $porte,
            ':pelagem'         => $pelagem,
            ':observacoes'     => $observacoes,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = getConnection()->prepare(
            'DELETE FROM cachorros
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }
}
