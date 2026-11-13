-- Ejecutar una vez en la base de datos de CVTS si la columna customer aún no existe.
ALTER TABLE pantallas ADD COLUMN customer VARCHAR(255) NULL;
