# CVTS | Planeación diaria

La pantalla muestra los registros de `pantallas` con `pantalla = 'plan'` y `fecha_up` del día actual en la zona `America/Mexico_City`. La fecha requerida del cliente se guarda en `fecha` y puede corresponder a cualquier día. La pantalla se actualiza cada minuto y también tiene un botón de actualización.

## Conectar con CVTS

1. Usa PHP 8.2 o superior con la extensión `pdo_mysql` habilitada. El módulo usa la tabla `pantallas` existente y presupone que `id` se genera automáticamente y que `fecha_up` y `fecha` son campos de fecha. Usa una tabla transaccional (por ejemplo, InnoDB) para que un error revierta la carga completa.
2. Copia `config.ejemplo.php` a `config.local.php` y coloca los datos de conexión de CVTS. La cuenta necesita permisos de lectura, inserción y eliminación sobre `pantallas`.
3. En phpMyAdmin, selecciona la base de datos que contiene `pantallas` y ejecuta `agregar_customer.sql` si todavía no existe la columna `customer`. Los registros históricos sin cliente mostrarán un guion.
4. Configura `autorizar_carga` en `config.local.php`. Si CVTS autentica con Apache/IIS y expone `REMOTE_USER`, agrega en el arreglo los nombres exactos de los usuarios con permiso. Si CVTS usa sesión PHP, usa su sesión y su comprobación real de permisos. El formulario aparece únicamente para usuarios autorizados. Si CVTS usa un nombre o una ruta de cookie de sesión personalizada, aplica esa configuración antes de `session_start()` en `public/cargar_csv.php`.
5. Para una prueba local, ejecuta desde esta carpeta:

   ```bash
   php -S 127.0.0.1:8000 -t public
   ```

   Abre [http://127.0.0.1:8000](http://127.0.0.1:8000). En el servidor de CVTS, sirve únicamente `public/` bajo el acceso interno de CVTS y conserva la estructura de carpetas. El archivo de conexión y el importador van fuera de la raíz web. Si Apache ya usa el puerto 8000, elige otro puerto libre para la prueba.

## Cargar el CSV

1. Completa el Excel y expórtalo como **CSV UTF-8**. Puedes empezar con `plantilla_planeacion.csv`, que contiene los mismos encabezados que tu Excel. Conserva las once columnas, incluidas las dos `L` y las dos `E`. Customer va primero y es obligatorio. Se admiten coma y punto y coma como separadores.
2. Mantén Número de parte y WO en formato Texto en Excel si llevan ceros a la izquierda. Usa una cantidad entera no negativa y una fecha requerida en formato `AAAA-MM-DD` o `DD/MM/AAAA`. Las cinco columnas intermedias y los comentarios pueden ir vacíos. Los comentarios pueden incluir saltos de línea.
3. En `public/index.html`, selecciona el archivo y pulsa **Cargar CSV**. La tabla se actualizará al confirmar la carga. Para ejecutar la misma carga desde terminal, usa:

   ```bash
   php importar_planeacion.php "mi_planeacion.csv"
   ```

   El programa asigna `plan` y la fecha del día de la carga. Cada carga exitosa reemplaza todo el plan del día. Sube el CSV completo, con todas las órdenes que deben aparecer. Se conservan los planes de otros días y los registros de otras pantallas. Si el archivo está vacío, contiene errores o falla una inserción, se conserva la carga anterior. El mensaje de salida indica cuántos registros anteriores se eliminaron y cuántos se insertaron, o el número de registro que provocó el error, contando el encabezado como registro 1.

| Posición | Excel / CSV | Campo en `pantallas` |
| ---: | --- | --- |
| 1 | Customer | `customer` |
| 2 | Numero de parte | `np` |
| 3 | WO | `wo` |
| 4 | Cantidad | `qty_wo` |
| 5 | L | `corte` |
| 6 | E | `ensa` |
| 7 | L | `loom` |
| 8 | C | `cal` |
| 9 | E | `emb` |
| 10 | Fecha requerida | `fecha` |
| 11 | Commentarios | `comentarios` |
