-- 📊 DIAGRAMA DE BASE DE DATOS NORMALIZADO
-- 1. BASE DE DATOS PRINCIPAL
CREATE DATABASE IF NOT EXISTS clubpension;
USE clubpension;

-- 2. TABLA: catalogo_institutos
CREATE TABLE catalogo_institutos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    activo BOOLEAN DEFAULT TRUE
);

-- 3. TABLA: catalogo_regimenes
CREATE TABLE catalogo_regimenes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    instituto_id INT NOT NULL,
    codigo VARCHAR(10) NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    FOREIGN KEY (instituto_id) REFERENCES catalogo_institutos(id),
    UNIQUE KEY unique_regimen (instituto_id, codigo)
);

-- 4. TABLA: catalogo_tramites
CREATE TABLE catalogo_tramites (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
);

-- 5. TABLA: catalogo_modalidades
CREATE TABLE catalogo_modalidades (
    id INT PRIMARY KEY AUTO_INCREMENT,
    codigo VARCHAR(10) UNIQUE NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    activo BOOLEAN DEFAULT TRUE
);

-- 6. TABLA: catalogo_documentos
CREATE TABLE catalogo_documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tipo ENUM('cliente', 'beneficiario') NOT NULL,
    nombre VARCHAR(50) NOT NULL,
    descripcion TEXT,
    obligatorio BOOLEAN DEFAULT FALSE,
    orden INT DEFAULT 0,
    activo BOOLEAN DEFAULT TRUE,
    UNIQUE KEY unique_documento (tipo, nombre)
);

-- 7. TABLA PRINCIPAL: clientes
CREATE TABLE clientes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    no_cliente VARCHAR(20) UNIQUE,
    tipo_cliente ENUM('C', 'P', 'S', 'B', 'I') NOT NULL COMMENT 'C=Cliente, P=Prospecto, S=Suspendido, B=Baja, I=Imposible',
    
    -- Datos personales
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NOT NULL,
    fecha_nacimiento DATE,
    edad TINYINT UNSIGNED,
    
    -- Datos institucionales
    instituto_id INT,
    regimen_id INT,
    semanas_imss INT,
    semanas_issste INT,
    
    -- Datos del trámite
    tramite_id INT,
    modalidad_id INT,
    
    -- Datos económicos
    pension_default DECIMAL(12,2),
    pension_normal DECIMAL(12,2),
    comision DECIMAL(12,2),
    honorarios DECIMAL(12,2),
    
    -- Fechas importantes
    fecha_alta DATE,
    fecha_baja DATE,
    
    -- Estatus
    estatus ENUM('Activo', 'Suspendido', 'Terminado', 'Baja') NOT NULL DEFAULT 'Activo',
    
    -- Referencia
    cliente_referidor_id INT,
    
    -- Auditoría
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    eliminado_en TIMESTAMP NULL,
    creado_por INT,
    actualizado_por INT,
    
    -- Índices
    INDEX idx_tipo_cliente (tipo_cliente),
    INDEX idx_estatus (estatus),
    INDEX idx_instituto (instituto_id),
    INDEX idx_no_cliente (no_cliente),
    
    -- Foreign keys
    FOREIGN KEY (instituto_id) REFERENCES catalogo_institutos(id),
    FOREIGN KEY (regimen_id) REFERENCES catalogo_regimenes(id),
    FOREIGN KEY (tramite_id) REFERENCES catalogo_tramites(id),
    FOREIGN KEY (modalidad_id) REFERENCES catalogo_modalidades(id),
    FOREIGN KEY (cliente_referidor_id) REFERENCES clientes(id)
);

-- 8. TABLA: clientes_curp (Múltiples CURP)
CREATE TABLE clientes_curp (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    curp VARCHAR(18) NOT NULL,
    es_principal BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_curp (curp),
    INDEX idx_cliente_principal (cliente_id, es_principal)
);

-- 9. TABLA: clientes_nss (Múltiples NSS)
CREATE TABLE clientes_nss (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    nss VARCHAR(11) NOT NULL,
    es_principal BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_nss (nss),
    INDEX idx_cliente_principal (cliente_id, es_principal)
);

