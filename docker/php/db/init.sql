-- Garante que o cliente MySQL leia este arquivo como UTF-8
-- (sem isso, "João" é salvo como "JoÃ£o" — double encoding)
SET NAMES utf8mb4;

CREATE TABLE proprietarios (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome          VARCHAR(120) NOT NULL,
    celular       VARCHAR(20)  NOT NULL,
    observacoes   TEXT NULL,
    criado_em     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE cachorros (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    proprietario_id INT UNSIGNED NOT NULL,
    nome            VARCHAR(80) NOT NULL,
    raca            VARCHAR(80) NULL,
    porte           ENUM('pequeno', 'medio', 'grande') NULL,
    pelagem         VARCHAR(80) NULL,
    observacoes     TEXT NULL,          -- características do animal
    criado_em       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_cachorro_proprietario
        FOREIGN KEY (proprietario_id) REFERENCES proprietarios (id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE agendamentos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cachorro_id INT UNSIGNED NOT NULL,
    servico_id  INT UNSIGNED NOT NULL,
    data        DATE NOT NULL,
    hora        TIME NOT NULL,
    status      ENUM('agendado', 'andamento', 'concluido', 'cancelado') NOT NULL DEFAULT 'agendado',
    observacoes TEXT NULL,
    criado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    CONSTRAINT fk_agendamento_cachorro
        FOREIGN KEY (cachorro_id) REFERENCES cachorros (id)
        ON DELETE CASCADE ON UPDATE CASCADE,

    CONSTRAINT uq_agenda_horario UNIQUE (data, hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE servicos (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome        VARCHAR(80) NOT NULL,
    descricao   VARCHAR(255) NULL,
    preco       DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    ativo       TINYINT(1) NOT NULL DEFAULT 1,
    criado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE agendamentos
    ADD CONSTRAINT fk_agendamento_servico
    FOREIGN KEY (servico_id) REFERENCES servicos (id)
    ON DELETE RESTRICT ON UPDATE CASCADE;

INSERT IGNORE INTO servicos (id, nome, descricao, preco) VALUES
    (1, 'Banho',         'Banho completo com hidratação', 40.00),
    (2, 'Tosa',          'Tosa higiênica ou da raça', 60.00),
    (3, 'Banho e Tosa',  'Banho + tosa completa', 100.00);

-- caso login de usuario futuro

CREATE TABLE usuarios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(120) NOT NULL,
    login      VARCHAR(60)  NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Usando triggers para registrar histórico de alterações de status dos agendamentos

CREATE TABLE historico_agendamentos (
    id        INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    agendamento_id INT UNSIGNED NOT NULL,
    status_anterior ENUM('agendado', 'andamento', 'concluido', 'cancelado') NOT NULL,
    status_novo ENUM('agendado', 'andamento', 'concluido', 'cancelado') NOT NULL,
    alterado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_historico_agendamento
        FOREIGN KEY (agendamento_id) REFERENCES agendamentos (id)
        ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- TRIGGER PARA REGISTRAR O HISTÓRICO DE ALTERAÇÕES DE STATUS DOS AGENDAMENTOS
DELIMITER //

CREATE TRIGGER trg_historico_status_agendamento
AFTER UPDATE ON agendamentos
FOR EACH ROW
BEGIN
    IF OLD.status <> NEW.status THEN
        INSERT INTO historico_agendamentos
            (agendamento_id, status_anterior, status_novo)
        VALUES
            (NEW.id, OLD.status, NEW.status);
    END IF;
END//

DELIMITER ;

-- VIEW PARA CONSULTAR O HISTÓRICO DE AGENDAMENTOS
CREATE VIEW vw_historico_agendamentos AS
SELECT
    h.id AS historico_id,
    a.id AS agendamento_id,
    c.nome AS cachorro,
    p.nome AS proprietario,
    s.nome AS servico,
    h.status_anterior,
    h.status_novo,
    h.alterado_em
FROM historico_agendamentos h
INNER JOIN agendamentos a
    ON h.agendamento_id = a.id
INNER JOIN cachorros c
    ON a.cachorro_id = c.id
INNER JOIN proprietarios p
    ON c.proprietario_id = p.id
INNER JOIN servicos s
    ON a.servico_id = s.id;

 
INSERT INTO proprietarios (nome, celular) VALUES
    ('João Silva', '(19) 99999-9999'),
    ('Maria Souza', '(19) 98888-8888');

INSERT INTO cachorros (proprietario_id, nome, raca, porte, pelagem) VALUES
    (1, 'Thor', 'Golden Retriever', 'grande', 'longa'),
    (2, 'Mel',  'Poodle',           'pequeno', 'cacheada');

INSERT INTO agendamentos (cachorro_id, servico_id, data, hora, observacoes) VALUES
    (1, 3, CURDATE(), '10:00:00', 'Usar shampoo de fresco '),
    (2, 1, CURDATE(), '14:00:00', NULL);




    -- proprietarios (1) ──── (N) cachorros (1) ──── (N) agendamentos
    --                                         └── servico, data, hora, status
