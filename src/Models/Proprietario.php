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

    public static function find(int $id): ?array
    {
        $stmt = getConnection()->prepare('SELECT * FROM proprietarios WHERE id = :id');
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $nome, string $celular, ?string $observacoes = null): int
    {
        $stmt = getConnection()->prepare(
            'INSERT INTO proprietarios (nome, celular, observacoes)
             VALUES (:nome, :celular, :observacoes)'
        );
        $stmt->execute([
            ':nome'        => $nome,
            ':celular'     => $celular,
            ':observacoes' => $observacoes,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    public static function update(int $id, string $nome, string $celular, ?string $observacoes): void
    {
        $stmt = getConnection()->prepare(
            'UPDATE proprietarios
             SET nome = :nome, celular = :celular, observacoes = :observacoes
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
        $stmt = getConnection()->prepare('DELETE FROM proprietarios WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }
}
