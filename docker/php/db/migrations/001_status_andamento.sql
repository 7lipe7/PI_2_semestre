-- Adiciona 'andamento' ao fluxo de status dos agendamentos
ALTER TABLE agendamentos
    MODIFY status ENUM('agendado','andamento','concluido','cancelado')
    NOT NULL DEFAULT 'agendado';

ALTER TABLE historico_agendamentos
    MODIFY status_anterior ENUM('agendado','andamento','concluido','cancelado') NOT NULL,
    MODIFY status_novo     ENUM('agendado','andamento','concluido','cancelado') NOT NULL;
