CREATE DATABASE IF NOT EXISTS arneses
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE arneses;

/* =========================================================
   HOUSINGS
========================================================= */

CREATE TABLE conectores (
    pn VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,
    cavidades TINYINT UNSIGNED NOT NULL,
    profundidad_mm DECIMAL(10,3) NOT NULL DEFAULT 0,

    PRIMARY KEY (pn),

    CHECK (cavidades BETWEEN 1 AND 50),
    CHECK (profundidad_mm >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================================================
   COMPONENTES
========================================================= */

CREATE TABLE componentes (
    pn VARCHAR(100) NOT NULL,
    descripcion VARCHAR(255) NOT NULL,

    tipo ENUM(
        'terminal',
        'sello',
        'accesorio'
    ) NOT NULL,

    PRIMARY KEY (pn)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================================================
   TERMINALES COMPATIBLES
========================================================= */

CREATE TABLE terminales_compatibles (
    conector_pn VARCHAR(100) NOT NULL,
    terminal_pn VARCHAR(100) NOT NULL,

    strip_mm DECIMAL(10,3) NOT NULL,
    consumo_mm DECIMAL(10,3) NOT NULL,

    PRIMARY KEY (
        conector_pn,
        terminal_pn
    ),

    CONSTRAINT fk_terminal_conector
        FOREIGN KEY (conector_pn)
        REFERENCES conectores(pn),

    CONSTRAINT fk_terminal_componente
        FOREIGN KEY (terminal_pn)
        REFERENCES componentes(pn),

    CHECK (strip_mm >= 0),
    CHECK (consumo_mm >= 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================================================
   ASIGNACIÓN POR VÍA
========================================================= */

CREATE TABLE vias_conector (
    conector_pn VARCHAR(100) NOT NULL,
    via TINYINT UNSIGNED NOT NULL,

    terminal_pn VARCHAR(100) NULL,
    sello_pn VARCHAR(100) NULL,

    PRIMARY KEY (
        conector_pn,
        via
    ),

    CONSTRAINT fk_via_conector
        FOREIGN KEY (conector_pn)
        REFERENCES conectores(pn),

    CONSTRAINT fk_via_terminal_compatible
        FOREIGN KEY (
            conector_pn,
            terminal_pn
        )
        REFERENCES terminales_compatibles(
            conector_pn,
            terminal_pn
        ),

    CONSTRAINT fk_via_sello
        FOREIGN KEY (sello_pn)
        REFERENCES componentes(pn),

    CHECK (via BETWEEN 1 AND 50)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================================================
   ACCESORIOS POR CONECTOR
========================================================= */

CREATE TABLE componentes_conector (
    conector_pn VARCHAR(100) NOT NULL,
    componente_pn VARCHAR(100) NOT NULL,

    cantidad DECIMAL(10,3) NOT NULL DEFAULT 1,

    PRIMARY KEY (
        conector_pn,
        componente_pn
    ),

    CONSTRAINT fk_accesorio_conector
        FOREIGN KEY (conector_pn)
        REFERENCES conectores(pn),

    CONSTRAINT fk_accesorio_componente
        FOREIGN KEY (componente_pn)
        REFERENCES componentes(pn),

    CHECK (cantidad > 0)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/* =========================================================
   CATÁLOGO FICTICIO
   NO UTILIZAR PARA FABRICACIÓN
========================================================= */

INSERT INTO conectores (
    pn,
    descripcion,
    cavidades,
    profundidad_mm
) VALUES
(
    'DEMO-C2',
    'Conector ficticio de 2 vías',
    2,
    20
),
(
    'DEMO-C4',
    'Conector ficticio de 4 vías',
    4,
    28
);

INSERT INTO componentes (
    pn,
    descripcion,
    tipo
) VALUES
(
    'DEMO-T18',
    'Terminal ficticia para prueba 18 AWG',
    'terminal'
),
(
    'DEMO-T16',
    'Terminal ficticia para prueba 16 AWG',
    'terminal'
),
(
    'DEMO-S18',
    'Sello ficticio para prueba 18 AWG',
    'sello'
),
(
    'DEMO-S16',
    'Sello ficticio para prueba 16 AWG',
    'sello'
),
(
    'DEMO-LOCK2',
    'Seguro secundario ficticio',
    'accesorio'
),
(
    'DEMO-LOCK4',
    'Seguro secundario ficticio',
    'accesorio'
),
(
    'DEMO-CAP4',
    'Tapa posterior ficticia',
    'accesorio'
);

INSERT INTO terminales_compatibles (
    conector_pn,
    terminal_pn,
    strip_mm,
    consumo_mm
) VALUES
(
    'DEMO-C2',
    'DEMO-T18',
    5,
    3
),
(
    'DEMO-C4',
    'DEMO-T18',
    5,
    3
),
(
    'DEMO-C4',
    'DEMO-T16',
    6,
    4
);

INSERT INTO vias_conector (
    conector_pn,
    via,
    terminal_pn,
    sello_pn
) VALUES
(
    'DEMO-C2',
    1,
    'DEMO-T18',
    'DEMO-S18'
),
(
    'DEMO-C2',
    2,
    'DEMO-T18',
    'DEMO-S18'
),
(
    'DEMO-C4',
    1,
    'DEMO-T18',
    'DEMO-S18'
),
(
    'DEMO-C4',
    2,
    'DEMO-T18',
    'DEMO-S18'
),
(
    'DEMO-C4',
    3,
    'DEMO-T16',
    'DEMO-S16'
),
(
    'DEMO-C4',
    4,
    'DEMO-T16',
    'DEMO-S16'
);

INSERT INTO componentes_conector (
    conector_pn,
    componente_pn,
    cantidad
) VALUES
(
    'DEMO-C2',
    'DEMO-LOCK2',
    1
),
(
    'DEMO-C4',
    'DEMO-LOCK4',
    1
),
(
    'DEMO-C4',
    'DEMO-CAP4',
    1
);

/*
 * OPCIONAL:
 * Ejecutar con un usuario administrador si necesitas crear
 * un usuario de consulta para la API.
 *
 * Usa la misma contraseña que configures en conectores.php.
 */

/*
CREATE USER IF NOT EXISTS
    'arneses_app'@'127.0.0.1'
    IDENTIFIED BY 'CAMBIA_ESTA_CLAVE';

GRANT SELECT ON arneses.*
    TO 'arneses_app'@'127.0.0.1';
*