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
-- Table structure for table `archivos`
--

DROP TABLE IF EXISTS `archivos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `archivos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `archivos_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `archivos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `archivos`
--

LOCK TABLES `archivos` WRITE;
/*!40000 ALTER TABLE `archivos` DISABLE KEYS */;
/*!40000 ALTER TABLE `archivos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_afores`
--

DROP TABLE IF EXISTS `catalogo_afores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_afores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_afores_codigo_unique` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_afores`
--

LOCK TABLES `catalogo_afores` WRITE;
/*!40000 ALTER TABLE `catalogo_afores` DISABLE KEYS */;
INSERT INTO `catalogo_afores` VALUES (1,'AAZT','Azteca','Afore Azteca',1,1,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(2,'ABAX','Banamex','Citibanamex Afore',1,2,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(3,'ABAN','Banorte','Afore XXI-Banorte',1,3,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(4,'ACOP','Coppel','Afore Coppel',1,4,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(5,'AINB','Inbursa','Afore Inbursa',1,5,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(6,'AINV','Invercap','Afore Invercap',1,6,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(7,'AISS','Issste','Afore PensionISSSTE',1,7,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(8,'APRI','Principal','Afore Principal',1,8,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(9,'APRO','Profuturo','Afore Profuturo',1,9,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL),(10,'ASUR','Sura','Afore Sura',1,10,'2026-02-15 00:42:04','2026-02-15 00:42:04',NULL);
/*!40000 ALTER TABLE `catalogo_afores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_estados`
--

DROP TABLE IF EXISTS `catalogo_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_estados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_estados`
--

LOCK TABLES `catalogo_estados` WRITE;
/*!40000 ALTER TABLE `catalogo_estados` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogo_estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_estatus_clientes`
--

DROP TABLE IF EXISTS `catalogo_estatus_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_estatus_clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_estatus_cliente_codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_estatus_clientes`
--

LOCK TABLES `catalogo_estatus_clientes` WRITE;
/*!40000 ALTER TABLE `catalogo_estatus_clientes` DISABLE KEYS */;
INSERT INTO `catalogo_estatus_clientes` VALUES (1,'ESAC','Activo','Activo',1,1,NULL,NULL,NULL),(2,'ESBA','Baja','Baja',1,2,NULL,NULL,NULL),(3,'ESSU','Suspendido','Suspendido',1,3,NULL,NULL,NULL),(4,'ESTE','Terminado','Terminado',1,4,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_estatus_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_estatus_prospectos`
--

DROP TABLE IF EXISTS `catalogo_estatus_prospectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_estatus_prospectos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`),
  UNIQUE KEY `codigo_2` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_estatus_prospectos`
--

LOCK TABLES `catalogo_estatus_prospectos` WRITE;
/*!40000 ALTER TABLE `catalogo_estatus_prospectos` DISABLE KEYS */;
INSERT INTO `catalogo_estatus_prospectos` VALUES (1,'ESVO','Nuevo','Nuevo',1,1,NULL,NULL,NULL),(2,'ESCT','Contactado','Contactado',1,2,NULL,NULL,NULL),(3,'ESIN','Interesado','Interesado',1,3,NULL,NULL,NULL),(4,'ESBA','Baja','Baja',1,4,NULL,NULL,NULL),(5,'ESIM','Imposible','Imposible',1,5,NULL,NULL,NULL),(6,'ESCV','Convertido','Convertido',1,6,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_estatus_prospectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_generos`
--

DROP TABLE IF EXISTS `catalogo_generos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_generos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_generos`
--

LOCK TABLES `catalogo_generos` WRITE;
/*!40000 ALTER TABLE `catalogo_generos` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogo_generos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_institutos`
--

DROP TABLE IF EXISTS `catalogo_institutos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_institutos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_institutos_codigo_unique` (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_institutos`
--

LOCK TABLES `catalogo_institutos` WRITE;
/*!40000 ALTER TABLE `catalogo_institutos` DISABLE KEYS */;
INSERT INTO `catalogo_institutos` VALUES (1,'IMS','IMSS','IMSS-des',1,1,NULL,NULL,NULL),(2,'IST','ISSSTE','ISSSTE-des',1,2,NULL,NULL,NULL),(3,'INA','NA','No Aplica',1,3,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_institutos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_modalidad_regimen_tramite`
--

DROP TABLE IF EXISTS `catalogo_modalidad_regimen_tramite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_modalidad_regimen_tramite` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `instituto_codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `modalidad_codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `regimen_codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tramite_codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_regla` (`instituto_codigo`,`regimen_codigo`,`tramite_codigo`,`modalidad_codigo`),
  UNIQUE KEY `uq_combo_unico` (`instituto_codigo`,`regimen_codigo`,`tramite_codigo`,`modalidad_codigo`),
  KEY `fk_cmrt_regimen` (`regimen_codigo`),
  KEY `fk_cmrt_tramite` (`tramite_codigo`),
  KEY `fk_cmrt_modalidad` (`modalidad_codigo`),
  CONSTRAINT `fk_cmrt_instituto` FOREIGN KEY (`instituto_codigo`) REFERENCES `catalogo_institutos` (`codigo`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cmrt_modalidad` FOREIGN KEY (`modalidad_codigo`) REFERENCES `catalogo_modalidades` (`codigo`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cmrt_regimen` FOREIGN KEY (`regimen_codigo`) REFERENCES `catalogo_regimenes` (`codigo`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cmrt_tramite` FOREIGN KEY (`tramite_codigo`) REFERENCES `catalogo_tramites` (`codigo`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_modalidad_regimen_tramite`
--

LOCK TABLES `catalogo_modalidad_regimen_tramite` WRITE;
/*!40000 ALTER TABLE `catalogo_modalidad_regimen_tramite` DISABLE KEYS */;
INSERT INTO `catalogo_modalidad_regimen_tramite` VALUES (1,'IMS','M10','R73','PIMCV',1,1,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(2,'IMS','M40','R73','PIMCV',1,2,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(3,'IMS','MNA','R73','PIMCV',1,3,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(4,'IMS','M10','R73','PIMVI',1,4,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(5,'IMS','M40','R73','PIMVI',1,5,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(6,'IMS','MNA','R73','PIMVI',1,6,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(7,'IMS','M10','R73','PIMRT',1,7,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(8,'IMS','M40','R73','PIMRT',1,8,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(9,'IMS','MNA','R73','PIMRT',1,9,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(10,'IMS','M10','R73','PIMIN',1,10,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(11,'IMS','M40','R73','PIMIN',1,11,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(12,'IMS','MNA','R73','PIMIN',1,12,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(13,'IMS','M10','R73','PIMOR',1,13,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(14,'IMS','M40','R73','PIMOR',1,14,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(15,'IMS','MNA','R73','PIMOR',1,15,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(16,'IMS','M10','R73','PIMAS',1,16,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(17,'IMS','M40','R73','PIMAS',1,17,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(18,'IMS','MNA','R73','PIMAS',1,18,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(19,'IMS','MNA','R73','AFO',1,19,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(20,'IMS','M10','R97','PIMCV',1,20,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(21,'IMS','M40','R97','PIMCV',1,21,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(22,'IMS','MNA','R97','PIMCV',1,22,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(23,'IMS','M10','R97','PIMVI',1,23,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(24,'IMS','M40','R97','PIMVI',1,24,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(25,'IMS','MNA','R97','PIMVI',1,25,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(26,'IMS','M10','R97','PIMRT',1,26,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(27,'IMS','M40','R97','PIMRT',1,27,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(28,'IMS','MNA','R97','PIMRT',1,28,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(29,'IMS','M10','R97','PIMIN',1,29,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(30,'IMS','M40','R97','PIMIN',1,30,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(31,'IMS','MNA','R97','PIMIN',1,31,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(32,'IMS','M10','R97','PIMOR',1,32,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(33,'IMS','M40','R97','PIMOR',1,33,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(34,'IMS','MNA','R97','PIMOR',1,34,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(35,'IMS','M10','R97','PIMAS',1,35,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(36,'IMS','M40','R97','PIMAS',1,36,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(37,'IMS','MNA','R97','PIMAS',1,37,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(38,'IMS','MNA','R97','AFO',1,38,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(39,'IST','MCV','DT','PITJU',1,39,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(40,'IST','MNA','DT','PITJU',1,40,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(41,'IST','MCV','DT','PITETS',1,41,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(42,'IST','MNA','DT','PITETS',1,42,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(43,'IST','MCV','DT','PITCEA',1,43,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(44,'IST','MNA','DT','PITCEA',1,44,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(45,'IST','MNA','DT','AFO',1,45,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(46,'IST','MNA','CI','PITRV',1,46,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(47,'IST','MNA','CI','PITRP',1,47,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(48,'IST','MNA','CI','PITPG',1,48,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL),(49,'IST','MNA','CI','AFO',1,49,'2026-02-13 06:50:59','2026-02-13 06:50:59',NULL);
/*!40000 ALTER TABLE `catalogo_modalidad_regimen_tramite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_modalidades`
--

DROP TABLE IF EXISTS `catalogo_modalidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_modalidades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_modalidades_codigo_unique` (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_modalidades`
--

LOCK TABLES `catalogo_modalidades` WRITE;
/*!40000 ALTER TABLE `catalogo_modalidades` DISABLE KEYS */;
INSERT INTO `catalogo_modalidades` VALUES (1,'M10','Modalidad 10','Pago de Modalidad 10',1,1,NULL,NULL,NULL),(2,'M40','Modalidad 40','Pago de Modalidad 40',1,2,NULL,NULL,NULL),(3,'MCV','Continuación Voluntaria','Pago de Continuacion Voluntaria',1,3,NULL,NULL,NULL),(4,'MNA','No Aplica','No Aplica',1,4,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_modalidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_regimenes`
--

DROP TABLE IF EXISTS `catalogo_regimenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_regimenes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_regimenes_codigo_unique` (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_regimenes`
--

LOCK TABLES `catalogo_regimenes` WRITE;
/*!40000 ALTER TABLE `catalogo_regimenes` DISABLE KEYS */;
INSERT INTO `catalogo_regimenes` VALUES (1,'R73','Regimen 73','Regimen 73',1,1,NULL,NULL,NULL),(2,'R97','Regimen 97','Regimen 97',1,2,NULL,NULL,NULL),(3,'DT','Decimo Transitorio','Decimo Transitorio',1,3,NULL,NULL,NULL),(4,'CI','Cuentas Individuales','Cuentas Individuales',1,4,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_regimenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tipos_cliente`
--

DROP TABLE IF EXISTS `catalogo_tipos_cliente`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tipos_cliente` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tipos_cliente`
--

LOCK TABLES `catalogo_tipos_cliente` WRITE;
/*!40000 ALTER TABLE `catalogo_tipos_cliente` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogo_tipos_cliente` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tipos_contacto`
--

DROP TABLE IF EXISTS `catalogo_tipos_contacto`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tipos_contacto` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tipos_contacto`
--

LOCK TABLES `catalogo_tipos_contacto` WRITE;
/*!40000 ALTER TABLE `catalogo_tipos_contacto` DISABLE KEYS */;
INSERT INTO `catalogo_tipos_contacto` VALUES (1,'CEL','Celular','Celular',1,1,'2026-02-12 04:40:51','2026-02-12 04:40:51',NULL),(2,'TEL','Telefono','Telefono',1,2,'2026-02-12 04:40:51','2026-02-12 04:40:51',NULL),(3,'COR','Correo','Correo',1,3,'2026-02-12 04:40:51','2026-02-12 04:40:51',NULL);
/*!40000 ALTER TABLE `catalogo_tipos_contacto` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tramites`
--

DROP TABLE IF EXISTS `catalogo_tramites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tramites` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_tramites_codigo_unique` (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tramites`
--

LOCK TABLES `catalogo_tramites` WRITE;
/*!40000 ALTER TABLE `catalogo_tramites` DISABLE KEYS */;
INSERT INTO `catalogo_tramites` VALUES (1,'AFO','Afore','Tramite de Afore',1,1,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(2,'PIMAS','Pension Ascendencia','Tramite de Pension Ascendencia',1,2,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(3,'PIMCV','Pension CEAV','Tramite de Pension CEAV',1,3,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(4,'PIMIN','Pension Invalidez','Tramite de Pension Invalidez',1,4,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(5,'PIMOR','Pension Orfandad','Tramite de Pension Orfandad',1,5,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(6,'PIMRT','Pension Riesgo Trabajo','Tramite de Pension Riesgo Trabajo',1,6,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL),(7,'PIMVI','Pension Viudez','Tramite de Pension Viudez',1,7,'2026-02-13 17:33:37','2026-02-13 17:33:37',NULL);
/*!40000 ALTER TABLE `catalogo_tramites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogo_tramites_issste`
--

DROP TABLE IF EXISTS `catalogo_tramites_issste`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogo_tramites_issste` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalogo_tramites_codigo_unique` (`codigo`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogo_tramites_issste`
--

LOCK TABLES `catalogo_tramites_issste` WRITE;
/*!40000 ALTER TABLE `catalogo_tramites_issste` DISABLE KEYS */;
INSERT INTO `catalogo_tramites_issste` VALUES (1,'AFO','Afore','Tramite de Afore',1,1,NULL,NULL,NULL),(2,'PITCEA','Cesantia Edad Avanzada','Tramite de Pension Cesantia E. A.',1,2,NULL,NULL,NULL),(3,'PITETS','Edad Tiempo Servicio','Tramite de Edad y Tiempo de Servicio',1,3,NULL,NULL,NULL),(4,'PITJU','Jubilacion','Tramite de Jubilacion',1,4,NULL,NULL,NULL),(5,'PITPG','Pension Garantizada','Tramite de Pension Garantizada',1,5,NULL,NULL,NULL),(6,'PITRP','Retiro Programado','Tramite de Pension Retiro Programado',1,6,NULL,NULL,NULL),(7,'PITRV','Renta Vitalicia','Tramite de Pension Renta Vitalicia',1,7,NULL,NULL,NULL);
/*!40000 ALTER TABLE `catalogo_tramites_issste` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogos_tipos`
--

DROP TABLE IF EXISTS `catalogos_tipos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `catalogos_tipos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `activo` tinyint(1) DEFAULT '1',
  `orden` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `codigo` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogos_tipos`
--

LOCK TABLES `catalogos_tipos` WRITE;
/*!40000 ALTER TABLE `catalogos_tipos` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogos_tipos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_beneficiarios`
--

DROP TABLE IF EXISTS `cliente_beneficiarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_beneficiarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `parentesco` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `porcentaje` decimal(5,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_beneficiarios_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_beneficiarios_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_beneficiarios`
--

LOCK TABLES `cliente_beneficiarios` WRITE;
/*!40000 ALTER TABLE `cliente_beneficiarios` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_beneficiarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_contactos`
--

DROP TABLE IF EXISTS `cliente_contactos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_contactos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `tipo_contacto_id` bigint unsigned NOT NULL,
  `valor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_contactos_cliente_id_foreign` (`cliente_id`),
  KEY `fk_cliente_contacto_tipo` (`tipo_contacto_id`),
  CONSTRAINT `cliente_contactos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cliente_contacto_tipo` FOREIGN KEY (`tipo_contacto_id`) REFERENCES `catalogo_tipos_contacto` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=142 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_contactos`
--

LOCK TABLES `cliente_contactos` WRITE;
/*!40000 ALTER TABLE `cliente_contactos` DISABLE KEYS */;
INSERT INTO `cliente_contactos` VALUES (132,9,1,'5644944042',1,'2026-02-14 18:14:31','2026-02-14 18:14:31'),(133,5,1,'5610492232',1,'2026-02-14 18:24:50','2026-02-14 18:24:50'),(136,8,1,'7772143452',1,'2026-02-14 19:10:59','2026-02-14 19:10:59'),(139,10,1,'5644944042',1,'2026-02-14 20:09:10','2026-02-14 20:09:10'),(140,11,1,'7771161055',1,'2026-02-14 20:39:58','2026-02-14 20:39:58'),(141,4,1,'0019565263978',1,'2026-02-14 22:59:27','2026-02-14 22:59:27');
/*!40000 ALTER TABLE `cliente_contactos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_curps`
--

DROP TABLE IF EXISTS `cliente_curps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_curps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `curp` varchar(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_curps_curp_unique` (`curp`),
  KEY `cliente_curps_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_curps_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=117 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_curps`
--

LOCK TABLES `cliente_curps` WRITE;
/*!40000 ALTER TABLE `cliente_curps` DISABLE KEYS */;
INSERT INTO `cliente_curps` VALUES (107,9,'GGGG681210MGRNRL03',1,'2026-02-14 18:14:31','2026-02-14 18:14:31'),(108,5,'CALR551206HDFLRL08',1,'2026-02-14 18:24:50','2026-02-14 18:24:50'),(111,8,'FUVG681210MGRN1111',1,'2026-02-14 19:10:59','2026-02-14 19:10:59'),(114,10,'HHHH680608HDFNPX07',1,'2026-02-14 20:09:10','2026-02-14 20:09:10'),(115,11,'RORD681210MGRNRL03',1,'2026-02-14 20:39:58','2026-02-14 20:39:58'),(116,4,'OABR690425HMSCSN05',1,'2026-02-14 22:59:27','2026-02-14 22:59:27');
/*!40000 ALTER TABLE `cliente_curps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_dependientes`
--

DROP TABLE IF EXISTS `cliente_dependientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_dependientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `edad` int DEFAULT NULL,
  `parentesco` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_dependientes_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_dependientes_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_dependientes`
--

LOCK TABLES `cliente_dependientes` WRITE;
/*!40000 ALTER TABLE `cliente_dependientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_dependientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_domicilios`
--

DROP TABLE IF EXISTS `cliente_domicilios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_domicilios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `calle` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `numero` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `colonia` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `municipio_id` bigint unsigned DEFAULT NULL,
  `estado_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_domicilios_municipio_id_foreign` (`municipio_id`),
  KEY `cliente_domicilios_estado_id_foreign` (`estado_id`),
  KEY `cliente_domicilios_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_domicilios_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `cliente_domicilios_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `cliente_estados` (`id`),
  CONSTRAINT `cliente_domicilios_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `cliente_municipios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_domicilios`
--

LOCK TABLES `cliente_domicilios` WRITE;
/*!40000 ALTER TABLE `cliente_domicilios` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_domicilios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_estados`
--

DROP TABLE IF EXISTS `cliente_estados`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_estados` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_estados_codigo_unique` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_estados`
--

LOCK TABLES `cliente_estados` WRITE;
/*!40000 ALTER TABLE `cliente_estados` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_estados` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_estudios`
--

DROP TABLE IF EXISTS `cliente_estudios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_estudios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `nivel` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `institucion` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_estudios_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_estudios_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_estudios`
--

LOCK TABLES `cliente_estudios` WRITE;
/*!40000 ALTER TABLE `cliente_estudios` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_estudios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_localidades`
--

DROP TABLE IF EXISTS `cliente_localidades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_localidades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `municipio_id` bigint unsigned NOT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_localidades_codigo_unique` (`codigo`),
  KEY `cliente_localidades_municipio_id_foreign` (`municipio_id`),
  CONSTRAINT `cliente_localidades_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `cliente_municipios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_localidades`
--

LOCK TABLES `cliente_localidades` WRITE;
/*!40000 ALTER TABLE `cliente_localidades` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_localidades` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_municipios`
--

DROP TABLE IF EXISTS `cliente_municipios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_municipios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `estado_id` bigint unsigned NOT NULL,
  `codigo` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_municipios_codigo_unique` (`codigo`),
  KEY `cliente_municipios_estado_id_foreign` (`estado_id`),
  CONSTRAINT `cliente_municipios_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `cliente_estados` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_municipios`
--

LOCK TABLES `cliente_municipios` WRITE;
/*!40000 ALTER TABLE `cliente_municipios` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_municipios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_nsss`
--

DROP TABLE IF EXISTS `cliente_nsss`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_nsss` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `nss` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_nsss_nss_unique` (`nss`),
  KEY `cliente_nsss_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_nsss_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_nsss`
--

LOCK TABLES `cliente_nsss` WRITE;
/*!40000 ALTER TABLE `cliente_nsss` DISABLE KEYS */;
INSERT INTO `cliente_nsss` VALUES (87,9,'55556806258',1,'2026-02-14 18:14:31','2026-02-14 18:14:31'),(88,5,'43745541375',1,'2026-02-14 18:24:50','2026-02-14 18:24:50'),(91,8,'55556320276',1,'2026-02-14 19:10:59','2026-02-14 19:10:59'),(94,10,'44446806258',1,'2026-02-14 20:09:10','2026-02-14 20:09:10'),(95,11,'15967709491',1,'2026-02-14 20:39:58','2026-02-14 20:39:58'),(96,4,'15876927516',1,'2026-02-14 22:59:27','2026-02-14 22:59:27');
/*!40000 ALTER TABLE `cliente_nsss` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_ocupaciones`
--

DROP TABLE IF EXISTS `cliente_ocupaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_ocupaciones` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `puesto` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `empresa` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cliente_ocupaciones_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_ocupaciones_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_ocupaciones`
--

LOCK TABLES `cliente_ocupaciones` WRITE;
/*!40000 ALTER TABLE `cliente_ocupaciones` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_ocupaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cliente_rfcs`
--

DROP TABLE IF EXISTS `cliente_rfcs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cliente_rfcs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `rfc` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `es_principal` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cliente_rfcs_rfc_unique` (`rfc`),
  KEY `cliente_rfcs_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `cliente_rfcs_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cliente_rfcs`
--

LOCK TABLES `cliente_rfcs` WRITE;
/*!40000 ALTER TABLE `cliente_rfcs` DISABLE KEYS */;
/*!40000 ALTER TABLE `cliente_rfcs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `no_cliente` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cliente` varchar(1) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'P',
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `instituto_id` bigint unsigned DEFAULT NULL,
  `instituto2_id` bigint unsigned DEFAULT NULL,
  `regimen_id` bigint unsigned DEFAULT NULL,
  `regimen2_id` bigint unsigned DEFAULT NULL,
  `semanas_imss` int DEFAULT NULL,
  `anios_servicio_issste` int DEFAULT NULL,
  `nss_issste` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tramite_id` bigint unsigned DEFAULT NULL,
  `tramite2_id` bigint unsigned DEFAULT NULL,
  `modalidad_id` bigint unsigned DEFAULT NULL,
  `pension_default` decimal(12,2) DEFAULT NULL,
  `pension_normal` decimal(12,2) DEFAULT NULL,
  `comision` decimal(12,2) DEFAULT NULL,
  `honorarios` decimal(12,2) DEFAULT NULL,
  `fecha_alta` date DEFAULT NULL,
  `fecha_baja` date DEFAULT NULL,
  `fecha_alta_issste` date DEFAULT NULL,
  `fecha_baja_issste` date DEFAULT NULL,
  `fecha_contrato` date DEFAULT NULL,
  `cliente_referidor_id` bigint unsigned DEFAULT NULL,
  `creado_por` bigint unsigned DEFAULT NULL,
  `actualizado_por` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `prospecto_id` bigint unsigned DEFAULT NULL,
  `estatus_cliente_id` bigint unsigned NOT NULL,
  `modalidad2_id` bigint unsigned DEFAULT NULL,
  `afore_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clientes_no_cliente_unique` (`no_cliente`),
  KEY `clientes_instituto_id_foreign` (`instituto_id`),
  KEY `clientes_instituto2_id_foreign` (`instituto2_id`),
  KEY `clientes_regimen_id_foreign` (`regimen_id`),
  KEY `clientes_regimen2_id_foreign` (`regimen2_id`),
  KEY `clientes_tramite_id_foreign` (`tramite_id`),
  KEY `clientes_tramite2_id_foreign` (`tramite2_id`),
  KEY `clientes_modalidad_id_foreign` (`modalidad_id`),
  KEY `clientes_creado_por_foreign` (`creado_por`),
  KEY `clientes_actualizado_por_foreign` (`actualizado_por`),
  KEY `clientes_cliente_referidor_id_foreign` (`cliente_referidor_id`),
  KEY `idx_clientes_tipo_cliente` (`tipo_cliente`),
  KEY `fk_cliente_estatus` (`estatus_cliente_id`),
  KEY `idx_clientes_busqueda` (`nombre`,`apellido_paterno`,`estatus_cliente_id`),
  KEY `fk_clientes_modalidad2` (`modalidad2_id`),
  KEY `fk_clientes_prospecto` (`prospecto_id`),
  KEY `idx_afore_id` (`afore_id`),
  CONSTRAINT `clientes_actualizado_por_foreign` FOREIGN KEY (`actualizado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clientes_afore_id_foreign` FOREIGN KEY (`afore_id`) REFERENCES `catalogo_afores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clientes_cliente_referidor_id_foreign` FOREIGN KEY (`cliente_referidor_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clientes_creado_por_foreign` FOREIGN KEY (`creado_por`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `clientes_instituto2_id_foreign` FOREIGN KEY (`instituto2_id`) REFERENCES `catalogo_institutos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_instituto_id_foreign` FOREIGN KEY (`instituto_id`) REFERENCES `catalogo_institutos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_modalidad_id_foreign` FOREIGN KEY (`modalidad_id`) REFERENCES `catalogo_modalidades` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_regimen2_id_foreign` FOREIGN KEY (`regimen2_id`) REFERENCES `catalogo_regimenes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_regimen_id_foreign` FOREIGN KEY (`regimen_id`) REFERENCES `catalogo_regimenes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_tramite2_id_foreign` FOREIGN KEY (`tramite2_id`) REFERENCES `catalogo_tramites` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `clientes_tramite_id_foreign` FOREIGN KEY (`tramite_id`) REFERENCES `catalogo_tramites` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cliente_estatus` FOREIGN KEY (`estatus_cliente_id`) REFERENCES `catalogo_estatus_clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_clientes_modalidad2` FOREIGN KEY (`modalidad2_id`) REFERENCES `catalogo_modalidades` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_clientes_prospecto` FOREIGN KEY (`prospecto_id`) REFERENCES `prospectos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (4,'CP-2','C','rene','lara','bustos','1954-12-10',71,1,3,1,NULL,500,NULL,NULL,3,NULL,2,5000.00,23000.00,23000.00,5000.00,NULL,NULL,NULL,NULL,'2025-12-10',NULL,1,1,'2026-02-14 06:26:04','2026-02-14 22:59:27',NULL,NULL,4,NULL,3),(5,'CP-3','C','raul','callejo','lara','1959-10-10',66,3,2,NULL,3,NULL,15,'12345698997',NULL,2,NULL,5000.00,52222.00,52222.00,6000.00,NULL,NULL,NULL,NULL,'2020-10-10',NULL,1,1,'2026-02-14 06:43:57','2026-02-14 18:24:50',NULL,NULL,3,3,NULL),(8,'CP-4','C','Gloria','Fuentes','Vergara','1964-10-10',61,1,2,2,3,345,40,'12345777887',1,4,1,2.00,2.00,2.00,2.00,NULL,NULL,NULL,NULL,'2025-10-10',NULL,1,1,'2026-02-14 16:06:59','2026-02-14 19:10:59',NULL,NULL,2,4,NULL),(9,'CP-5','C','Martha','Morales','bustos','1954-12-10',71,3,2,NULL,4,NULL,34,'12311118887',NULL,6,NULL,5.00,5.00,5.00,5.00,NULL,NULL,NULL,NULL,'2026-01-10',NULL,1,1,'2026-02-14 16:26:03','2026-02-14 18:14:31',NULL,NULL,1,3,NULL),(10,'CP-6','C','Vicentx','Gonzalez','Vergara','1958-12-10',67,1,3,1,NULL,2000,NULL,NULL,3,NULL,2,2.00,2.00,2.00,2.00,NULL,'2030-01-20',NULL,NULL,'2026-02-13',NULL,1,1,'2026-02-14 20:02:39','2026-02-14 20:09:10',NULL,NULL,1,NULL,NULL),(11,'CP-7','C','Daniel','Roman','Ronces',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,NULL,'2026-02-14 20:39:58','2026-02-14 20:39:58',NULL,NULL,1,NULL,NULL);
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contratos`
--

DROP TABLE IF EXISTS `contratos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contratos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `numero` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `monto` decimal(12,2) NOT NULL,
  `estatus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contratos_numero_unique` (`numero`),
  KEY `contratos_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `contratos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contratos`
--

LOCK TABLES `contratos` WRITE;
/*!40000 ALTER TABLE `contratos` DISABLE KEYS */;
/*!40000 ALTER TABLE `contratos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documentos`
--

DROP TABLE IF EXISTS `documentos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documentos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `tipo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `ruta` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `estatus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Activo',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documentos_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `documentos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documentos`
--

LOCK TABLES `documentos` WRITE;
/*!40000 ALTER TABLE `documentos` DISABLE KEYS */;
/*!40000 ALTER TABLE `documentos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `historial_clientes`
--

DROP TABLE IF EXISTS `historial_clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `historial_clientes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `campo_modificado` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor_anterior` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `valor_nuevo` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `usuario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `historial_clientes_cliente_id_foreign` (`cliente_id`),
  KEY `historial_clientes_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `historial_clientes_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `historial_clientes_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `historial_clientes`
--

LOCK TABLES `historial_clientes` WRITE;
/*!40000 ALTER TABLE `historial_clientes` DISABLE KEYS */;
/*!40000 ALTER TABLE `historial_clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2019_12_14_000001_create_personal_access_tokens_table',1),(2,'2026_02_01_180409_create_usuarios_table',1),(3,'2026_02_01_180410_create_catalogo_institutos_table',1),(4,'2026_02_01_180411_create_catalogo_regimenes_table',1),(5,'2026_02_01_180412_create_catalogo_tramites_table',1),(6,'2026_02_01_180413_create_catalogo_modalidades_table',1),(7,'2026_02_01_180414_create_clientes_table',1),(8,'2026_02_01_180415_create_cliente_contactos_table',1),(9,'2026_02_01_180416_create_cliente_curps_table',1),(10,'2026_02_01_180417_create_cliente_rfcs_table',1),(11,'2026_02_01_180418_create_cliente_nsss_table',1),(12,'2026_02_01_180419_create_cliente_estados_table',1),(13,'2026_02_01_180420_create_cliente_municipios_table',1),(14,'2026_02_01_180421_create_cliente_localidades_table',1),(15,'2026_02_01_180422_create_cliente_domicilios_table',1),(16,'2026_02_01_180423_create_cliente_beneficiarios_table',1),(17,'2026_02_01_180424_create_cliente_estudios_table',1),(18,'2026_02_01_180425_create_cliente_ocupaciones_table',1),(19,'2026_02_01_180426_create_cliente_dependientes_table',1),(20,'2026_02_01_180427_create_contratos_table',1),(21,'2026_02_01_180428_create_pagos_table',1),(22,'2026_02_01_180429_create_documentos_table',1),(23,'2026_02_01_180430_create_seguimientos_table',1),(24,'2026_02_01_180431_create_notas_table',1),(25,'2026_02_01_180432_create_historial_clientes_table',1),(26,'2026_02_01_180433_create_archivos_table',1),(27,'2026_02_14_182000_add_afore_id_to_clientes',2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notas`
--

DROP TABLE IF EXISTS `notas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `titulo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenido` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notas_cliente_id_foreign` (`cliente_id`),
  CONSTRAINT `notas_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notas`
--

LOCK TABLES `notas` WRITE;
/*!40000 ALTER TABLE `notas` DISABLE KEYS */;
/*!40000 ALTER TABLE `notas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pagos`
--

DROP TABLE IF EXISTS `pagos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pagos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contrato_id` bigint unsigned NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `metodo` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estatus` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Registrado',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pagos_contrato_id_foreign` (`contrato_id`),
  CONSTRAINT `pagos_contrato_id_foreign` FOREIGN KEY (`contrato_id`) REFERENCES `contratos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pagos`
--

LOCK TABLES `pagos` WRITE;
/*!40000 ALTER TABLE `pagos` DISABLE KEYS */;
/*!40000 ALTER TABLE `pagos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
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
-- Table structure for table `prospectos`
--

DROP TABLE IF EXISTS `prospectos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `prospectos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_paterno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `apellido_materno` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `curp` char(18) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `nss` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `celular` varchar(13) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `convertido` tinyint(1) NOT NULL DEFAULT '0',
  `estatus_prospecto_id` bigint unsigned NOT NULL,
  `cliente_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `notas` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `curp` (`curp`),
  KEY `fk_prospectos_cliente` (`cliente_id`),
  KEY `fk_prospectos_estatus` (`estatus_prospecto_id`),
  CONSTRAINT `fk_prospectos_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_prospectos_estatus` FOREIGN KEY (`estatus_prospecto_id`) REFERENCES `catalogo_estatus_prospectos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prospectos`
--

LOCK TABLES `prospectos` WRITE;
/*!40000 ALTER TABLE `prospectos` DISABLE KEYS */;
INSERT INTO `prospectos` VALUES (1,'Maximino','Gonzalez','Lopez','GOLM680608HDFNPX07','45906803437','5610145851',1,6,2,'2026-02-06 15:10:58','1er cliente','2026-02-10 18:48:14'),(2,'Gloria','Fuentes','Vergara','FUVG681210MGRNRL02','15866806258','7772143452',1,6,NULL,'2026-02-06 15:12:08',NULL,'2026-02-10 19:07:30'),(3,'Martha','Morales','Alcala','MOAM631118MDFRLR06','17856309491','5511335271',1,6,4,'2026-02-06 15:34:51','3ER CLIENTE','2026-02-10 19:46:49'),(4,'Maria Luisa','Morales','Alcala','MOAL641010MDFRLS08','64846518369','5548623843',1,6,5,'2026-02-06 15:35:18',NULL,'2026-02-10 20:13:31'),(5,'Hector','Rodriguez','Bonilla','ROBH600123HDFDNC08','15026000206','7775157537',1,6,1,'2026-02-06 15:35:56','5to cliente','2026-02-10 07:32:40'),(6,'Maria del Rocio','Gonzalez','Morales','GOMR660524MGRNRC03','76916601222','0014045074423',1,6,NULL,'2026-02-06 19:12:53',NULL,'2026-02-10 20:17:50'),(7,'Antonio','Diaz','del Moral','DIMA600410HMSZRN05','15836003093','7774680609',1,6,8,'2026-02-06 19:13:21',NULL,'2026-02-10 20:30:20'),(8,'Florentino','Hernandez','Garcia','HEGF461017HHGRRL00','06664615785','7771234567',1,6,9,'2026-02-06 19:14:44',NULL,'2026-02-10 20:31:29'),(9,'Julio','Dominguez','Sanreymond','DOSJ690216HDFMNL03','10856933014','5644944042',1,6,7,'2026-02-10 05:20:17',NULL,'2026-02-10 20:20:43'),(10,'Ricardo','Rosales','Navarro','RONR631008HDFSVC00','11816324633','5526999229',1,6,10,'2026-02-10 20:49:07',NULL,'2026-02-10 20:55:51'),(12,'Santiago','Santana','Jaimez','SAJS651127HGRNMN06','72836536697','5573718947',1,6,11,'2026-02-10 20:59:49',NULL,'2026-02-10 21:24:17'),(15,'Manuel','Carrillo','Leyva','CALM630608HDGRYN05','31806320276','0017207254469',1,6,12,'2026-02-10 21:06:44',NULL,'2026-02-10 21:28:56'),(16,'Ruben','Benitez','Dueñas','BEDR590111HGRNXB09','01765905623','7671023486',1,6,13,'2026-02-10 21:12:20',NULL,'2026-02-10 22:08:38'),(17,'Raul','Vazquez','Garcia','VAGR550517HDGZRL07','31765503391','5591966650',1,6,14,'2026-02-10 21:13:19',NULL,'2026-02-10 22:17:17'),(18,'Benjamin','Loeza','Matias','LOMB460331HGRZTN17','01714617576','7671308988',1,6,15,'2026-02-10 21:27:51',NULL,'2026-02-10 22:17:45'),(19,'Miguel','Barrera','Cardenas','BACM571214HMSRRG05','15785702232','7775236149',1,6,16,'2026-02-10 21:37:33',NULL,'2026-02-10 22:17:56'),(20,'raul','callejo','lara','CALR551206HDFLRL08','43745541375','5610492232',1,6,5,'2026-02-10 21:44:49',NULL,'2026-02-14 06:43:57'),(21,'rene','ocampo','bustos','OABR690425HMSCSN05','15876927516','0019565263978',1,6,4,'2026-02-10 22:05:39',NULL,'2026-02-14 06:26:04'),(22,'Vicente','Dominguez','Sanreymond','DOSV710526HDFMNC02','07917124906','5530170859',1,6,NULL,'2026-02-10 22:06:34',NULL,'2026-02-12 08:58:41'),(23,'Maximino','Gonzalez','Alcala','MOAM631118MDFRLR77','15866806254','5610145851',1,6,NULL,'2026-02-14 07:41:14',NULL,'2026-02-14 07:41:46'),(24,'Gloria','Fuentes','Vergara','FUVG681210MGRN1111','55556320276','7772143452',1,6,8,'2026-02-14 08:01:01',NULL,'2026-02-14 16:06:59'),(25,'Martha','Morales','bustos','gggg681210MGRNRL03','55556806258','5644944042',1,6,9,'2026-02-14 16:25:59',NULL,'2026-02-14 16:26:03'),(26,'Vicentx','Gonzalez','Vergara','hhhh680608HDFNPX07','44446806258','5644944042',1,6,10,'2026-02-14 18:07:01',NULL,'2026-02-14 20:02:39'),(27,'Martha','Dominguex','Sanreymonx','kkkk651127HGRNMN06','22116320276','5573718947',0,4,NULL,'2026-02-14 18:07:23',NULL,'2026-02-14 18:54:19'),(28,'Martha','lara','bustos','FFFF681210MGRNRL03','15866804444','5573718947',0,3,NULL,'2026-02-14 18:36:42',NULL,'2026-02-14 18:54:16'),(29,'romeo','sancgez','perez','GOLM444444HDFNPX07','00443309491','7671308988',0,2,NULL,'2026-02-14 18:53:43',NULL,'2026-02-14 18:54:13'),(30,'Antonio','Rosas','Sanchez','cccc681210MGRNRL03','33336803437','5610145851',0,5,NULL,'2026-02-14 18:54:09',NULL,'2026-02-14 20:32:54'),(31,'Maximino','Dominguez','Sanreymond','mmmm571214HMSRRG05','00336803437','5644944042',0,1,NULL,'2026-02-14 20:24:30',NULL,'2026-02-14 20:24:30'),(32,'Daniel','Roman','Ronces','RORD681210MGRNRL03','15967709491','7771161055',1,6,11,'2026-02-14 20:31:59','yomeroooooooooooooo','2026-02-14 20:39:58');
/*!40000 ALTER TABLE `prospectos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `secuencias`
--

DROP TABLE IF EXISTS `secuencias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `secuencias` (
  `id` int NOT NULL,
  `valor` bigint NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `secuencias`
--

LOCK TABLES `secuencias` WRITE;
/*!40000 ALTER TABLE `secuencias` DISABLE KEYS */;
INSERT INTO `secuencias` VALUES (1,0);
/*!40000 ALTER TABLE `secuencias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seguimientos`
--

DROP TABLE IF EXISTS `seguimientos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `seguimientos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cliente_id` bigint unsigned NOT NULL,
  `fecha` date NOT NULL,
  `comentario` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `usuario_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `seguimientos_cliente_id_foreign` (`cliente_id`),
  KEY `seguimientos_usuario_id_foreign` (`usuario_id`),
  CONSTRAINT `seguimientos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id`),
  CONSTRAINT `seguimientos_usuario_id_foreign` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seguimientos`
--

LOCK TABLES `seguimientos` WRITE;
/*!40000 ALTER TABLE `seguimientos` DISABLE KEYS */;
/*!40000 ALTER TABLE `seguimientos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `rol` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'usuario',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `estatus` enum('activo','inactivo') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'activo',
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimo_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `usuarios_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'Administrador','dronces@hotmail.com','$2y$12$ajZyXNAHRBom8EhZXwau1eCcPaRXBj6A7wcCByLSvEd0UR5iVi4GC','admin',1,'2026-02-02 01:31:03','2026-02-02 01:31:03',NULL,'activo','2026-02-02 01:31:03','2026-02-14 23:02:57'),(2,'Usuario Normal','usuario@example.com','$2y$12$bAUaXSBw0bEFHXk7iBTykOYWddTr0pU2I.LueYkSDDV7kkk0GT.uK','usuario',1,'2026-02-02 01:31:03','2026-02-02 01:31:03',NULL,'activo','2026-02-02 01:31:03',NULL);
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

-- Dump completed on 2026-02-14 18:50:59
