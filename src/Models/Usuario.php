<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Usuario
{
    public static function findByLogin(string $login): ?array
    {
        $stmt = getConnection()->prepare(
            'SELECT id, nome, login, senha_hash
             FROM usuarios
             WHERE login = :login
             LIMIT 1'
        );

        $stmt->execute([':login' => $login]);

        $usuario = $stmt->fetch();

        return $usuario ?: null;
    }

    /**
     * Confere a senha. Se o hash estiver com custo antigo,
     * refaz com o custo atual (mantém a tabela saudável).
     */
    public static function verificarSenha(array $usuario, string $senha): bool
    {
        $hash = (string) $usuario['senha_hash'];

        if (!password_verify($senha, $hash)) {
            return false;
        }

        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            self::atualizarSenha((int) $usuario['id'], $senha);
        }

        return true;
    }

    public static function atualizarSenha(int $id, string $senha): void
    {
        $stmt = getConnection()->prepare(
            'UPDATE usuarios SET senha_hash = :hash WHERE id = :id'
        );

        $stmt->execute([
            ':hash' => password_hash($senha, PASSWORD_DEFAULT),
            ':id'   => $id,
        ]);
    }
}
