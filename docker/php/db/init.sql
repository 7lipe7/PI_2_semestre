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
    servico     ENUM('banho', 'tosa', 'banho_e_tosa') NOT NULL,
    data        DATE NOT NULL,
    hora        TIME NOT NULL,
    status      ENUM('agendado', 'concluido', 'cancelado') NOT NULL DEFAULT 'agendado',
    observacoes TEXT NULL,
    criado_em   TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    atualizado_em TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_agendamento_cachorro
        FOREIGN KEY (cachorro_id) REFERENCES cachorros (id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    -- Impede dois cachorros no mesmo horário (resolve o problema central!)
    CONSTRAINT uq_agenda_horario UNIQUE (data, hora)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE precos_servicos (
    id      INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    servico ENUM('banho', 'tosa', 'banho_e_tosa') NOT NULL,
    porte   ENUM('pequeno', 'medio', 'grande') NOT NULL,
    valor   DECIMAL(10,2) NOT NULL,
    UNIQUE KEY uq_servico_porte (servico, porte)
);


-- caso login de usuario futuro

CREATE TABLE usuarios (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nome       VARCHAR(120) NOT NULL,
    login      VARCHAR(60)  NOT NULL UNIQUE,
    senha_hash VARCHAR(255) NOT NULL,
    criado_em  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
 
INSERT INTO proprietarios (nome, celular) VALUES
    ('João Silva', '(19) 99999-9999'),
    ('Maria Souza', '(19) 98888-8888');

INSERT INTO cachorros (proprietario_id, nome, raca, porte, pelagem) VALUES
    (1, 'Thor', 'Golden Retriever', 'grande', 'longa'),
    (2, 'Mel',  'Poodle',           'pequeno', 'cacheada');

INSERT INTO agendamentos (cachorro_id, servico, data, hora, observacoes) VALUES
    (1, 'banho_e_tosa', CURDATE(), '10:00:00', 'Usar shampoo de fresco '),
    (2, 'banho',        CURDATE(), '14:00:00', NULL);




    -- proprietarios (1) ──── (N) cachorros (1) ──── (N) agendamentos
    --                                         └── servico, data, hora, status
