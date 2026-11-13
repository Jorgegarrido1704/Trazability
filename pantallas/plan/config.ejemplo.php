<?php
return [
    'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=TU_BASE_CVTS;charset=utf8mb4',
    'usuario' => 'TU_USUARIO',
    'clave' => 'TU_CLAVE',
    // Usa los usuarios autorizados por el servidor web, o integra aquí el permiso de CVTS.
    'autorizar_carga' => static fn (): bool => in_array(
        $_SERVER['REMOTE_USER'] ?? '',
        [],
        true
    ),
];