-- 10. TABLA: clientes_rfc (Múltiples RFC)
CREATE TABLE clientes_rfc (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    rfc VARCHAR(13) NOT NULL,
    es_principal BOOLEAN DEFAULT TRUE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_rfc (rfc),
    INDEX idx_cliente_principal (cliente_id, es_principal)
);

-- 11. TABLA: clientes_contacto
CREATE TABLE clientes_contacto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    tipo ENUM('celular1', 'celular2', 'tel_casa', 'correo1', 'correo2', 'correo_personal') NOT NULL,
    valor VARCHAR(100) NOT NULL,
    es_principal BOOLEAN DEFAULT FALSE,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    INDEX idx_cliente_tipo (cliente_id, tipo),
    INDEX idx_principal (cliente_id, es_principal)
);

-- 12. TABLA: clientes_accesos_institucionales
CREATE TABLE clientes_accesos_institucionales (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    institucion ENUM('IMSS', 'ISSSTE', 'INFONAVIT') NOT NULL,
    cuenta VARCHAR(100),
    password VARCHAR(255),
    correo_asociado ENUM('correo1', 'correo2', 'correo_personal'),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    UNIQUE KEY unique_acceso (cliente_id, institucion)
);

-- 13. TABLA: familiares
CREATE TABLE familiares (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellido_paterno VARCHAR(100) NOT NULL,
    apellido_materno VARCHAR(100) NOT NULL,
    parentesco ENUM('conyuge', 'hijo', 'padre', 'madre', 'otro') NOT NULL,
    fecha_nacimiento DATE,
    curp VARCHAR(18),
    rfc VARCHAR(13),
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id)
);

-- 14. TABLA: clientes_notas
CREATE TABLE clientes_notas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    nota TEXT NOT NULL,
    tipo ENUM('general', 'seguimiento', 'importante', 'recordatorio') DEFAULT 'general',
    creado_por INT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    INDEX idx_cliente (cliente_id),
    INDEX idx_tipo (tipo)
);

-- 15. TABLA: clientes_documentos
CREATE TABLE clientes_documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT NOT NULL,
    documento_id INT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    fecha_subida DATE NOT NULL,
    valido BOOLEAN DEFAULT TRUE,
    observaciones TEXT,
    subido_por INT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (cliente_id) REFERENCES clientes(id) ON DELETE CASCADE,
    FOREIGN KEY (documento_id) REFERENCES catalogo_documentos(id),
    INDEX idx_cliente_documento (cliente_id, documento_id),
    UNIQUE KEY unique_documento (cliente_id, documento_id, ruta_archivo)
);

-- 16. TABLA: familiares_documentos
CREATE TABLE familiares_documentos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    familiar_id INT NOT NULL,
    documento_id INT NOT NULL,
    ruta_archivo VARCHAR(255) NOT NULL,
    nombre_archivo VARCHAR(255) NOT NULL,
    fecha_subida DATE NOT NULL,
    valido BOOLEAN DEFAULT TRUE,
    observaciones TEXT,
    subido_por INT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (familiar_id) REFERENCES familiares(id) ON DELETE CASCADE,
    FOREIGN KEY (documento_id) REFERENCES catalogo_documentos(id),
    INDEX idx_familiar_documento (familiar_id, documento_id)
);

-- 17. TABLA: usuarios
CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    rol ENUM('admin', 'supervisor', 'operador', 'consulta') DEFAULT 'operador',
    activo BOOLEAN DEFAULT TRUE,
    ultimo_login TIMESTAMP NULL,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    actualizado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 18. TABLA: permisos
CREATE TABLE permisos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) UNIQUE NOT NULL,
    descripcion VARCHAR(255),
    modulo VARCHAR(50) NOT NULL
);

-- 19. TABLA: usuario_permisos
CREATE TABLE usuario_permisos (
    usuario_id INT NOT NULL,
    permiso_id INT NOT NULL,
    concedido BOOLEAN DEFAULT TRUE,
    PRIMARY KEY (usuario_id, permiso_id),
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (permiso_id) REFERENCES permisos(id) ON DELETE CASCADE
);

