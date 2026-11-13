# CVTS | Planeación diaria

La pantalla muestra los registros de `pantallas` con `pantalla = 'plan'` y `fecha_up` del día actual en la zona `America/Mexico_City`. La fecha requerida del cliente se guarda en `fecha` y puede corresponder a cualquier día. La pantalla se actualiza cada minuto y también tiene un botón de actualización.

## Conectar con CVTS

1. Usa PHP 8.2 o superior con la extensión `pdo_mysql` habilitada. El módulo usa la tabla `pantallas` existente y presupone que `id` se genera automáticamente y que `fecha_up` y `fecha` son campos de fecha. Usa una tabla transaccional (por ejemplo, InnoDB) para que un error revierta la carga completa.
2. Copia `config.ejemplo.php` a `config.local.php` y coloca los datos de conexión de CVTS. La cuenta necesita permisos de lectura, inserción y eliminación sobre `pantallas`.
3. Para una prueba local, ejecuta desde esta carpeta:

   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

   Abre [http://127.0.0.1:8000](http://127.0.0.1:8000). En el servidor de CVTS, sirve únicamente `public/` bajo el acceso interno de CVTS y conserva la estructura de carpetas. El archivo de conexión y el importador van fuera de la raíz web. Si Apache ya usa el puerto 8000, elige otro puerto libre para la prueba.

## Cargar el CSV

1. Completa el Excel y expórtalo como **CSV UTF-8**. Puedes empezar con `plantilla_planeacion.csv`, que contiene los mismos encabezados que tu Excel. Conserva las diez columnas, incluidas las dos `L` y las dos `E`. Se admiten coma y punto y coma como separadores.
2. Mantén Número de parte y WO en formato Texto en Excel si llevan ceros a la izquierda. Usa una cantidad entera no negativa y una fecha requerida en formato `AAAA-MM-DD` o `DD/MM/AAAA`. Las cinco columnas intermedias y los comentarios pueden ir vacíos. Los comentarios pueden incluir saltos de línea.
3. Desde la carpeta del módulo, ejecuta:

   ```bash
   php importar_planeacion.php "mi_planeacion.csv"
   ```

   El programa asigna `plan` y la fecha del día de la carga. Cada carga exitosa reemplaza todo el plan del día. Sube el CSV completo, con todas las órdenes que deben aparecer. Se conservan los planes de otros días y los registros de otras pantallas. Si el archivo está vacío, contiene errores o falla una inserción, se conserva la carga anterior. El mensaje de salida indica cuántos registros anteriores se eliminaron y cuántos se insertaron, o el número de registro que provocó el error, contando el encabezado como registro 1.

| Posición | Excel / CSV | Campo en `pantallas` |
| ---: | --- | --- |
| 1 | Numero de parte | `np` |
| 2 | WO | `wo` |
| 3 | Cantidad | `qty_wo` |
| 4 | L | `corte` |
| 5 | E | `ensa` |
| 6 | L | `loom` |
| 7 | C | `cal` |
| 8 | E | `emb` |
| 9 | Fecha requerida | `fecha` |
| 10 | Commentarios | `comentarios` |
