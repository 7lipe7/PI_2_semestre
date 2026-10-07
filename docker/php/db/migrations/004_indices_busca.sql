-- Otimiza as consultas de busca e listagem por nome/filtro de status.
-- Aplicar em bancos já criados sem recriar o volume do Docker.

CREATE INDEX idx_proprietarios_nome ON proprietarios (nome);
CREATE INDEX idx_cachorros_nome ON cachorros (nome);
CREATE INDEX idx_cachorros_proprietario_id ON cachorros (proprietario_id);
CREATE INDEX idx_agendamentos_status_data ON agendamentos (status, data);
CREATE INDEX idx_agendamentos_cachorro_id_status ON agendamentos (cachorro_id, status);
CREATE INDEX idx_agendamentos_servico_id ON agendamentos (servico_id);