-- 20. TABLA: bitacora
CREATE TABLE bitacora (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT,
    accion VARCHAR(100) NOT NULL,
    modulo VARCHAR(50) NOT NULL,
    detalles TEXT,
    ip_address VARCHAR(45),
    user_agent TEXT,
    creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    INDEX idx_usuario (usuario_id),
    INDEX idx_modulo (modulo),
    INDEX idx_fecha (creado_en)
);


-- 🔧 DATOS INICIALES PARA CATÁLOGOS
-- Institutos
INSERT INTO catalogo_institutos (codigo, nombre) VALUES
('IMSS', 'IMSS'),
('ISSSTE', 'ISSSTE');

-- Régimenes IMSS
INSERT INTO catalogo_regimenes (instituto_id, codigo, nombre) VALUES
(1, '73', 'Rég 73'),
(1, '97', 'Rég 97');

-- Régimenes ISSSTE
INSERT INTO catalogo_regimenes (instituto_id, codigo, nombre) VALUES
(2, '10MO', 'Décimo'),
(2, 'CI', 'Cuentas Ind');

-- Trámites
INSERT INTO catalogo_tramites (codigo, nombre) VALUES
('INV', 'Invalidez'),
('RT', 'Riesgo T'),
('CEAV', 'Cesantía'),
('VIU', 'Viudez'),
('ORF', 'Orfandad');

-- Modalidades
INSERT INTO catalogo_modalidades (codigo, nombre) VALUES
('M10', 'Modalidad 10'),
('M40', 'Modalidad 40');

-- Documentos para clientes
INSERT INTO catalogo_documentos (tipo, nombre, obligatorio, orden) VALUES
('cliente', 'INE', TRUE, 1),
('cliente', 'Domicilio', TRUE, 2),
('cliente', 'CURP', TRUE, 3),
('cliente', 'Banco', TRUE, 4),
('cliente', 'Afore', TRUE, 5),
('cliente', 'Nacimiento', TRUE, 6),
('cliente', 'Defuncion', FALSE, 7),
('cliente', 'NSS', TRUE, 8),
('cliente', 'RFC', TRUE, 9);

-- Documentos para beneficiarios
INSERT INTO catalogo_documentos (tipo, nombre, obligatorio, orden) VALUES
('beneficiario', 'INE', TRUE, 1),
('beneficiario', 'Domicilio', TRUE, 2),
('beneficiario', 'CURP', TRUE, 3),
('beneficiario', 'Banco', TRUE, 4),
('beneficiario', 'Nacimiento', TRUE, 5),
('beneficiario', 'Matrimonio', TRUE, 6),
('beneficiario', 'RFC', FALSE, 7);



--🎯 TRIGGER PARA GENERAR NoCliente
DELIMITER $$

CREATE TRIGGER before_clientes_insert
BEFORE INSERT ON clientes
FOR EACH ROW
BEGIN
    DECLARE prefijo VARCHAR(2);
    DECLARE siguiente_numero INT;
    
    -- Asignar prefijo según tipo de cliente
    CASE NEW.tipo_cliente
        WHEN 'C' THEN SET prefijo = 'C';
        WHEN 'P' THEN SET prefijo = 'P';
        WHEN 'S' THEN SET prefijo = 'S';
        WHEN 'B' THEN SET prefijo = 'B';
        WHEN 'I' THEN SET prefijo = 'I';
    END CASE;
    
    -- Obtener el siguiente número para este tipo
    SELECT COALESCE(MAX(CAST(SUBSTRING(no_cliente, 3) AS UNSIGNED)), 999) + 1
    INTO siguiente_numero
    FROM clientes
    WHERE tipo_cliente = NEW.tipo_cliente;
    
    -- Generar número de cliente (ej: C-10001)
    SET NEW.no_cliente = CONCAT(prefijo, '-', siguiente_numero);
END$$

DELIMITER ;
