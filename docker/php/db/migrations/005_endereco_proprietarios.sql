-- Adiciona endereço em bancos existentes, preservando registros antigos.
-- Pode ser executada novamente caso a coluna já exista.

SET @endereco_existe = (
    SELECT COUNT(*)
    FROM information_schema.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE()
      AND TABLE_NAME = 'proprietarios'
      AND COLUMN_NAME = 'endereco'
);

SET @sql_endereco = IF(
    @endereco_existe = 0,
    'ALTER TABLE proprietarios ADD COLUMN endereco VARCHAR(255) NULL AFTER celular',
    'SELECT 1'
);

PREPARE migracao_endereco FROM @sql_endereco;
EXECUTE migracao_endereco;
DEALLOCATE PREPARE migracao_endereco;

UPDATE proprietarios SET endereco = '' WHERE endereco IS NULL;

ALTER TABLE proprietarios
    MODIFY COLUMN endereco VARCHAR(255) NOT NULL;
