<?php

declare(strict_types=1);

/**
 * Model da tabela login_tentativas (proteção contra força bruta).
 *
 * O contador vive no banco, e não na sessão, porque a sessão é controlada
 * pelo cliente: basta descartar o cookie para zerar a contagem. Um atacante
 * testando senhas descartaria o cookie a cada tentativa e nunca bateria no
 * limite. Aqui ele teria de INSERT a cada erro.
 */
class TentativaLogin
{
    /** Mesmo tamanho da coluna usuarios.login e de login_tentativas.login. */
    private const TAMANHO_MAXIMO_LOGIN = 60;

    /** Comporta IPv4 e IPv6 por inteiro (o máximo de chars de um IP IPv6). */
    private const TAMANHO_MAXIMO_IP = 45;

    /**
     * Quantas tentativas o mesmo login+IP acumulou dentro da janela.
     * $minutos = null conta o histórico inteiro (usado na limpeza).
     */
    public static function contar(string $login, string $ip, ?int $minutos = null): int
    {
        $sql    = 'SELECT COUNT(*) FROM login_tentativas WHERE login = :login AND ip = :ip';
        $params = [':login' => $login, ':ip' => $ip];

        if ($minutos !== null) {
            // NOW() usa o fuso do servidor MySQL; a tabela grava em TIMESTAMP.
            $sql .= ' AND criado_em > DATE_SUB(NOW(), INTERVAL :minutos MINUTE)';
            $params[':minutos'] = $minutos;
        }

        $stmt = getConnection()->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    /** Registra uma tentativa malsucedida. */
    public static function registrar(string $login, string $ip): void
    {
        // A coluna é VARCHAR(60), igual ao login de usuarios. Sem truncar,
        // um login maior causaria "Data too long" e derrubaria a tela com
        // HTTP 500 — um visitante anônimo derrubaria o login só de digitar
        // uma string longa no campo.
        $stmt = getConnection()->prepare(
            'INSERT INTO login_tentativas (login, ip) VALUES (:login, :ip)'
        );

        $stmt->execute([
            ':login' => mb_substr($login, 0, self::TAMANHO_MAXIMO_LOGIN),
            ':ip'    => mb_substr($ip, 0, self::TAMANHO_MAXIMO_IP),
        ]);
    }

    /**
     * Zera o contador após um login bem-sucedido: o próximo erro do usuário
     * legítimo começa de novo, em vez de herdrar o histórico de um ataque
     * que passou antes de o bloqueio entrar em vigor.
     */
    public static function limpar(string $login, string $ip): void
    {
        $stmt = getConnection()->prepare(
            'DELETE FROM login_tentativas WHERE login = :login AND ip = :ip'
        );

        $stmt->execute([
            ':login' => mb_substr($login, 0, self::TAMANHO_MAXIMO_LOGIN),
            ':ip'    => mb_substr($ip, 0, self::TAMANHO_MAXIMO_IP),
        ]);
    }

    /**
     * Remove registros antigos (chamado pelo cron, não a cada requisição).
     * Sem isso a tabela cresceria para sempre.
     */
    public static function limparAntigos(int $dias = 30): int
    {
        $stmt = getConnection()->prepare(
            'DELETE FROM login_tentativas
             WHERE criado_em < DATE_SUB(NOW(), INTERVAL :dias DAY)'
        );

        $stmt->execute([':dias' => $dias]);

        return $stmt->rowCount();
    }
}
