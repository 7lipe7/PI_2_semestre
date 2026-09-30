-- Bloqueio de força bruta: registra as tentativas malsucedidas de login.
-- Fica no banco (e não na sessão) porque o atacante descarta cookies à
-- vontade — só um contador que ele não controla interrompe o ataque.
CREATE TABLE IF NOT EXISTS login_tentativas (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    login     VARCHAR(60)  NOT NULL,
    ip        VARCHAR(45)  NOT NULL,
    criado_em TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    -- A consulta de bloqueio filtra por login+ip dentro da janela de tempo;
    -- o índice evita varrer a tabela inteira a cada tentativa.
    INDEX idx_login_tentativas_login_ip (login, ip, criado_em)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
