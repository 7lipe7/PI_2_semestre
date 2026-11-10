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
                       GROUP_CONCAT(DISTINCT c.nome ORDER BY c.nome SEPARATOR \', \') AS pets,
                       COUNT(a.id) AS visitas,
                       COALESCE(SUM(
                           COALESCE(s.preco, 0)
                       ), 0) AS total_gasto
                FROM proprietarios p
                LEFT JOIN cachorros c ON c.proprietario_id = p.id
                LEFT JOIN agendamentos a ON a.cachorro_id = c.id
                    AND a.status = \'concluido\'
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
        ?string $observacoes = null,
        ?string $endereco = null
    ): int {
        $stmt = getConnection()->prepare(
            'INSERT INTO proprietarios
                (nome, celular, observacoes, endereco)
             VALUES
                (:nome, :celular, :observacoes, :endereco)'
        );

        $stmt->execute([
            ':nome'        => $nome,
            ':celular'     => $celular,
            ':observacoes' => $observacoes,
            ':endereco'    => $endereco,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    public static function update(
        int $id,
        string $nome,
        string $celular,
        ?string $observacoes,
        ?string $endereco
    ): void {
        $stmt = getConnection()->prepare(
            'UPDATE proprietarios
             SET nome = :nome,
                 celular = :celular,
                 observacoes = :observacoes,
                 endereco = :endereco
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'          => $id,
            ':nome'        => $nome,
            ':celular'     => $celular,
            ':observacoes' => $observacoes,
            ':endereco'    => $endereco,
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