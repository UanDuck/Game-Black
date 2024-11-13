-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: gbtest
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `compra`
--

DROP TABLE IF EXISTS `compra`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `compra` (
  `id_c` int(10) NOT NULL AUTO_INCREMENT,
  `total` float DEFAULT NULL,
  `subtotal` float DEFAULT NULL,
  `id_u` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_c`),
  KEY `id_u` (`id_u`),
  CONSTRAINT `compra_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compra`
--

LOCK TABLES `compra` WRITE;
/*!40000 ALTER TABLE `compra` DISABLE KEYS */;
/*!40000 ALTER TABLE `compra` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarjeta`
--

DROP TABLE IF EXISTS `tarjeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tarjeta` (
  `id_tj` int(10) NOT NULL AUTO_INCREMENT,
  `nom_titular` varchar(100) DEFAULT NULL,
  `tipo_tj` enum('debito','credito') DEFAULT NULL,
  `fecha_venc` date DEFAULT NULL,
  `num_tj` varchar(20) DEFAULT NULL,
  `cvv` int(4) DEFAULT NULL,
  `id_u` int(10) DEFAULT NULL,
  PRIMARY KEY (`id_tj`),
  KEY `id_u` (`id_u`),
  CONSTRAINT `tarjeta_ibfk_1` FOREIGN KEY (`id_u`) REFERENCES `usuario` (`id_u`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarjeta`
--

LOCK TABLES `tarjeta` WRITE;
/*!40000 ALTER TABLE `tarjeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `tarjeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `id_u` int(10) NOT NULL AUTO_INCREMENT,
  `usuario` varchar(50) DEFAULT NULL,
  `nom_u` varchar(50) DEFAULT NULL,
  `ap_u` varchar(50) DEFAULT NULL,
  `am_u` varchar(50) DEFAULT NULL,
  `correo_u` varchar(100) DEFAULT NULL,
  `contrasenia_u` varchar(255) DEFAULT NULL,
  `tipo_u` enum('admin','user') DEFAULT NULL,
  `telefono` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`id_u`),
  UNIQUE KEY `email` (`correo_u`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'orlando ','test1','aptets','amtest1','ejemploxd@gmail.com','$2y$10$FNOvR1kIQgpXDy09irESWOglj.jq1sF440AL.OwCpaXYukUlZZZFC','user',5522113344),(2,'alehi','ale','ave','ara','alehi@gmail.com','$2y$10$c8E.mGf0qUr0u/IXecssEe/xuN5PysH8JNytA8AanGF2FEUVAfBda','user',5523232323);
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vc`
--

DROP TABLE IF EXISTS `vc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vc` (
  `id_v` int(10) DEFAULT NULL,
  `id_c` int(10) DEFAULT NULL,
  KEY `id_v` (`id_v`),
  KEY `id_c` (`id_c`),
  CONSTRAINT `vc_ibfk_1` FOREIGN KEY (`id_v`) REFERENCES `videojuegos` (`id_v`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `vc_ibfk_2` FOREIGN KEY (`id_c`) REFERENCES `compra` (`id_c`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vc`
--

LOCK TABLES `vc` WRITE;
/*!40000 ALTER TABLE `vc` DISABLE KEYS */;
/*!40000 ALTER TABLE `vc` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videojuegos`
--

DROP TABLE IF EXISTS `videojuegos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videojuegos` (
  `id_v` int(10) NOT NULL AUTO_INCREMENT,
  `nom_v` varchar(100) DEFAULT NULL,
  `desc_v` text DEFAULT NULL,
  `fecha_lanz` date DEFAULT NULL,
  `clasif_v` varchar(100) DEFAULT NULL,
  `genero_v` varchar(100) DEFAULT NULL,
  `precio` float DEFAULT NULL,
  `imagen` text DEFAULT NULL,
  PRIMARY KEY (`id_v`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videojuegos`
--

LOCK TABLES `videojuegos` WRITE;
/*!40000 ALTER TABLE `videojuegos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videojuegos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-09 11:50:30
