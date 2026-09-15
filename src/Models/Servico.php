<?php

declare(strict_types=1);

/**
 * Model da tabela servicos (catálogo de serviços e valores).
 */

require_once __DIR__ . '/../config/database.php';

class Servico
{
    public static function all(bool $somenteAtivos = false): array
    {
        $sql = 'SELECT * FROM servicos';

        if ($somenteAtivos) {
            $sql .= ' WHERE ativo = 1';
        }

        $sql .= ' ORDER BY nome';

        return getConnection()->query($sql)->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = getConnection()->prepare('SELECT * FROM servicos WHERE id = :id');
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch();
        return $row ?: null;
    }

    public static function create(string $nome, ?string $descricao, float $preco, int $ativo = 1): int
    {
        $stmt = getConnection()->prepare(
            'INSERT INTO servicos (nome, descricao, preco, ativo)
             VALUES (:nome, :descricao, :preco, :ativo)'
        );

        $stmt->execute([
            ':nome'      => $nome,
            ':descricao' => $descricao,
            ':preco'     => $preco,
            ':ativo'     => $ativo,
        ]);

        return (int) getConnection()->lastInsertId();
    }

    public static function update(int $id, string $nome, ?string $descricao, float $preco, int $ativo): void
    {
        $stmt = getConnection()->prepare(
            'UPDATE servicos
             SET nome = :nome, descricao = :descricao, preco = :preco, ativo = :ativo
             WHERE id = :id'
        );

        $stmt->execute([
            ':id'        => $id,
            ':nome'      => $nome,
            ':descricao' => $descricao,
            ':preco'     => $preco,
            ':ativo'     => $ativo,
        ]);
    }

    public static function delete(int $id): void
    {
        $stmt = getConnection()->prepare('DELETE FROM servicos WHERE id = :id');
        $stmt->execute([':id' => $id]);
    }

    /**
     * Converte o valor digitado ("45,00", "R$ 45,00", "45.00") em float.
     * Retorna null se não der para interpretar como número válido.
     */
    public static function parsePreco(string $valor): ?float
    {
        $limpo = preg_replace('/[^0-9,.]/', '', $valor);

        if ($limpo === null || $limpo === '') {
            return null;
        }

        $limpo = str_replace(',', '.', $limpo);

        return is_numeric($limpo) ? (float) $limpo : null;
    }
}
