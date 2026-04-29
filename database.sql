-- MySQL dump 10.13  Distrib 8.0.36, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: e-commerce
-- ------------------------------------------------------
-- Server version	8.0.37

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
-- Table structure for table `category`
--

DROP TABLE IF EXISTS `category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `category` (
  `id_category` int NOT NULL AUTO_INCREMENT,
  `name_category` varchar(45) NOT NULL,
  PRIMARY KEY (`id_category`),
  KEY `idx_category_name` (`name_category`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `category`
--

LOCK TABLES `category` WRITE;
/*!40000 ALTER TABLE `category` DISABLE KEYS */;
INSERT INTO `category` VALUES (6,'Accesorios'),(3,'Blusas'),(4,'Faldas'),(5,'Pantalones'),(2,'Vestidos'),(1,'Zapatos');
/*!40000 ALTER TABLE `category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `color`
--

DROP TABLE IF EXISTS `color`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `color` (
  `id_color` int NOT NULL AUTO_INCREMENT,
  `name_color` varchar(10) NOT NULL,
  PRIMARY KEY (`id_color`),
  KEY `idx_color_name` (`name_color`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `color`
--

LOCK TABLES `color` WRITE;
/*!40000 ALTER TABLE `color` DISABLE KEYS */;
INSERT INTO `color` VALUES (8,'Azul'),(4,'Bamboo'),(7,'Blanco'),(3,'Estampado'),(5,'Maquillaje'),(6,'Mercurio'),(10,'Naranja'),(1,'Negro'),(9,'Oro'),(2,'Pistache');
/*!40000 ALTER TABLE `color` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `imagen` varchar(255) NOT NULL,
  `price` int NOT NULL,
  `id_size` int NOT NULL,
  `id_category` int NOT NULL,
  `url` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `talla_fk_idx` (`id_size`),
  KEY `categoria_fk_idx` (`id_category`),
  KEY `idx_products_filters` (`id_category`,`id_size`,`price`),
  KEY `idx_products_price` (`price`),
  CONSTRAINT `categoria_fk` FOREIGN KEY (`id_category`) REFERENCES `category` (`id_category`) ON UPDATE CASCADE,
  CONSTRAINT `talla_fk` FOREIGN KEY (`id_size`) REFERENCES `size` (`id_size`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Alexa Negro','Zapato Alexa negro','https://davidsalomonfashion.com/wp-content/uploads/2022/08/alexa-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/alexa-negro/'),(2,'JUMPSUIT UN HOMBRO CAIDO LINO PISTACHE','Jumpsuit pistache','https://davidsalomonfashion.com/wp-content/uploads/2024/02/Palazzo-hombros-verde-pastel0-300x450.jpg',16800,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/jumpsuit-hombros-pantalon/'),(3,'VESTIDO CORTO PAREO BENGALAS','Vestido bengalas','https://davidsalomonfashion.com/wp-content/uploads/2024/02/vestido-pareo-bengalas1-300x450.jpg',18800,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/vestido-corto-falta-tipo-pareo/'),(4,'Vestido Irregular Bamboo','Vestido bamboo','https://davidsalomonfashion.com/wp-content/uploads/2024/02/vestido-irregular-bamboo0-300x450.jpg',7800,2,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/vestido-irregular-escote-v-en-espalda/'),(5,'Alexa Maquillaje','Zapato maquillaje','https://davidsalomonfashion.com/wp-content/uploads/2024/07/alexa-nude4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/alexa-maquillaje/'),(6,'Alexa Mercurio','Zapato mercurio','https://davidsalomonfashion.com/wp-content/uploads/2024/07/alexa-mercurio4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/alexa-mercurio/'),(7,'Itzel Maquillaje','Zapato maquillaje','https://davidsalomonfashion.com/wp-content/uploads/2024/07/itzel-nude4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/itzel/'),(8,'Salomon Maquillaje','Zapato maquillaje','https://davidsalomonfashion.com/wp-content/uploads/2024/07/salomon-nude4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/salomon/'),(9,'Sharon 172 Negro','Zapato negro','https://davidsalomonfashion.com/wp-content/uploads/2024/07/SH172-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-172/'),(10,'Sharon 175 Maquillaje','Zapato maquillaje','https://davidsalomonfashion.com/wp-content/uploads/2024/07/SHN175-nude4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-175/'),(11,'Sharon 175 Mercurio','Zapato mercurio','https://davidsalomonfashion.com/wp-content/uploads/2024/07/SHN175-mercurio-4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-175-2/'),(12,'Sharon 410 Maquillaje','Zapato maquillaje','https://davidsalomonfashion.com/wp-content/uploads/2024/07/sharon410-nude4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-410/'),(13,'Blusa Lino Blanco','Blusa blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/02/blusa-manga-suelta-blanco1-300x450.jpg',5800,3,3,'https://davidsalomonfashion.com/tienda/pret-a-porter/blusas/blusa-manga-suelta/'),(14,'Falda Lino Blanco','Falda blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/02/falda-larga-envolvente-blanco0-300x450.jpg',6800,3,4,'https://davidsalomonfashion.com/tienda/pret-a-porter/faldas/falda-larga-envolvente/'),(15,'Pantalon Lino Blanco','Pantalon blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/02/pantalon-ancho-blanco0-300x450.jpg',5500,3,5,'https://davidsalomonfashion.com/tienda/pret-a-porter/pantalones/pantalon-ancho/'),(16,'Vestido Slip Azul','Vestido azul','https://davidsalomonfashion.com/wp-content/uploads/2024/02/vestido-slip-turquesa0-300x450.jpg',8500,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/vestido-tipo-slip/'),(17,'Vestido Negro','Vestido negro','https://davidsalomonfashion.com/wp-content/uploads/2024/02/vestido-cinturon-negro0-300x450.jpg',7800,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/vestido-cinturon/'),(18,'Jumpsuit Blanco','Jumpsuit blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/02/jumpsuit-mangas-acampanadas-blanco0-300x450.jpg',15800,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/jumpsuit-mangas/'),(19,'Collar Perlas','Collar blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/03/collar-perlas0-300x450.jpg',3500,1,6,'https://davidsalomonfashion.com/tienda/accesorios/collares/collar-perlas/'),(20,'Collar Dorado','Collar oro','https://davidsalomonfashion.com/wp-content/uploads/2024/03/collar-dorado0-300x450.jpg',2800,1,6,'https://davidsalomonfashion.com/tienda/accesorios/collares/collar-dorado/'),(21,'Arete Perlas','Arete blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/03/arete-perlas0-300x450.jpg',1800,1,6,'https://davidsalomonfashion.com/tienda/accesorios/aretes/arete-perlas/'),(22,'Pulsera Perlas','Pulsera blanco','https://davidsalomonfashion.com/wp-content/uploads/2024/03/pulsera-perlas0-300x450.jpg',2200,1,6,'https://davidsalomonfashion.com/tienda/accesorios/pulseras/pulsera-perlas/'),(23,'Blusa Negra','Blusa negro','https://davidsalomonfashion.com/wp-content/uploads/2024/02/blusa-corta-negro0-300x450.jpg',4800,2,3,'https://davidsalomonfashion.com/tienda/pret-a-porter/blusas/blusa-corta/'),(24,'Vestido Flores','Vestido estampado','https://davidsalomonfashion.com/wp-content/uploads/2024/02/vestido-largo-flores0-300x450.jpg',12800,3,2,'https://davidsalomonfashion.com/tienda/pret-a-porter/vestidos/vestido-largo-flores/'),(25,'Sharon 200 Negro','Zapato negro','https://davidsalomonfashion.com/wp-content/uploads/2024/07/SH200-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-200/'),(26,'Salomon Negro','Zapato negro','https://davidsalomonfashion.com/wp-content/uploads/2022/08/salomon-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/salomon-negro/'),(27,'Itzel Negro','Zapato negro','https://davidsalomonfashion.com/wp-content/uploads/2022/08/itzel-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/itzel-negro/'),(28,'Sharon 410 Negro','Zapato negro','https://davidsalomonfashion.com/wp-content/uploads/2024/07/sharon410-negro4-300x450.jpg',2800,1,1,'https://davidsalomonfashion.com/tienda/zapatos/sharon-410-negro/'),(29,'Blusa Turquesa','Blusa azul','https://davidsalomonfashion.com/wp-content/uploads/2024/02/blusa-hombros-turquesa0-300x450.jpg',5500,3,3,'https://davidsalomonfashion.com/tienda/pret-a-porter/blusas/blusa-hombros/'),(30,'Falda Naranja','Falda naranja','https://davidsalomonfashion.com/wp-content/uploads/2024/02/falda-plisada-naranja0-300x450.jpg',6500,3,4,'https://davidsalomonfashion.com/tienda/pret-a-porter/faldas/falda-plisada/');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products_colors`
--

DROP TABLE IF EXISTS `products_colors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products_colors` (
  `id_products_colors` int NOT NULL AUTO_INCREMENT,
  `id_product` int NOT NULL,
  `id_color` int NOT NULL,
  PRIMARY KEY (`id_products_colors`),
  KEY `product_fk_idx` (`id_product`),
  KEY `color_fk_idx` (`id_color`),
  KEY `idx_products_colors_filter` (`id_color`,`id_product`),
  CONSTRAINT `colors_fk_` FOREIGN KEY (`id_color`) REFERENCES `color` (`id_color`) ON UPDATE CASCADE,
  CONSTRAINT `product_fk` FOREIGN KEY (`id_product`) REFERENCES `products` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=31 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products_colors`
--

LOCK TABLES `products_colors` WRITE;
/*!40000 ALTER TABLE `products_colors` DISABLE KEYS */;
INSERT INTO `products_colors` VALUES (1,1,1),(9,9,1),(17,17,1),(23,23,1),(25,25,1),(26,26,1),(27,27,1),(28,28,1),(2,2,2),(3,3,3),(24,24,3),(4,4,4),(5,5,5),(7,7,5),(8,8,5),(10,10,5),(12,12,5),(6,6,6),(11,11,6),(13,13,7),(14,14,7),(15,15,7),(18,18,7),(19,19,7),(21,21,7),(22,22,7),(16,16,8),(29,29,8),(20,20,9),(30,30,10);
/*!40000 ALTER TABLE `products_colors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `size`
--

DROP TABLE IF EXISTS `size`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `size` (
  `id_size` int NOT NULL AUTO_INCREMENT,
  `name_size` varchar(5) NOT NULL,
  PRIMARY KEY (`id_size`),
  KEY `idx_size_name` (`name_size`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `size`
--

LOCK TABLES `size` WRITE;
/*!40000 ALTER TABLE `size` DISABLE KEYS */;
INSERT INTO `size` VALUES (3,'M'),(2,'S'),(1,'Única');
/*!40000 ALTER TABLE `size` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-29  4:10:28
