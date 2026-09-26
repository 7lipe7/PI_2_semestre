-- Excluir um agendamento deve remover também o histórico dele
ALTER TABLE historico_agendamentos
    DROP FOREIGN KEY fk_historico_agendamento;

ALTER TABLE historico_agendamentos
    ADD CONSTRAINT fk_historico_agendamento
    FOREIGN KEY (agendamento_id) REFERENCES agendamentos (id)
    ON DELETE CASCADE ON UPDATE CASCADE;
