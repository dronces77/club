CREATE DATABASE  IF NOT EXISTS `clubpension` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `clubpension`;
-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: clubpension
-- ------------------------------------------------------
-- Server version	8.0.44

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bitacora`
--

DROP TABLE IF EXISTS `bitacora`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bitacora` (
  `id` int NOT NULL AUTO_INCREMENT,
  `usuario_id` int DEFAULT NULL,
  `accion` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `detalles` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_usuario` (`usuario_id`),
  KEY `idx_modulo` (`modulo`),
  KEY `idx_fecha` (`creado_en`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora`
--

LOCK TABLES `bitacora` WRITE;
/*!40000 ALTER TABLE `bitacora` DISABLE KEYS */;
/*!40000 ALTER TABLE `bitacora` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_documentos`
--

DROP TABLE IF EXISTS `catalogo_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('cliente','beneficiario') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `obligatorio` tinyint(1) DEFAULT '0',
  `orden` int DEFAULT '0',
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_documento` (`tipo`,`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_documentos`
--

LOCK TABLES `catalogo_documentos` WRITE;
/*!40000 ALTER TABLE `catalogo_documentos` DISABLE KEYS */;
INSERT INTO `catalogo_documentos` VALUES (1,'cliente','INE',NULL,1,1,1),(2,'cliente','Domicilio',NULL,1,2,1),(3,'cliente','CURP',NULL,1,3,1),(4,'cliente','Banco',NULL,1,4,1),(5,'cliente','Afore',NULL,1,5,1),(6,'cliente','Nacimiento',NULL,1,6,1),(7,'cliente','Defuncion',NULL,0,7,1),(8,'cliente','NSS',NULL,1,8,1),(9,'cliente','RFC',NULL,1,9,1),(10,'beneficiario','INE',NULL,1,1,1),(11,'beneficiario','Domicilio',NULL,1,2,1),(12,'beneficiario','CURP',NULL,1,3,1),(13,'beneficiario','Banco',NULL,1,4,1),(14,'beneficiario','Nacimiento',NULL,1,5,1),(15,'beneficiario','Matrimonio',NULL,1,6,1),(16,'beneficiario','RFC',NULL,0,7,1);
/*!40000 ALTER TABLE `catalogo_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_institutos`
--

DROP TABLE IF EXISTS `catalogo_institutos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_institutos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_institutos`
--

LOCK TABLES `catalogo_institutos` WRITE;
/*!40000 ALTER TABLE `catalogo_institutos` DISABLE KEYS */;
INSERT INTO `catalogo_institutos` VALUES (1,'IMSS','IMSS',1),(2,'ISSSTE','ISSSTE',1);
/*!40000 ALTER TABLE `catalogo_institutos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_modalidades`
--

DROP TABLE IF EXISTS `catalogo_modalidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_modalidades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_modalidades`
--

LOCK TABLES `catalogo_modalidades` WRITE;
/*!40000 ALTER TABLE `catalogo_modalidades` DISABLE KEYS */;
INSERT INTO `catalogo_modalidades` VALUES (1,'M10','Modalidad 10',NULL,1),(2,'M40','Modalidad 40',NULL,1),(3,'NA','No Aplica',NULL,1),(4,'CV','Continuación Voluntaria',NULL,1);
/*!40000 ALTER TABLE `catalogo_modalidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_regimenes`
--

DROP TABLE IF EXISTS `catalogo_regimenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_regimenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `instituto_id` int NOT NULL,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_regimen` (`instituto_id`,`codigo`),
  CONSTRAINT `catalogo_regimenes_ibfk_1` FOREIGN KEY (`instituto_id`) REFERENCES `catalogo_institutos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_regimenes`
--

LOCK TABLES `catalogo_regimenes` WRITE;
/*!40000 ALTER TABLE `catalogo_regimenes` DISABLE KEYS */;
INSERT INTO `catalogo_regimenes` VALUES (1,1,'73','Rég 73'),(2,1,'97','Rég 97'),(3,2,'10MO','Décimo'),(4,2,'CI','Cuentas Ind');
/*!40000 ALTER TABLE `catalogo_regimenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tramites`
--

DROP TABLE IF EXISTS `catalogo_tramites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tramites` (
  `id` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tramites`
--

LOCK TABLES `catalogo_tramites` WRITE;
/*!40000 ALTER TABLE `catalogo_tramites` DISABLE KEYS */;
INSERT INTO `catalogo_tramites` VALUES (1,'INV','Invalidez',NULL,1),(2,'RT','Riesgo T',NULL,1),(3,'CEAV','Cesantía',NULL,1),(4,'VIU','Viudez',NULL,1),(5,'ORF','Orfandad',NULL,1);
/*!40000 ALTER TABLE `catalogo_tramites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `no_cliente` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cliente` enum('C','P','S','B','I') COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'C=Cliente, P=Prospecto, S=Suspendido, B=Baja, I=Imposible',
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` tinyint unsigned DEFAULT NULL,
  `instituto_id` int DEFAULT NULL,
  `instituto2_id` int DEFAULT NULL,
  `regimen_id` int DEFAULT NULL,
  `regimen2_id` int DEFAULT NULL,
  `semanas_imss` int DEFAULT NULL,
  `anios_servicio_issste` int DEFAULT NULL,
  `nss_issste` varchar(11) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tramite_id` int DEFAULT NULL,
  `tramite2_id` int DEFAULT NULL,
  `modalidad_id` int DEFAULT NULL,
  `modalidad_issste` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pension_default` decimal(12,2) DEFAULT NULL,
  `pension_normal` decimal(12,2) DEFAULT NULL,
  `comision` decimal(12,2) DEFAULT NULL,
  `honorarios` decimal(12,2) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `fecha_alta_issste` date DEFAULT NULL,
  `fecha_baja_issste` date DEFAULT NULL,
  `fecha_contrato` date DEFAULT NULL,
  `estatus` enum('Activo','Suspendido','Terminado','Baja') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `cliente_referidor_id` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado_en` timestamp NULL DEFAULT NULL,
  `creado_por` int DEFAULT NULL,
  `actualizado_por` int DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `no_cliente` (`no_cliente`),
  KEY `idx_tipo_cliente` (`tipo_cliente`),
  KEY `idx_estatus` (`estatus`),
  KEY `idx_instituto` (`instituto_id`),
  KEY `idx_no_cliente` (`no_cliente`),
  KEY `regimen_id` (`regimen_id`),
  KEY `tramite_id` (`tramite_id`),
  KEY `modalidad_id` (`modalidad_id`),
  KEY `cliente_referidor_id` (`cliente_referidor_id`),
  KEY `idx_regimen2` (`regimen2_id`),
  KEY `idx_tramite2` (`tramite2_id`),
  KEY `fk_clientes_instituto2` (`instituto2_id`),
  CONSTRAINT `clientes_ibfk_1` FOREIGN KEY (`instituto_id`) REFERENCES `catalogo_institutos` (`id`),
  CONSTRAINT `clientes_ibfk_2` FOREIGN KEY (`regimen_id`) REFERENCES `catalogo_regimenes` (`id`),
  CONSTRAINT `clientes_ibfk_3` FOREIGN KEY (`tramite_id`) REFERENCES `catalogo_tramites` (`id`),
  CONSTRAINT `clientes_ibfk_4` FOREIGN KEY (`modalidad_id`) REFERENCES `catalogo_modalidades` (`id`),
  CONSTRAINT `clientes_ibfk_5` FOREIGN KEY (`cliente_referidor_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `clientes_ibfk_6` FOREIGN KEY (`instituto2_id`) REFERENCES `catalogo_institutos` (`id`),
  CONSTRAINT `fk_clientes_instituto2` FOREIGN KEY (`instituto2_id`) REFERENCES `catalogo_institutos` (`id`),
  CONSTRAINT `fk_clientes_regimen2` FOREIGN KEY (`regimen2_id`) REFERENCES `catalogo_regimenes` (`id`),
  CONSTRAINT `fk_clientes_tramite2` FOREIGN KEY (`tramite2_id`) REFERENCES `catalogo_tramites` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'C-1000','C','Gloria','Fuentes','Vergara','1968-12-10',57,1,NULL,1,NULL,NULL,NULL,NULL,3,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL,'2026-01-31 10:53:53','2026-01-31 11:03:31','2026-01-31 11:03:31',1,NULL,NULL),(2,'C-1001','C','Gloria','Fuentes','Vergara','1968-12-10',57,1,NULL,1,NULL,NULL,NULL,NULL,3,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL,'2026-01-31 11:04:38','2026-01-31 15:46:38','2026-01-31 15:46:38',1,NULL,NULL),(3,'C-1002','C','Gloria','Fuentes','Vergara','1968-12-10',57,1,NULL,1,NULL,NULL,NULL,NULL,3,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL,'2026-01-31 15:47:13','2026-01-31 21:42:59','2026-01-31 21:42:59',1,1,NULL),(4,'C-1003','C','Gloria','Fuentes','Vergara','1968-12-10',57,1,NULL,1,NULL,NULL,NULL,NULL,3,NULL,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL,'2026-01-31 21:44:25','2026-01-31 21:44:25',NULL,1,NULL,NULL),(5,'C-1004','C','Martha','Morales','Alcala','1963-11-18',62,1,NULL,1,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Activo',NULL,'2026-01-31 22:47:44','2026-01-31 22:47:44',NULL,1,NULL,NULL),(6,'C-1005','C','Maria Luisa','Morales','Alcala','1964-11-10',61,1,2,1,4,1029,10,'8541254132',3,3,2,'NA',9600.00,40000.00,40000.00,8000.00,NULL,'2027-11-27',NULL,'2027-10-12','2024-12-04','Activo',5,'2026-01-31 22:49:01','2026-02-01 07:10:50',NULL,1,1,NULL),(7,'C-1006','C','Ruben','Benitez','Dueñas','1959-01-11',67,1,NULL,1,NULL,1025,NULL,NULL,3,NULL,3,NULL,9600.00,18000.00,18000.00,5000.00,NULL,'2026-01-30',NULL,NULL,'2025-07-15','Activo',NULL,'2026-02-01 02:21:01','2026-02-01 03:34:02',NULL,1,1,NULL),(8,'C-1007','C','Manuel','Carrillo','Leyva','1963-06-08',62,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'2025-07-04','Activo',NULL,'2026-02-01 03:49:15','2026-02-01 03:49:15',NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8mb4 */ ;
/*!50003 SET character_set_results = utf8mb4 */ ;
/*!50003 SET collation_connection  = utf8mb4_0900_ai_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = 'ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER `before_clientes_insert` BEFORE INSERT ON `clientes` FOR EACH ROW BEGIN
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
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `clientes_accesos_institucionales`
--

DROP TABLE IF EXISTS `clientes_accesos_institucionales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_accesos_institucionales` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `institucion` enum('IMSS','ISSSTE','INFONAVIT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `cuenta` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `correo_asociado` enum('correo1','correo2','correo_personal') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_acceso` (`cliente_id`,`institucion`),
  CONSTRAINT `clientes_accesos_institucionales_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_accesos_institucionales`
--

LOCK TABLES `clientes_accesos_institucionales` WRITE;
/*!40000 ALTER TABLE `clientes_accesos_institucionales` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes_accesos_institucionales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_contacto`
--

DROP TABLE IF EXISTS `clientes_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_contacto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `tipo` enum('celular1','celular2','tel_casa','correo1','correo2','correo_personal') COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) DEFAULT '0',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_cliente_tipo` (`cliente_id`,`tipo`),
  KEY `idx_principal` (`cliente_id`,`es_principal`),
  CONSTRAINT `clientes_contacto_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_contacto`
--

LOCK TABLES `clientes_contacto` WRITE;
/*!40000 ALTER TABLE `clientes_contacto` DISABLE KEYS */;
INSERT INTO `clientes_contacto` VALUES (1,7,'celular1','7671023486',1,'2026-02-01 03:34:02',NULL),(2,7,'correo1','rubenbenitezduenas@outlook.com',1,'2026-02-01 03:34:02',NULL),(3,6,'celular1','5548623843',1,'2026-02-01 03:42:37','2026-02-01 07:10:50'),(4,6,'correo1','marialuisamoralesalcala@outlook.com',1,'2026-02-01 03:42:37','2026-02-01 07:10:50'),(5,6,'celular1','5548623843',1,'2026-02-01 07:10:50',NULL),(6,6,'correo1','marialuisamoralesalcala@outlook.com',1,'2026-02-01 07:10:50',NULL);
/*!40000 ALTER TABLE `clientes_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_curp`
--

DROP TABLE IF EXISTS `clientes_curp`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_curp` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `curp` varchar(18) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_curp` (`curp`),
  KEY `idx_cliente_principal` (`cliente_id`,`es_principal`),
  CONSTRAINT `clientes_curp_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_curp`
--

LOCK TABLES `clientes_curp` WRITE;
/*!40000 ALTER TABLE `clientes_curp` DISABLE KEYS */;
INSERT INTO `clientes_curp` VALUES (1,7,'BEDR590111HGRNXB09',1,'2026-02-01 03:34:02',NULL),(2,6,'MOAL641010MDFRLS08',1,'2026-02-01 03:42:37',NULL);
/*!40000 ALTER TABLE `clientes_curp` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_documentos`
--

DROP TABLE IF EXISTS `clientes_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `documento_id` int NOT NULL,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_subida` date NOT NULL,
  `valido` tinyint(1) DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `subido_por` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_documento` (`cliente_id`,`documento_id`,`ruta_archivo`),
  KEY `documento_id` (`documento_id`),
  KEY `idx_cliente_documento` (`cliente_id`,`documento_id`),
  CONSTRAINT `clientes_documentos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `clientes_documentos_ibfk_2` FOREIGN KEY (`documento_id`) REFERENCES `catalogo_documentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_documentos`
--

LOCK TABLES `clientes_documentos` WRITE;
/*!40000 ALTER TABLE `clientes_documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_notas`
--

DROP TABLE IF EXISTS `clientes_notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_notas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `nota` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('general','seguimiento','importante','recordatorio') COLLATE utf8mb4_unicode_ci DEFAULT 'general',
  `creado_por` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cliente` (`cliente_id`),
  KEY `idx_tipo` (`tipo`),
  CONSTRAINT `clientes_notas_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_notas`
--

LOCK TABLES `clientes_notas` WRITE;
/*!40000 ALTER TABLE `clientes_notas` DISABLE KEYS */;
/*!40000 ALTER TABLE `clientes_notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_nss`
--

DROP TABLE IF EXISTS `clientes_nss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_nss` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `nss` varchar(11) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_nss` (`nss`),
  KEY `idx_cliente_principal` (`cliente_id`,`es_principal`),
  CONSTRAINT `clientes_nss_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_nss`
--

LOCK TABLES `clientes_nss` WRITE;
/*!40000 ALTER TABLE `clientes_nss` DISABLE KEYS */;
INSERT INTO `clientes_nss` VALUES (1,7,'01765905623',1,'2026-02-01 03:34:02',NULL),(2,6,'64846518369',1,'2026-02-01 03:42:37',NULL);
/*!40000 ALTER TABLE `clientes_nss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes_rfc`
--

DROP TABLE IF EXISTS `clientes_rfc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes_rfc` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `rfc` varchar(13) COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) DEFAULT '1',
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_rfc` (`rfc`),
  KEY `idx_cliente_principal` (`cliente_id`,`es_principal`),
  CONSTRAINT `clientes_rfc_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes_rfc`
--

LOCK TABLES `clientes_rfc` WRITE;
/*!40000 ALTER TABLE `clientes_rfc` DISABLE KEYS */;
INSERT INTO `clientes_rfc` VALUES (1,7,'BEDR590111CX9',1,'2026-02-01 03:34:02',NULL),(2,6,'MOAL6410109R2',1,'2026-02-01 03:42:37',NULL);
/*!40000 ALTER TABLE `clientes_rfc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familiares`
--

DROP TABLE IF EXISTS `familiares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familiares` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentesco` enum('conyuge','hijo','padre','madre','otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `curp` varchar(18) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rfc` varchar(13) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_cliente` (`cliente_id`),
  CONSTRAINT `familiares_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familiares`
--

LOCK TABLES `familiares` WRITE;
/*!40000 ALTER TABLE `familiares` DISABLE KEYS */;
/*!40000 ALTER TABLE `familiares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `familiares_documentos`
--

DROP TABLE IF EXISTS `familiares_documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `familiares_documentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `familiar_id` int NOT NULL,
  `documento_id` int NOT NULL,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_archivo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_subida` date NOT NULL,
  `valido` tinyint(1) DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `subido_por` int DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `documento_id` (`documento_id`),
  KEY `idx_familiar_documento` (`familiar_id`,`documento_id`),
  CONSTRAINT `familiares_documentos_ibfk_1` FOREIGN KEY (`familiar_id`) REFERENCES `familiares` (`id`) ON DELETE CASCADE,
  CONSTRAINT `familiares_documentos_ibfk_2` FOREIGN KEY (`documento_id`) REFERENCES `catalogo_documentos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `familiares_documentos`
--

LOCK TABLES `familiares_documentos` WRITE;
/*!40000 ALTER TABLE `familiares_documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `familiares_documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2026_01_31_025531_create_permissions_table',2),(3,'2026_01_31_025536_create_roles_table',2),(4,'2026_01_31_025541_create_model_has_permissions_table',2),(5,'2026_01_31_025557_create_model_has_roles_table',2),(6,'2026_01_31_025602_create_role_has_permissions_table',2),(7,'2026_01_31_131600_add_fecha_contrato_to_clientes_table',3),(8,'2026_01_31_194500_add_issste_fields_to_clientes_table',4),(9,'2026_01_31_200858_add_soft_deletes_to_clientes_table',5),(10,'2026_01_31_201146_add_soft_deletes_to_related_tables',6),(11,'2026_01_31_201907_add_deleted_at_to_clientes_table',7);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\Usuario',1);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permisos`
--

DROP TABLE IF EXISTS `permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permisos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `modulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre` (`nombre`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permisos`
--

LOCK TABLES `permisos` WRITE;
/*!40000 ALTER TABLE `permisos` DISABLE KEYS */;
/*!40000 ALTER TABLE `permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
INSERT INTO `permissions` VALUES (1,'ver dashboard','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(2,'ver clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(3,'crear clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(4,'editar clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(5,'eliminar clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(6,'exportar clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(7,'importar clientes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(8,'ver documentos','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(9,'subir documentos','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(10,'descargar documentos','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(11,'eliminar documentos','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(12,'ver reportes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(13,'generar reportes','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(14,'ver usuarios','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(15,'crear usuarios','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(16,'editar usuarios','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(17,'eliminar usuarios','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(18,'asignar roles','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(19,'ver catalogos','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(20,'editar catalogos','web','2026-01-31 08:59:06','2026-01-31 08:59:06');
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
INSERT INTO `role_has_permissions` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(6,1),(7,1),(8,1),(9,1),(10,1),(11,1),(12,1),(13,1),(14,1),(15,1),(16,1),(17,1),(18,1),(19,1),(20,1),(1,2),(2,2),(3,2),(4,2),(8,2),(9,2),(12,2),(14,2),(1,3),(2,3),(3,3),(4,3),(8,3),(9,3);
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(2,'supervisor','web','2026-01-31 08:59:06','2026-01-31 08:59:06'),(3,'operador','web','2026-01-31 08:59:06','2026-01-31 08:59:06');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario_permisos`
--

DROP TABLE IF EXISTS `usuario_permisos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuario_permisos` (
  `usuario_id` int NOT NULL,
  `permiso_id` int NOT NULL,
  `concedido` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`usuario_id`,`permiso_id`),
  KEY `permiso_id` (`permiso_id`),
  CONSTRAINT `usuario_permisos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  CONSTRAINT `usuario_permisos_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permisos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario_permisos`
--

LOCK TABLES `usuario_permisos` WRITE;
/*!40000 ALTER TABLE `usuario_permisos` DISABLE KEYS */;
/*!40000 ALTER TABLE `usuario_permisos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellidos` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` enum('admin','supervisor','operador','consulta') COLLATE utf8mb4_unicode_ci DEFAULT 'operador',
  `activo` tinyint(1) DEFAULT '1',
  `ultimo_login` timestamp NULL DEFAULT NULL,
  `creado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado_en` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','dronces@hotmail.com','$2y$12$rTDh8XpbQvU9nvi72dnA0eWcM3X4C3pr23WQEr2QBPnRw4Y4sk1e2','Administrador','Sistema','admin',1,'2026-01-31 21:18:09','2026-01-31 02:52:19','2026-01-31 21:18:09');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'clubpension'
--

--
-- Dumping routines for database 'clubpension'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-01  2:26:31
