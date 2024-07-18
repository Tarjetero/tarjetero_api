ALTER TABLE tarjetas ADD COLUMN tipo INT DEFAULT 1;
ALTER TABLE tarjetas ADD COLUMN dia_corte VARCHAR(2);
ALTER TABLE tarjetas ADD COLUMN dia_pago VARCHAR(2);
-- PASAR A STRING codigo_cvv, anio_expiracion