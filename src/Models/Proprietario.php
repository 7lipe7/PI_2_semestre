<?php

declare(strict_types=1);

/**
 * Model da tabela proprietarios.
 * Métodos estáticos — não precisamos de instância,
 * cada operação fala direto com o PDO.
 */

require_once __DIR__ . '/../config/database.php';

class Proprietario
{
    public static function all(): array
    {
        return getConnection()
            ->query('SELECT * FROM proprietarios ORDER BY nome')
            ->fetchAll();
    }

    public static function allWithVisitas(?string $busca = null): array
    {
        $busca = trim((string) $busca);

        $sql = 'SELECT p.id, p.nome, p.celular, p.observacoes,
                       GROUP_CONCAT(DISTINCT c.nome ORDER BY c.nome SEPARATOR ", ") AS pets,
                       COUNT(a.id) AS visitas,
                       COALESCE(SUM(
                           COALESCE(s.preco, 0)
                       ), 0) AS total_gasto
                FROM proprietarios p
                LEFT JOIN cachorros c ON c.proprietario_id = p.id
                LEFT JOIN agendamentos a ON a.cachorro_id = c.id
                LEFT JOIN servicos s ON s.id = a.servico_id';

        $params = [];

        if ($busca !== '') {
            $sql .= ' WHERE p.nome LIKE :busca';
            $params[':busca'] = '%' . $busca . '%';
        }

        $sql .= '
            GROUP BY
                p.id,
                p.nome,
                p.celular,
                p.observacoes
            ORDER BY p.nome';

        $stmt = getConnection()->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = getConnection()->prepare(
            'SELECT *
             FROM proprietarios
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);

        $row = $stmt->fetch();

        return $row ?: null;
    }

    public static function create(
        string $nome,
        string $celular,
        ?string $observacoes = null
    ): int {
        $stmt = getConnection()->prepare(
            'INSERT INTO proprietarios
                (nome, celular, observacoes)
             VALUES
                (:nome, :celular, :observacoes)'
        );

        $stmt->execute([
            ':nome'        => $nome,
            ':celular'     => $celular,
            ':observacoes' => $observacoes,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        string $nome,
        string $celular,
        ?string $observacoes
    ): void {
        $stmt = getConnection()->prepare(
            'UPDATE proprietarios
             SET nome = :nome,
                 celular = :celular,
                 observacoes = :observacoes
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'          => $id,
            ':nome'        => $nome,
            ':celular'     => $celular,
            ':observacoes' => $observacoes,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = getConnection()->prepare(
            'DELETE FROM proprietarios
             WHERE id = :id'
        );

        $stmt->execute([
            ':id' => $id
        ]);
    }
}