CREATE DATABASE IF NOT EXISTS optica_poo DEFAULT CHARACTER SET utf8mb4 DEFAULT COLLATE utf8mb4_general_ci;

USE optica_poo;

-- Activar eventes en el motot de BD
-- ESTO SE ACTIVA DESPUES (SET GLOBAL event_scheduler=ON)

CREATE TABLE IF NOT EXISTS tbl_roles (
    idRoles INT PRIMARY KEY,
    nombreRol VARCHAR(50) NOT NULL
);

CREATE TABLE IF NOT EXISTS tbl_users (
    idUsers INT PRIMARY KEY NOT NULL,
    nombreUsuario VARCHAR(250) NOT NULL,
    nombreContrasena VARCHAR(25) NOT NULL,
    idRol INT NULL,
    CONSTRAINT fk_rol FOREIGN KEY (idRol) REFERENCES tbl_roles (idRoles) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS tbl_bitacora (
    idBitacora CHAR(36) PRIMARY KEY,
    usuario VARCHAR(25) NOT NULL,
    tabla VARCHAR(20) NOT NULL,
    accion VARCHAR(20) NOT NULL,
    fecha TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    descripcion TEXT NOT NULL,
    query TEXT NULL,
    idQuery CHAR(10) NULL
);

CREATE TABLE IF NOT EXISTS tbl_elementos (
    idElemento INT PRIMARY KEY AUTO_INCREMENT,
    nombreElemento VARCHAR(100) NOT NULL,
    descripcion TEXT,
    cantidad INT NOT NULL DEFAULT 1,
    fechaRegistro TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado ENUM('activo', 'inactivo') NOT NULL DEFAULT 'activo'
);

CREATE TABLE user_creation_queue (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombreUsuario VARCHAR(100) NOT NULL,
    nombreContrasena VARCHAR(100) NOT NULL,
    idRol INT NOT NULL,
    procesado BOOLEAN DEFAULT FALSE
);

INSERT INTO
    tbl_elementos (
        nombreElemento,
        descripcion,
        cantidad,
        estado
    )
VALUES (
        'Computador portátil',
        'Laptop Dell Core i5',
        10,
        'activo'
    ),
    (
        'Proyector',
        'Proyector Epson HD',
        2,
        'activo'
    ),
    (
        'Silla',
        'Silla ergonómica para oficina',
        20,
        'activo'
    );

INSERT INTO
    tbl_roles (idRoles, nombreRol)
VALUES (1, 'Administrador'),
    (2, 'Coordinador'),
    (3, 'Instructor'),
    (4, 'Estudiante');

INSERT INTO
    tbl_users (
        idUsers,
        nombreUsuario,
        nombreContrasena,
        idRol
    )
VALUES (
        12345,
        "marlonReina",
        "marlon123",
        1
    ),
    (
        62334,
        "alejandroCeron",
        "alejanro123",
        2
    );

DELIMITER $$

CREATE TRIGGER after_user_insert
AFTER INSERT ON tbl_users
FOR EACH ROW
BEGIN
    INSERT INTO user_creation_queue (nombreUsuario, nombreContrasena, idRol) 
    VALUES (NEW.nombreUsuario, NEW.nombreContrasena, NEW.idRol);
END$$

DELIMITER;

DELIMITER $$

CREATE EVENT process_user_creation
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE v_nombreUsuario VARCHAR(100);
    DECLARE v_nombreContrasena VARCHAR(100);
    DECLARE v_idRol INT;
    DECLARE v_rolNombre VARCHAR(50);

    -- Cursor para recorrer los usuarios pendientes
    DECLARE cur CURSOR FOR 
    SELECT nombreUsuario, nombreContrasena, idRol FROM user_creation_queue WHERE procesado = FALSE;

    -- Manejo de errores
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;
    
    read_loop: LOOP
        FETCH cur INTO v_nombreUsuario, v_nombreContrasena, v_idRol;
        IF done THEN 
            LEAVE read_loop; 
        END IF;

        -- Obtener el nombre del rol
        SELECT nombreRol INTO v_rolNombre FROM tbl_roles WHERE idRoles = v_idRol;

        -- Ejecutar manualmente en MySQL: CREATE USER y GRANT
        SET @user_query = CONCAT('CREATE USER ''', v_nombreUsuario, '''@''%'' IDENTIFIED BY ''', v_nombreContrasena, ''';');
        SET @grant_query = 
            CASE 
                WHEN v_rolNombre = 'Administrador' THEN CONCAT('GRANT ALL PRIVILEGES ON *.* TO ''', v_nombreUsuario, '''@''%'' WITH GRANT OPTION;')
                WHEN v_rolNombre = 'Coordinador' THEN CONCAT('GRANT SELECT, INSERT, UPDATE ON poo_conn.* TO ''', v_nombreUsuario, '''@''%'';')
                WHEN v_rolNombre = 'Instructor' THEN CONCAT('GRANT SELECT, INSERT, UPDATE ON poo_conn.* TO ''', v_nombreUsuario, '''@''%'';')
                WHEN v_rolNombre = 'Estudiante' THEN CONCAT('GRANT SELECT ON poo_conn.tbl_elementos TO ''', v_nombreUsuario, '''@''%'';')
                ELSE NULL
            END;

        -- Ejecutar en la base de datos INMEDIATAMENTE
        EXECUTE IMMEDIATE @user_query;
        EXECUTE IMMEDIATE @grant_query;
        
        -- Marcar el usuario como procesado
        UPDATE user_creation_queue SET procesado = TRUE WHERE nombreUsuario = v_nombreUsuario;
    END LOOP;

    CLOSE cur;
END$$

DELIMITER;

-- TRIGGER AFTER INSERT
DELIMITER $$

CREATE TRIGGER after_elemento_insert
AFTER INSERT ON tbl_elementos
FOR EACH ROW
BEGIN
    INSERT INTO tbl_bitacora (idBitacora, usuario, tabla, accion, fecha, descripcion)
    VALUES (UUID(), USER(), 'tbl_elementos', 'Insertado', NOW(), 
            CONCAT('Se agregó el elemento "', NEW.nombreElemento, '" con cantidad "', NEW.cantidad, '".'));
END$$

DELIMITER;

--TRIGGER AFTER UPDATE
DELIMITER $$

CREATE TRIGGER after_elemento_update
AFTER UPDATE ON tbl_elementos
FOR EACH ROW
BEGIN
    DECLARE descripcion_texto TEXT;
    DECLARE query_texto TEXT;
    
    SET descripcion_texto = 'Se actualizó el elemento: ';
    
    IF OLD.nombreElemento <> NEW.nombreElemento THEN
        SET descripcion_texto = CONCAT(descripcion_texto, 'Nombre: de "', OLD.nombreElemento, '" a "', NEW.nombreElemento, '". ');
    END IF;
    
    IF OLD.descripcion <> NEW.descripcion THEN
        SET descripcion_texto = CONCAT(descripcion_texto, 'Descripción modificada. ');
    END IF;
    
    IF OLD.cantidad <> NEW.cantidad THEN
        SET descripcion_texto = CONCAT(descripcion_texto, 'Cantidad: de "', OLD.cantidad, '" a "', NEW.cantidad, '". ');
    END IF;

    IF OLD.estado <> NEW.estado THEN
        SET descripcion_texto = CONCAT(descripcion_texto, 'Estado: de "', OLD.estado, '" a "', NEW.estado, '". ');
    END IF;

    IF descripcion_texto = 'Se actualizó el elemento: ' THEN
        SET descripcion_texto = 'No hubo cambios significativos en los datos del elemento.';
    END IF;

    SET query_texto = CONCAT('UPDATE tbl_elementos SET ',
        'nombreElemento = "', OLD.nombreElemento, '", ',
        'descripcion = "', OLD.descripcion, '", ',
        'cantidad = "', OLD.cantidad, '", ',
        'estado = "', OLD.estado, '" ',
        'WHERE idElemento = ', OLD.idElemento, ';');

    INSERT INTO tbl_bitacora (idBitacora,Usuario, Tabla, Accion, Fecha, Descripcion, query, idQuery)
    VALUES (UUID(), USER(), 'tbl_elementos', 'Modificado', NOW(), descripcion_texto, query_texto, OLD.idElemento);
END$$

DELIMITER;

-- TRIGGER AFTER DELETE
DELIMITER / /

CREATE TRIGGER after_delete_elemento
AFTER DELETE ON tbl_elementos
FOR EACH ROW
BEGIN
    -- Declaración de variables con tipo de dato
    DECLARE tbl_nombre VARCHAR(50);
    DECLARE query_text TEXT;
    DECLARE descripcion_texto TEXT;

    -- Asignación de valores
    SET tbl_nombre = 'tbl_elementos';
    SET query_text = CONCAT('INSERT INTO tbl_elementos (nombreElemento, descripcion, cantidad, estado) VALUES("', 
                            OLD.nombreElemento, '", "', OLD.descripcion, '", ', OLD.cantidad, ', "', OLD.estado, '")');

    SET descripcion_texto = CONCAT('Se eliminó el elemento con ID: ', OLD.idElemento, 
    ', Nombre: ', OLD.nombreElemento, 
    ', Descripción: ', OLD.descripcion, 
    ', Cantidad: ', OLD.cantidad);

    -- Inserción en la bitácora
    INSERT INTO tbl_bitacora (idBitacora, usuario, descripcion, tabla, accion, fecha, query, idQuery)
    VALUES (
        UUID(),
        USER(),
        descripcion_texto,  
        tbl_nombre,         
        'DELETE',           
        NOW(),              
        query_text,
        OLD.idElemento       
    );
END //

DELIMITER;