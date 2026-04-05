-- MySQL dump 10.13  Distrib 9.5.0, for macos15.7 (arm64)
--
-- Host: localhost    Database: gameshala
-- ------------------------------------------------------
-- Server version	9.5.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ '489571d6-efb1-11f0-8cd0-1f02ad5cc7d2:1-336';

--
-- Table structure for table `batch_procurement_rules`
--

DROP TABLE IF EXISTS `batch_procurement_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `batch_procurement_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_id` bigint unsigned NOT NULL,
  `procurement_rule_id` bigint unsigned NOT NULL,
  `applied_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_batch_rules_batch` (`batch_id`),
  KEY `idx_batch_rules_rule` (`procurement_rule_id`),
  KEY `idx_batch_rules_active` (`is_active`),
  CONSTRAINT `fk_batch_rules_batch` FOREIGN KEY (`batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_procurement_rules`
--

LOCK TABLES `batch_procurement_rules` WRITE;
/*!40000 ALTER TABLE `batch_procurement_rules` DISABLE KEYS */;
INSERT INTO `batch_procurement_rules` VALUES (4,3,4,'2026-03-10 16:07:41',NULL,0),(5,5,5,'2026-03-11 15:07:03',NULL,0),(6,6,5,'2026-03-11 15:09:25',NULL,0),(7,7,5,'2026-03-11 15:11:20',NULL,0),(8,8,6,'2026-03-12 07:05:50',NULL,0),(9,3,6,'2026-03-12 07:06:02',NULL,0),(10,9,6,'2026-03-12 07:10:41',NULL,0),(11,10,7,'2026-03-12 08:40:04',NULL,0),(12,11,7,'2026-03-12 08:41:12',NULL,0),(13,12,8,'2026-03-12 09:35:59',NULL,0),(14,13,9,'2026-03-12 09:59:17',NULL,0),(15,14,6,'2026-03-12 10:47:38',NULL,0),(16,15,6,'2026-03-12 11:15:51',NULL,0),(17,15,9,'2026-03-12 11:18:21',NULL,0),(18,16,9,'2026-03-12 16:24:27',NULL,0),(19,17,9,'2026-03-12 16:28:51',NULL,0),(20,18,10,'2026-03-13 09:11:52',NULL,0),(21,19,11,'2026-03-13 09:28:43',NULL,0),(22,20,11,'2026-03-13 09:38:51',NULL,0),(23,21,11,'2026-03-13 09:55:10',NULL,0),(24,22,11,'2026-03-13 10:13:27',NULL,0),(25,23,11,'2026-03-13 10:34:01',NULL,0),(26,24,12,'2026-03-13 11:01:29',NULL,0),(27,25,12,'2026-03-13 11:05:52',NULL,0),(28,26,12,'2026-03-13 11:08:22',NULL,0),(29,27,12,'2026-03-13 11:11:48',NULL,0),(30,28,12,'2026-03-13 11:18:05',NULL,0),(31,29,12,'2026-03-13 11:22:19',NULL,0),(32,30,12,'2026-03-13 11:25:49',NULL,0),(33,31,12,'2026-03-13 11:32:10',NULL,0),(34,20,13,'2026-03-14 12:17:38',NULL,0),(35,31,11,'2026-03-14 12:19:08',NULL,0),(36,31,13,'2026-03-14 12:20:04',NULL,0),(37,31,6,'2026-03-14 13:12:22',NULL,0),(38,32,11,'2026-03-15 07:30:42',NULL,0),(39,32,6,'2026-03-15 07:33:11',NULL,0),(40,33,8,'2026-03-15 07:40:56',NULL,0),(41,34,6,'2026-03-15 07:47:18',NULL,0),(42,35,6,'2026-03-15 07:53:34',NULL,0),(43,33,6,'2026-03-15 07:55:23',NULL,0),(44,36,9,'2026-03-15 08:14:30',NULL,0),(45,37,9,'2026-03-15 08:23:26',NULL,0),(46,38,9,'2026-03-15 08:28:24',NULL,0),(47,39,10,'2026-03-15 08:33:02',NULL,0),(48,40,6,'2026-03-15 08:39:44',NULL,0),(49,41,9,'2026-03-15 08:54:38',NULL,0),(50,41,14,'2026-03-15 09:02:25',NULL,0),(51,42,6,'2026-03-15 09:06:54',NULL,0),(52,43,6,'2026-03-15 09:12:14',NULL,0),(53,44,14,'2026-03-17 08:30:50',NULL,0),(54,45,10,'2026-03-17 08:36:42',NULL,0),(55,46,9,'2026-03-17 08:39:46',NULL,0),(56,47,10,'2026-03-17 08:45:26',NULL,0),(57,48,14,'2026-03-17 08:49:48',NULL,0),(58,49,10,'2026-03-17 08:57:15',NULL,0),(59,50,10,'2026-03-17 09:02:54',NULL,0),(60,51,14,'2026-03-17 09:05:58',NULL,0),(61,52,10,'2026-03-17 09:11:17',NULL,0),(62,53,10,'2026-03-17 09:17:56',NULL,0),(63,54,10,'2026-03-17 09:22:28',NULL,0),(64,55,5,'2026-03-17 09:40:35',NULL,0),(65,56,10,'2026-03-17 09:46:12',NULL,0),(66,57,9,'2026-03-17 09:50:40',NULL,0),(67,58,10,'2026-03-17 09:54:17',NULL,0),(68,59,10,'2026-03-17 09:58:34',NULL,0),(69,60,10,'2026-03-17 10:01:02',NULL,0),(70,61,10,'2026-03-17 10:09:26',NULL,0),(71,62,10,'2026-03-17 10:12:44',NULL,0),(72,63,10,'2026-03-17 10:14:30',NULL,0),(73,64,10,'2026-03-17 10:16:29',NULL,0),(74,65,10,'2026-03-18 07:06:51',NULL,0),(75,66,9,'2026-03-18 07:11:44',NULL,0),(76,67,10,'2026-03-18 07:16:03',NULL,0),(77,68,10,'2026-03-18 07:19:26',NULL,0),(78,69,10,'2026-03-18 07:22:10',NULL,0),(79,70,10,'2026-03-18 07:32:56',NULL,0),(80,71,10,'2026-03-18 07:37:09',NULL,0),(81,17,19,'2026-03-18 09:48:26',NULL,1),(82,16,19,'2026-03-18 09:48:46',NULL,1),(83,15,20,'2026-03-18 09:50:16',NULL,1),(84,14,21,'2026-03-18 09:50:46',NULL,1),(85,13,19,'2026-03-18 09:51:12',NULL,1),(86,12,21,'2026-03-18 09:51:56',NULL,1),(87,11,20,'2026-03-18 09:53:50',NULL,1),(88,10,20,'2026-03-18 09:54:15',NULL,1),(89,9,21,'2026-03-18 09:55:06',NULL,1),(90,72,21,'2026-03-18 12:50:30',NULL,1),(91,73,20,'2026-03-18 12:53:49',NULL,1),(92,74,20,'2026-03-18 12:56:26',NULL,1),(93,31,17,'2026-03-18 13:11:21',NULL,1),(94,30,18,'2026-03-18 13:12:09',NULL,1),(95,29,17,'2026-03-18 13:12:50',NULL,1),(96,28,18,'2026-03-18 13:13:08',NULL,1),(97,27,17,'2026-03-18 13:13:36',NULL,1),(98,26,17,'2026-03-18 13:14:04',NULL,1),(99,25,18,'2026-03-18 13:14:27',NULL,1),(100,24,18,'2026-03-18 13:15:29',NULL,1),(101,23,16,'2026-03-18 13:15:58',NULL,1),(102,64,16,'2026-03-18 13:16:45',NULL,1),(103,63,17,'2026-03-18 13:17:20',NULL,1),(104,65,18,'2026-03-18 13:17:46',NULL,1),(105,66,17,'2026-03-18 13:18:15',NULL,1),(106,67,17,'2026-03-18 13:18:45',NULL,1),(107,68,18,'2026-03-18 13:19:03',NULL,1),(108,69,16,'2026-03-18 13:19:32',NULL,1),(109,32,18,'2026-03-18 13:19:57',NULL,1),(110,70,17,'2026-03-18 13:20:29',NULL,1),(111,71,16,'2026-03-18 13:20:55',NULL,1),(112,33,18,'2026-03-18 13:21:24',NULL,1),(113,34,18,'2026-03-18 13:21:41',NULL,1),(114,20,18,'2026-03-18 13:22:31',NULL,1),(115,35,18,'2026-03-18 13:22:47',NULL,1),(116,36,15,'2026-03-18 13:23:13',NULL,0),(117,37,15,'2026-03-18 13:23:36',NULL,0),(118,38,15,'2026-03-18 13:23:59',NULL,0),(119,44,16,'2026-03-18 13:24:22',NULL,1),(120,55,16,'2026-03-18 13:24:51',NULL,1),(121,56,16,'2026-03-18 13:25:27',NULL,1),(122,22,16,'2026-03-18 13:25:54',NULL,1),(123,62,17,'2026-03-18 13:26:13',NULL,1),(124,61,15,'2026-03-18 13:26:31',NULL,0),(125,60,18,'2026-03-18 13:27:01',NULL,1),(126,7,18,'2026-03-18 13:27:50',NULL,0),(127,7,22,'2026-03-18 13:29:03',NULL,0),(128,6,22,'2026-03-18 13:29:19',NULL,0),(129,5,22,'2026-03-18 13:29:41',NULL,1),(130,59,16,'2026-03-18 13:30:23',NULL,1),(131,21,18,'2026-03-18 13:30:55',NULL,1),(132,58,19,'2026-03-18 13:31:31',NULL,0),(133,57,16,'2026-03-18 13:31:59',NULL,1),(134,58,15,'2026-03-18 14:58:17',NULL,1),(135,19,18,'2026-03-18 14:58:47',NULL,1),(136,75,16,'2026-03-20 11:31:24',NULL,1),(137,76,16,'2026-03-20 11:46:11',NULL,1),(138,77,18,'2026-03-20 11:58:34',NULL,1),(139,78,18,'2026-03-20 12:40:11',NULL,1),(140,54,18,'2026-03-20 13:01:14',NULL,1),(141,53,18,'2026-03-20 13:01:33',NULL,1),(142,43,17,'2026-03-20 13:02:52',NULL,1),(143,39,16,'2026-03-20 13:16:25',NULL,1),(144,45,16,'2026-03-20 13:16:50',NULL,1),(145,46,16,'2026-03-20 13:17:14',NULL,1),(146,48,15,'2026-03-20 13:17:34',NULL,0),(147,51,17,'2026-03-20 13:19:33',NULL,1),(148,18,15,'2026-03-20 13:22:10',NULL,0),(149,47,17,'2026-03-20 13:22:30',NULL,1),(150,52,17,'2026-03-20 13:22:48',NULL,1),(151,42,18,'2026-03-20 13:23:36',NULL,1),(152,50,15,'2026-03-20 13:23:59',NULL,0),(153,49,16,'2026-03-20 13:24:31',NULL,1),(154,41,16,'2026-03-20 13:24:48',NULL,1),(155,40,18,'2026-03-20 13:26:10',NULL,1),(156,8,21,'2026-03-20 14:01:33',NULL,1),(157,3,21,'2026-03-20 14:01:49',NULL,1),(158,7,24,'2026-03-20 14:27:36',NULL,1),(159,6,21,'2026-03-20 14:28:05',NULL,0),(160,6,24,'2026-03-20 14:28:21',NULL,1),(161,61,16,'2026-03-20 14:53:36',NULL,1),(162,18,16,'2026-03-20 14:54:49',NULL,1),(163,50,16,'2026-03-20 14:56:52',NULL,1),(164,48,16,'2026-03-20 14:57:21',NULL,1),(165,38,16,'2026-03-20 14:57:58',NULL,1),(166,37,16,'2026-03-20 14:58:37',NULL,1),(167,36,16,'2026-03-20 14:59:14',NULL,1);
/*!40000 ALTER TABLE `batch_procurement_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `coupons`
--

DROP TABLE IF EXISTS `coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `coupons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('FLAT','PERCENTAGE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FLAT',
  `discount_value` decimal(12,2) NOT NULL DEFAULT '0.00',
  `min_order_amount` decimal(12,2) DEFAULT NULL,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int DEFAULT NULL,
  `used_count` int NOT NULL DEFAULT '0',
  `valid_from` datetime NOT NULL,
  `valid_to` datetime NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_coupons_code` (`code`),
  KEY `idx_coupons_active_dates` (`is_active`,`valid_from`,`valid_to`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
INSERT INTO `coupons` VALUES (3,'REG-ELC-50','FLAT',50.00,500.00,NULL,25,0,'2026-03-12 12:57:00','2026-12-31 12:57:00',1,'2026-03-12 07:27:30','2026-03-12 07:52:32');
/*!40000 ALTER TABLE `coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_type` enum('INDIVIDUAL','BUSINESS','WALK_IN') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INDIVIDUAL',
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address_line2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `postal_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_number` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `notes` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_customers_type` (`customer_type`),
  KEY `idx_customers_name` (`name`),
  KEY `idx_customers_phone` (`phone`),
  KEY `idx_customers_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `food_beverage_items`
--

DROP TABLE IF EXISTS `food_beverage_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `food_beverage_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_label` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_beverage_items`
--

LOCK TABLES `food_beverage_items` WRITE;
/*!40000 ALTER TABLE `food_beverage_items` DISABLE KEYS */;
INSERT INTO `food_beverage_items` VALUES (5,'Thums Up - 200 ML','10',20.00,1),(6,'Cappuccino',NULL,45.00,1),(7,'Red Bull - 200 ML','1',120.00,1),(8,'Black Coffee',NULL,30.00,1),(9,'Tea',NULL,50.00,1);
/*!40000 ALTER TABLE `food_beverage_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gaming_categories`
--

DROP TABLE IF EXISTS `gaming_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gaming_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gaming_categories_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_categories`
--

LOCK TABLES `gaming_categories` WRITE;
/*!40000 ALTER TABLE `gaming_categories` DISABLE KEYS */;
INSERT INTO `gaming_categories` VALUES (2,'PlayStation 5 - PS5',1),(3,'Meta Quest 3 - VR',1),(4,'Racing Car Simulator',1),(5,'Nex Playground',1);
/*!40000 ALTER TABLE `gaming_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gaming_modes`
--

DROP TABLE IF EXISTS `gaming_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gaming_modes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_gaming_modes_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_modes`
--

LOCK TABLES `gaming_modes` WRITE;
/*!40000 ALTER TABLE `gaming_modes` DISABLE KEYS */;
INSERT INTO `gaming_modes` VALUES (4,'STARTER - PS5',1),(5,'STARTER - VR',1),(6,'REGULAR - PS5 (45 MINUTES)',1),(7,'HAPPY HOURS - VR (25 MINUTES)',1),(8,'STARTER - RACING SIMULATOR',1),(9,'REGULAR - VR',1),(10,'REGULAR - RACING SIMULATOR',1),(11,'HAPPY HOURS - PS5 (60 MINUTES)',1),(12,'HAPPY HOURS - RACING SIMULATOR (25 MINUTES)',1);
/*!40000 ALTER TABLE `gaming_modes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gaming_price_rules`
--

DROP TABLE IF EXISTS `gaming_price_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gaming_price_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gaming_category_id` bigint unsigned NOT NULL,
  `gaming_mode_id` bigint unsigned NOT NULL,
  `price_type` enum('PER_MINUTE','PER_30_MIN','PER_HOUR','FIXED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_gaming_price_rules_category` (`gaming_category_id`),
  KEY `idx_gaming_price_rules_mode` (`gaming_mode_id`),
  CONSTRAINT `fk_gaming_price_rules_category` FOREIGN KEY (`gaming_category_id`) REFERENCES `gaming_categories` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_price_rules_mode` FOREIGN KEY (`gaming_mode_id`) REFERENCES `gaming_modes` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_price_rules`
--

LOCK TABLES `gaming_price_rules` WRITE;
/*!40000 ALTER TABLE `gaming_price_rules` DISABLE KEYS */;
INSERT INTO `gaming_price_rules` VALUES (2,3,11,'',100.00,1),(3,5,6,'',200.00,1),(4,4,8,'',2000.00,1);
/*!40000 ALTER TABLE `gaming_price_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gaming_visit_food_items`
--

DROP TABLE IF EXISTS `gaming_visit_food_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gaming_visit_food_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `gaming_visit_id` bigint unsigned NOT NULL,
  `food_beverage_item_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `line_total` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_gaming_visit_food_items_visit` (`gaming_visit_id`),
  KEY `fk_gaming_visit_food_items_item` (`food_beverage_item_id`),
  CONSTRAINT `fk_gaming_visit_food_items_item` FOREIGN KEY (`food_beverage_item_id`) REFERENCES `food_beverage_items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_visit_food_items_visit` FOREIGN KEY (`gaming_visit_id`) REFERENCES `gaming_visits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_visit_food_items`
--

LOCK TABLES `gaming_visit_food_items` WRITE;
/*!40000 ALTER TABLE `gaming_visit_food_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `gaming_visit_food_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gaming_visits`
--

DROP TABLE IF EXISTS `gaming_visits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gaming_visits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `gaming_price_rule_id` bigint unsigned NOT NULL,
  `invoice_id` bigint unsigned DEFAULT NULL,
  `no_of_players` int NOT NULL DEFAULT '1',
  `start_time` datetime NOT NULL,
  `end_time` datetime DEFAULT NULL,
  `gaming_amount` decimal(12,2) DEFAULT '0.00',
  `food_amount` decimal(12,2) DEFAULT '0.00',
  `total_amount` decimal(12,2) DEFAULT '0.00',
  `status` enum('ONGOING','FINISHED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ONGOING',
  PRIMARY KEY (`id`),
  KEY `idx_gaming_visits_customer` (`customer_id`),
  KEY `idx_gaming_visits_rule` (`gaming_price_rule_id`),
  KEY `idx_gaming_visits_invoice` (`invoice_id`),
  CONSTRAINT `fk_gaming_visits_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_visits_invoice` FOREIGN KEY (`invoice_id`) REFERENCES `invoices` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_visits_rule` FOREIGN KEY (`gaming_price_rule_id`) REFERENCES `gaming_price_rules` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_visits`
--

LOCK TABLES `gaming_visits` WRITE;
/*!40000 ALTER TABLE `gaming_visits` DISABLE KEYS */;
INSERT INTO `gaming_visits` VALUES (13,1,2,17,1,'2026-03-30 03:53:00','2026-03-30 04:06:59',100.00,0.00,100.00,'FINISHED'),(14,2,4,NULL,1,'2026-03-30 10:05:00','2026-03-30 05:07:19',2000.00,0.00,2000.00,'FINISHED'),(15,3,2,NULL,1,'2026-03-30 13:05:00','2026-04-03 07:55:44',100.00,0.00,100.00,'FINISHED'),(16,4,4,NULL,1,'2026-04-03 13:28:00','2026-04-05 09:03:02',2000.00,0.00,2000.00,'FINISHED'),(17,5,3,NULL,1,'2026-04-05 14:47:00',NULL,0.00,0.00,0.00,'ONGOING');
/*!40000 ALTER TABLE `gaming_visits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `invoices`
--

DROP TABLE IF EXISTS `invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `gaming_visit_id` bigint unsigned DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `status` enum('DRAFT','ISSUED','PAID') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `issued_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_invoices_invoice_number` (`invoice_number`),
  UNIQUE KEY `uq_invoices_gaming_visit` (`gaming_visit_id`),
  KEY `idx_invoices_customer` (`customer_id`),
  KEY `idx_invoices_status` (`status`),
  KEY `idx_invoices_order_id` (`order_id`),
  CONSTRAINT `fk_invoices_gaming_visit` FOREIGN KEY (`gaming_visit_id`) REFERENCES `gaming_visits` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
INSERT INTO `invoices` VALUES (16,'INV-20260329-963291',4,NULL,4,1147.50,0.00,0.00,1147.50,'ISSUED','2026-03-29 09:14:42','2026-03-29 09:14:42','2026-03-29 09:14:42'),(17,'GINV-20260330-EFFC96',NULL,13,1,100.00,0.00,0.00,100.00,'PAID','2026-03-30 05:04:46','2026-03-30 05:04:46','2026-03-30 05:04:46'),(18,'INV-20260330-DD8056',5,NULL,4,634.50,0.00,0.00,634.50,'ISSUED','2026-03-30 05:06:34','2026-03-30 05:06:34','2026-03-30 05:06:34'),(19,'INV-20260330-07DF83',6,NULL,1,2377.50,0.00,0.00,2377.50,'ISSUED','2026-03-30 09:27:13','2026-03-30 09:27:13','2026-03-30 09:27:13'),(20,'INV-20260330-6B8335',7,NULL,1,346.50,0.00,0.00,346.50,'ISSUED','2026-03-30 09:28:15','2026-03-30 09:28:15','2026-03-30 09:28:15');
/*!40000 ALTER TABLE `invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (41,'2026-03-07-120000','App\\Database\\Migrations\\CreateUsersTable','default','App',1774788806,1),(42,'2026-03-07-130001','App\\Database\\Migrations\\CreateVendorsTable','default','App',1774788806,1),(43,'2026-03-07-130002','App\\Database\\Migrations\\CreateProductsTable','default','App',1774788806,1),(44,'2026-03-07-130003','App\\Database\\Migrations\\CreateProcurementRulesTable','default','App',1774788806,1),(45,'2026-03-07-130004','App\\Database\\Migrations\\CreateStockBatchesTable','default','App',1774788806,1),(46,'2026-03-07-130005','App\\Database\\Migrations\\CreateStockMovementsTable','default','App',1774788806,1),(47,'2026-03-07-130006','App\\Database\\Migrations\\CreateCustomersTable','default','App',1774788806,1),(48,'2026-03-07-130007','App\\Database\\Migrations\\CreateCouponsTable','default','App',1774788806,1),(49,'2026-03-07-130008','App\\Database\\Migrations\\CreateOrdersTable','default','App',1774788806,1),(50,'2026-03-07-130009','App\\Database\\Migrations\\CreateOrderItemsTable','default','App',1774788806,1),(51,'2026-03-07-130010','App\\Database\\Migrations\\CreateInvoicesTable','default','App',1774788806,1),(52,'2026-03-07-130011','App\\Database\\Migrations\\CreateUserActivitiesTable','default','App',1774788806,1),(53,'2026-03-07-140000','App\\Database\\Migrations\\AddIsActiveToVendors','default','App',1774798222,2),(54,'2026-03-07-140001','App\\Database\\Migrations\\AlterProductsDropPriceStockColumns','default','App',1774798222,2),(55,'2026-03-07-140002','App\\Database\\Migrations\\AlterStockBatchesAndProcurementRules','default','App',1774798222,2),(56,'2026-03-07-150000','App\\Database\\Migrations\\CreateBatchProcurementRulesTable','default','App',1774798222,2),(57,'2026-03-07-160000','App\\Database\\Migrations\\AlterStockBatchesDropSellingPriceSnapshot','default','App',1774798222,2),(58,'2026-03-07-170000','App\\Database\\Migrations\\AddIsActiveToBatchProcurementRules','default','App',1774798222,2),(59,'2026-03-07-180000','App\\Database\\Migrations\\AlterCustomersDropCustomerCode','default','App',1774798222,2);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `stock_batch_id` bigint unsigned DEFAULT NULL,
  `qty` int NOT NULL,
  `unit_price` decimal(12,2) NOT NULL DEFAULT '0.00',
  `unit_cost_snapshot` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `line_total` decimal(12,2) NOT NULL DEFAULT '0.00',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_order_items_order` (`order_id`),
  KEY `idx_order_items_product` (`product_id`),
  KEY `idx_order_items_batch` (`stock_batch_id`),
  CONSTRAINT `fk_order_items_batch` FOREIGN KEY (`stock_batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
INSERT INTO `order_items` VALUES (4,4,39,34,1,1147.50,450.00,0.00,1147.50,'2026-03-29 09:14:18'),(5,5,57,51,1,634.50,270.00,0.00,634.50,'2026-03-30 05:06:29'),(6,6,34,30,1,1402.50,550.00,0.00,1402.50,'2026-03-30 09:27:05'),(7,6,53,47,1,705.00,300.00,0.00,705.00,'2026-03-30 09:27:05'),(8,6,79,75,1,270.00,120.00,0.00,270.00,'2026-03-30 09:27:05'),(9,7,63,57,1,346.50,154.00,0.00,346.50,'2026-03-30 09:28:09');
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `status` enum('PENDING','CONFIRMED','PAID','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PENDING',
  `subtotal` decimal(12,2) NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `tax_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `coupon_id` bigint unsigned DEFAULT NULL,
  `shipping_name` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_via` enum('API','ADMIN') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'API',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_orders_order_number` (`order_number`),
  KEY `idx_orders_customer` (`customer_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_coupon` (`coupon_id`),
  KEY `idx_orders_created_at` (`created_at`),
  CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (4,'ORD-20260329-3AF0D6',4,'PAID',1147.50,0.00,0.00,1147.50,NULL,NULL,NULL,NULL,'ADMIN','2026-03-29 09:14:18','2026-03-29 09:14:42'),(5,'ORD-20260330-561AAB',4,'PAID',634.50,0.00,0.00,634.50,NULL,NULL,NULL,NULL,'ADMIN','2026-03-30 05:06:29','2026-03-30 05:06:34'),(6,'ORD-20260330-FF5B11',1,'PAID',2377.50,0.00,0.00,2377.50,NULL,NULL,NULL,NULL,'ADMIN','2026-03-30 09:27:05','2026-03-30 09:27:13'),(7,'ORD-20260330-169BCA',1,'PAID',346.50,0.00,0.00,346.50,NULL,NULL,NULL,NULL,'ADMIN','2026-03-30 09:28:09','2026-03-30 09:28:15');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `procurement_rules`
--

DROP TABLE IF EXISTS `procurement_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `procurement_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_type` enum('FLAT','PERCENTAGE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FLAT',
  `discount_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `profit_type` enum('FLAT','PERCENTAGE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FLAT',
  `profit_value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_procurement_rules_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procurement_rules`
--

LOCK TABLES `procurement_rules` WRITE;
/*!40000 ALTER TABLE `procurement_rules` DISABLE KEYS */;
INSERT INTO `procurement_rules` VALUES (15,'TY-D-F00','FLAT',0.00,'FLAT',100.00,1,'2026-03-18 09:42:24','2026-03-18 09:42:24'),(16,'TY-DF10-P25','FLAT',10.00,'PERCENTAGE',125.00,1,'2026-03-18 09:43:02','2026-03-20 20:19:47'),(17,'TY-DP15-MP35','PERCENTAGE',15.00,'PERCENTAGE',135.00,1,'2026-03-18 09:43:25','2026-03-20 20:20:21'),(18,'TY-DP25-MP55','PERCENTAGE',25.00,'PERCENTAGE',155.00,1,'2026-03-18 09:43:47','2026-03-20 20:20:53'),(19,'EL-D-P00','FLAT',0.00,'PERCENTAGE',100.00,1,'2026-03-18 09:44:11','2026-03-18 09:44:11'),(20,'EL-DP10-MP50','PERCENTAGE',10.00,'PERCENTAGE',150.00,1,'2026-03-18 09:44:54','2026-03-18 09:44:54'),(21,'EL-DP20-MP80','PERCENTAGE',20.00,'PERCENTAGE',180.00,1,'2026-03-18 09:45:15','2026-03-20 19:53:32'),(22,'DR-DP20-MP50','PERCENTAGE',20.00,'PERCENTAGE',150.00,1,'2026-03-18 09:46:07','2026-03-18 09:46:07'),(23,'TY-DP25-MP80','PERCENTAGE',25.00,'PERCENTAGE',180.00,1,'2026-03-18 09:46:27','2026-03-20 19:54:07'),(24,'DR-DP25-MP80','PERCENTAGE',25.00,'PERCENTAGE',180.00,1,'2026-03-20 14:26:20','2026-03-20 14:26:20');
/*!40000 ALTER TABLE `procurement_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image_url` text COLLATE utf8mb4_unicode_ci,
  `unit` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PCS',
  `is_public` tinyint(1) NOT NULL DEFAULT '1',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_products_sku` (`sku`),
  UNIQUE KEY `uq_products_slug` (`slug`),
  KEY `idx_products_public_active` (`is_public`,`is_active`),
  KEY `idx_products_name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (4,'DRN-ST-E88-PRO','DRONE E88 PRO','drn-e88-pro','E88 Pro Foldable HD Camera Drone\r\nExperience the excitement of aerial photography with the E88 Pro Foldable Drone. Designed for beginners and hobbyists, this compact drone combines HD imaging, dual cameras, and a foldable design to deliver a fun and easy flying experience. Whether you\'re capturing photos, recording videos, or simply enjoying flight control, the E88 Pro offers a great balance of performance and portability.\r\n\r\nKey Features\r\n- HD Lens Camera\r\nCapture clear aerial photos and videos with the built-in HD camera, perfect for recording your adventures from the sky.\r\n\r\n- Dual Camera System\r\nSwitch between cameras to enjoy different viewing angles and enhance your flying experience.\r\n\r\n- Real-Time Transmission\r\nView live footage directly on your smartphone through the mobile app connection.\r\n\r\n- Foldable & Portable Design\r\nThe foldable arms make the drone compact and easy to carry, ideal for travel and outdoor activities.\r\n\r\n- Cool LED Lights\r\nBright LED lights help with visibility during flight, especially in low-light environments.\r\n\r\n- Photo & Video Recording\r\nTake photos and record videos easily while flying using the mobile app control system.\r\n\r\n- Large Battery Capacity\r\nEquipped with a high-capacity battery to provide longer flight sessions for more fun.\r\n\r\n- APP Remote Control\r\nControl the drone through a dedicated mobile app, making flight navigation simple and interactive.\r\n\r\nSpecifications\r\n- Model: E88 Pro Drone\r\n- Camera: HD Dual Camera\r\n- Frequency: 2.4GHz\r\n- Control Method: Mobile App + Remote Control\r\n- Design: Foldable Drone\r\n- Recommended Age: 14+','assets/products/cdb490ac7dde3068.png|assets/products/8b121ae867056979.png|assets/products/3852842ef0222527.png','PCS',1,1,'2026-03-10 13:06:07','2026-03-11 15:54:52'),(5,'DRN-ST-E99-PRO','DRONE E99-PRO','drn-e99-pro','Experience aerial photography and fun flying with the E99 Pro Foldable Drone. Designed for beginners and enthusiasts, this compact drone features a 4K HD camera, brushless motor, and advanced hover technology for stable and smooth flight.\r\n\r\nIts foldable design makes it portable and easy to carry, while smart flight functions allow effortless control through your smartphone.\r\n\r\nWhether you\'re capturing photos, recording videos, or simply enjoying drone flying, the E99 Pro delivers performance, stability, and convenience.\r\n\r\nKey Features\r\n- 4K HD Camera\r\nCapture stunning aerial photos and videos with the built-in 4K HD camera. Perfect for travel, outdoor adventures, and creative photography.\r\n\r\n- Brushless Motor Technology\r\nThe advanced brushless motor system provides:\r\nMore power, Less noise, Longer motor lifespan, Smoother flight stability\r\n\r\n- Foldable & Portable Design\r\nThe drone folds into a compact size, making it easy to carry in bags or backpacks. Perfect for travel and outdoor flying.\r\n\r\n- Smart App Control\r\nControl the drone directly from your smartphone using the APP control system. Enjoy smart flight modes and easy navigation.\r\n\r\n- 360° Flip & Roll\r\nPerform exciting aerial tricks with one-key 360° flips and rolls, adding fun and creativity to your flying experience.\r\n\r\n- Altitude Hold (Air Pressure Fixed Height)\r\nThe altitude hold feature keeps the drone hovering steadily at a fixed height, making it easier for beginners to control.\r\n\r\n- Long Battery Life\r\nHigh-capacity battery ensures longer flight time so you can enjoy extended flying sessions.','assets/products/9c45ace830901685.png|assets/products/beea61ef15315491.png|assets/products/7a272fc54cddaa0a.png|assets/products/e0048c37a69ab61b.png','PCS',1,1,'2026-03-10 13:38:48','2026-03-11 15:54:58'),(6,'ELC-RT-BOSE-Q-C-U-H-DRIFTWOOD-SAND','Bose QuietComfort Ultra headphones - Driftwood Sand - Limited Edition','ELC-BOSE-Q-C-U-H-DRIFTWOOD-SAND','Bose Quiet Comfort Ultra Headphones - Driftwood Sand - Limited Edition\r\n\r\n- Premium Noise Cancelling Wireless Headphones\r\nExperience immersive sound and next-level comfort with the Bose QuietComfort Ultra Headphones. Designed for music lovers and professionals, these headphones deliver powerful audio, industry-leading noise cancellation, and a premium ergonomic design for long listening sessions.\r\n\r\nKey Features\r\n- Advanced Noise Cancellation\r\nBlock out unwanted noise and focus on your music, calls, or work with Bose’s powerful active noise cancelling technology.\r\n\r\n- Immersive Premium Sound\r\nEnjoy deep bass, clear vocals, and detailed instrument separation for an immersive listening experience.\r\n\r\n- Comfortable All-Day Design\r\nSoft ear cushions and a lightweight headband provide superior comfort even during long listening sessions.\r\n\r\n- Wireless Bluetooth Connectivity\r\nSeamlessly connect your headphones to smartphones, tablets, and laptops using reliable Bluetooth wireless technology.\r\n\r\n- Cross-Platform Compatibility\r\nWorks smoothly with: iPhone, iPad, Android devices, \r\n\r\n- Snapdragon Sound Support\r\nOptimized audio quality with enhanced wireless performance for supported devices.\r\n\r\n- Long Battery Life\r\nLarge battery capacity ensures extended listening time for music, calls, and entertainment.','assets/products/df4a94b43256610b.png|assets/products/9a26479cf2874969.png','PCS',1,1,'2026-03-10 14:08:58','2026-03-12 06:43:28'),(7,'DRN-ST-J2','DRONEJ2','drn-j2','J2 Smart Drone with Dual Camera & Obstacle Avoidance\r\n\r\nExperience aerial photography and fun flying with the J2 Smart Drone. Designed for beginners and hobbyists, this compact drone features 360° obstacle avoidance, dual cameras, optical flow positioning, and intelligent flight controls for a smooth and stable flying experience.\r\n\r\nPerfect for capturing photos, recording videos, and enjoying an exciting drone flight experience.\r\n\r\nKey Features\r\n\r\n- 360° Obstacle Avoidance\r\nBuilt-in obstacle detection helps the drone avoid collisions while flying, making it safer and easier to control.\r\n\r\n- Dual Camera System\r\nCapture stunning aerial photos and videos with the dual HD cameras that allow you to switch perspectives while flying.\r\n\r\n- Optical Flow Positioning\r\nAdvanced optical flow technology keeps the drone stable and balanced even when hovering indoors or outdoors.\r\n\r\n- Intelligent Flight Control\r\nSmart flight stabilization ensures smooth flying even for beginners.\r\n\r\n- Electric Adjustment Camera\r\nAdjust the camera angle remotely to capture the perfect aerial view.\r\n\r\n- Modular Battery Design\r\nThe removable battery design allows quick replacement for extended flight sessions.\r\n\r\n- Headless Mode\r\nControl the drone easily without worrying about orientation.\r\n\r\n- Cool LED Lighting\r\nBright LED lights improve visibility during night flying and add a stylish look.','assets/products/3489ab5c52592d7d.png|assets/products/436038a8b6c6eb8e.png|assets/products/50ae1092732ebacf.png','PCS',1,1,'2026-03-10 15:45:34','2026-03-11 15:55:08'),(8,'ELC-RT-BOSE-Q-C-U-H-CHILLED-LILAC','Bose QuietComfort Ultra headphones Chilled Lilac - Limited Edition','elc-BOSE-Q-C-U-H-CHILLED-LILAC','Bose QuietComfort Ultra Headphones - Chilled Lilac - Limited Edition\r\n\r\n- Premium Noise Cancelling Wireless Headphones\r\nExperience immersive sound and next-level comfort with the Bose QuietComfort Ultra Headphones. Designed for music lovers and professionals, these headphones deliver powerful audio, industry-leading noise cancellation, and a premium ergonomic design for long listening sessions.\r\n\r\nKey Features\r\n- Advanced Noise Cancellation\r\nBlock out unwanted noise and focus on your music, calls, or work with Bose’s powerful active noise cancelling technology.\r\n\r\n- Immersive Premium Sound\r\nEnjoy deep bass, clear vocals, and detailed instrument separation for an immersive listening experience.\r\n\r\n- Comfortable All-Day Design\r\nSoft ear cushions and a lightweight headband provide superior comfort even during long listening sessions.\r\n\r\n- Wireless Bluetooth Connectivity\r\nSeamlessly connect your headphones to smartphones, tablets, and laptops using reliable Bluetooth wireless technology.\r\n\r\n- Cross-Platform Compatibility\r\nWorks smoothly with: iPhone, iPad, Android devices, \r\n\r\n- Snapdragon Sound Support\r\nOptimized audio quality with enhanced wireless performance for supported devices.\r\n\r\n- Long Battery Life\r\nLarge battery capacity ensures extended listening time for music, calls, and entertainment.',NULL,'PCS',1,1,'2026-03-11 15:54:34','2026-03-12 06:46:32'),(9,'ELC-RT-APPLE-MAX-GOLD','APPLE MAX - GOLD ','ELC-RT-APPLE-MAX-GOLD','Apple AirPods Max\r\n\r\nPremium Over-Ear Wireless Headphones\r\n\r\nExperience high-fidelity sound and advanced noise cancellation with Apple AirPods Max. Designed with precision-engineered drivers and Apple’s powerful audio processing, these headphones deliver an immersive listening experience with exceptional comfort and premium build quality.\r\n\r\nKey Features\r\n- Active Noise Cancellation\r\nAdvanced Active Noise Cancellation (ANC) blocks external noise so you can fully focus on music, calls, or videos.\r\n\r\n- Transparency Mode\r\nQuickly switch to transparency mode to hear surroundings without removing the headphones.\r\n\r\n- High-Fidelity Audio\r\nCustom acoustic design with Apple-engineered drivers delivers deep bass, accurate mids, and crisp highs.\r\n\r\n- Spatial Audio with Dynamic Head Tracking\r\nEnjoy theater-like surround sound when watching movies or listening to compatible content.\r\n\r\n- Premium Design & Comfort\r\nStainless steel frame\r\nBreathable knit mesh canopy\r\nMemory foam ear cushions for long listening comfort\r\nSeamless Apple Ecosystem\r\nInstant pairing and switching across Apple devices such as: iPhone, iPad,Mac\r\n\r\n- Long Battery Life\r\nUp to 20 hours of listening time with Active Noise Cancellation and Spatial Audio enabled.','assets/products/d24919fc58fb22c3.jpg|assets/products/a9e187ff73139885.jpg|assets/products/3fd946b0a128b632.jpg','PCS',1,1,'2026-03-12 06:59:44','2026-03-12 07:00:07'),(10,'ELC-RT-BS07-RGB-WL-SPK-BLK','BS-07 WIRELESS SPEAKER - BLACK','ELC-RT-BS07-RGB-WL-SPK-BLK','BS-07 Mini Portable Wireless Bluetooth Speaker\r\nCompact 5W portable Bluetooth speaker with RGB lighting and a carabiner clip for easy carrying. Designed for outdoor use, promotions, and everyday music playback.\r\n\r\nKey Features\r\n- Bluetooth wireless connectivity\r\n- 5W powerful speaker output\r\n- RGB colorful LED lighting\r\nBuilt-in rechargeable battery\r\n- Water-resistant design\r\n- Portable with carabiner clip\r\n- Supports TF card playback\r\n- Built-in microphone for calls\r\n- Lightweight and travel-friendly',NULL,'PCS',1,1,'2026-03-12 08:31:55','2026-03-12 08:31:55'),(11,'ELC-RT-BS07-RGB-WL-SPK-CAMOUFLAGE','BS-07 WIRELESS SPEAKER - CAMOUFLAGE','ELC-RT-BS07-RGB-WL-SPK-CAMOUFLAGE','BS-07 Mini Portable Wireless Bluetooth Speaker\r\nCompact 5W portable Bluetooth speaker with RGB lighting and a carabiner clip for easy carrying. Designed for outdoor use, promotions, and everyday music playback.\r\n\r\nKey Features\r\n- Bluetooth wireless connectivity\r\n- 5W powerful speaker output\r\n- RGB colorful LED lighting\r\nBuilt-in rechargeable battery\r\n- Water-resistant design\r\n- Portable with carabiner clip\r\n- Supports TF card playback\r\n- Built-in microphone for calls\r\n- Lightweight and travel-friendly',NULL,'PCS',1,1,'2026-03-12 08:35:24','2026-03-12 08:35:24'),(12,'ELC-RT-APPLE-EARPODS-PRO','APPLE EARPODS PRO','ELC-RT-APPLE-EARPODS-PRO','AirPods Pro – Wireless Active Noise Cancelling Earbuds\r\nPremium true wireless earbuds with Active Noise Cancellation (ANC), Transparency Mode, and spatial audio. Designed for immersive sound, clear calls, and seamless connectivity with Apple devices.\r\n\r\nKey Features\r\n- Active Noise Cancellation (ANC)\r\n- Transparency Mode\r\n- Adaptive EQ\r\n- Spatial Audio with dynamic head tracking\r\n- Touch control on stems\r\n- MagSafe wireless charging case\r\n- Sweat and water resistant (IPX4)\r\n- Bluetooth connectivity\r\n- Silicone ear tips for comfortable fit','assets/products/23a97e0b1277ae05.webp|assets/products/a642a28508e46317.jpg|assets/products/63e75b826b08a138.webp|assets/products/c0df53468be1997a.jpg','PCS',1,1,'2026-03-12 09:17:48','2026-03-18 12:45:53'),(13,'ELC-RT-4IN1-CABLE','4 IN 1 - FAST CHARGING CABLE','ELC-RT-4IN1-CABLE','4-in-1 Fast Charging Data Cable – Multi Connector USB Charging Cable\r\n\r\nKey Features\r\n- 4-in-1 Multi Connector Cable\r\nSupports multiple connectors in one cable, making it compatible with many devices.\r\n\r\n- Fast Charging Support\r\nOptimized for high-speed charging and stable data transmission.\r\n\r\n- Durable Braided Design\r\nStrong braided cable construction increases durability and prevents tangling.\r\n\r\n- High Compatibility\r\nCompatible with smartphones, tablets, power banks, and other USB devices.\r\n\r\n- High-Speed Data Transmission\r\nEnsures efficient charging + data transfer simultaneously.','assets/products/336e44abb4356c97.png|assets/products/4a72fd98499577b3.png','PCS',1,1,'2026-03-12 09:53:26','2026-03-12 09:53:26'),(14,'ELC-RT-APPLE-WATCH-9','APPLE WATCH 9','ELC-RT-APPLE-WATCH-9','Apple Watch Series 9\r\nAdvanced Smartwatch with Powerful Health & Fitness Features\r\nThe Apple Watch Series 9 is designed to help you stay connected, active, and healthy. Powered by the new S9 chip, it delivers faster performance, improved Siri functionality, and advanced health tracking features in a sleek and durable design.\r\n\r\nKey Features\r\n- Brighter Always-On Retina Display\r\nThe display reaches up to 2000 nits brightness, making it easy to read even in bright sunlight.\r\n\r\n- Double Tap Gesture\r\nA new gesture control lets you answer calls, pause music, or open notifications using just your fingers.\r\n\r\n- Advanced Health Monitoring\r\nTrack your health with powerful sensors that monitor:\r\n\r\n- Heart Rate\r\nBlood Oxygen, Sleep patterns, Activity levels, Fitness Tracking, Stay motivated with comprehensive workout tracking including running, cycling, swimming, yoga, and more, Seamless Apple Ecosystem Integration\r\n\r\n- Durable & Stylish Design\r\nBuilt with premium materials and water resistance, perfect for everyday use and workouts.','assets/products/81bb3e756de488ce.jpg|assets/products/f7b3309b8771309a.png|assets/products/f54dddb0a5dd4686.webp|assets/products/0254f07d34e14a88.webp|assets/products/6e2c30df41369161.webp|assets/products/3fcf9b8fd3ff0526.webp','PCS',1,1,'2026-03-12 10:26:14','2026-03-12 10:26:14'),(15,'ELC-RT-APPLE-CHARGER','APPLE 20W USB-C POWER ADAPTER','ELC-RT-APPLE-CHARGER','Apple 20W USB-C Power Adapter\r\n\r\nFast Charging Power Adapter for iPhone & iPad\r\nThe Apple 20W USB-C Power Adapter provides fast and efficient charging at home, in the office, or on the go. Designed by Apple, it delivers reliable power output and works seamlessly with compatible iPhone and iPad devices.\r\n\r\nKey Features\r\n- Fast Charging Support\r\nDelivers up to 20W power output, allowing compatible iPhones to charge up to 50% in about 30 minutes when used with a USB-C to Lightning cable.\r\n\r\n- USB-C Output\r\nEquipped with a USB-C port that supports modern fast-charging standards.\r\n\r\n- Compact & Portable Design\r\nSmall and lightweight design makes it easy to carry for travel and everyday use.\r\n\r\n- Apple Certified Performance\r\nEngineered by Apple to provide safe and stable charging for Apple devices.\r\n\r\n- Universal Compatibility\r\nWorks with a wide range of Apple products including: iPhone 15 / 14 / 13 / 12 series, iPhone 11 / XS / XR / X, iPad Pro, iPad Air, iPad mini','assets/products/fbd2b7b8068cb717.png|assets/products/8c1fa8c63d585708.png','PCS',1,1,'2026-03-12 11:12:00','2026-03-12 11:12:00'),(16,'ELC-RT-APPLE-MAGSAFE','APPLE MAGSAFE CHARGER','ELC-RT-APPLE-MAGSAFE','Magnetic Wireless Charging for iPhone\r\nThe Apple MagSafe Charger delivers convenient wireless charging with perfectly aligned magnets designed for compatible iPhone models. Simply place your iPhone on the charger and it automatically snaps into place for efficient charging.\r\n\r\nKey Features\r\n- Magnetic Alignment\r\nBuilt-in magnets automatically align with MagSafe-compatible iPhones, ensuring optimal charging every time.\r\n\r\n- Fast Wireless Charging\r\nSupports up to 15W wireless charging when paired with a compatible 20W USB-C power adapter.\r\n\r\n- USB-C Connectivity\r\nEquipped with a USB-C cable for stable and reliable power delivery.\r\n\r\n- Slim & Premium Design\r\nCompact circular charging pad with Apple’s minimalist design, perfect for desks, bedside tables, or travel.\r\n\r\n- Easy Snap-On Charging\r\nJust place the charger on the back of your iPhone and it magnetically locks into place.','assets/products/3f675ff0a6930881.jpeg|assets/products/2d7a966fbd27b641.jpeg|assets/products/e9b23718aa70262e.jpg','PCS',1,1,'2026-03-12 11:46:12','2026-03-12 11:46:12'),(17,'ELC-RT-APPLE-EARPODS','APPLE MAGSAFE EARPODS','ELC-RT-APPLE-EARPODS','Apple EarPods with Lightning Connector\r\nExperience high-quality audio and a comfortable fit with Apple EarPods featuring a Lightning connector. Designed to match the geometry of the ear, these EarPods deliver clear sound, powerful bass, and convenient controls for music and calls.\r\n\r\nKey Features\r\n- Lightning Connector\r\nConnects directly to iPhones and iPads with a Lightning port for seamless compatibility.\r\n\r\n- High-Quality Audio\r\nEngineered by Apple to provide rich bass, clear mids, and crisp highs for music, videos, and calls.\r\n\r\n- Ergonomic Design\r\nUnlike traditional circular earbuds, EarPods are shaped to fit the ear comfortably for longer listening sessions.\r\n\r\n- Built-in Remote\r\nControl your device without taking it out of your pocket.\r\n\r\nYou can: Adjust volume, Control music and video playback, Answer or end calls, Integrated Microphone, Allows clear voice calls and voice commands.','assets/products/6215bd4b3820bfc5.png|assets/products/dc282280a8c30a1b.png','PCS',1,1,'2026-03-12 12:02:06','2026-03-12 12:02:06'),(18,'ELC-RT-CABLE-PROTECTOR','CABLE PROTECTOR','ELC-RT-CABLE-PROTECTOR','CABLE-PROTECTOR','assets/products/6d054f474d094d6a.jpg|assets/products/f7bafa6e22913211.webp|assets/products/ba2e0948e2e5387c.webp','PCS',1,1,'2026-03-12 15:40:18','2026-03-12 15:40:18'),(19,'ELC-RT-WASP-FEELERS','WASP FEELERS','ELC-RT-WASP-FEELERS','WASP FEELERS','assets/products/870ecf9e4c4ea545.webp|assets/products/7e46e2241d229b7a.webp','PCS',1,1,'2026-03-12 16:27:30','2026-03-12 16:28:11'),(20,'TY-RAF-DIECAST-CAR-1:16','DIECAST CAR SERIES - 1:64','TY-RAF-DIECAST-CAR-1:16','High quality metal diecst car ','assets/products/0b5fed11bab4b273.png','PCS',1,1,'2026-03-13 09:07:59','2026-03-13 09:15:13'),(21,'TY-RAF-ROYAL-ENFIELD','ROYAL ENFIELD - 1:15','TY-RAF-ROYAL-ENFIELD','Royal Enfiled Bike ','assets/products/7cf1753dbabc3ad9.png|assets/products/c6086355348fae89.png|assets/products/82fbe9763873a9cb.png|assets/products/e2c867d92c2151c4.jpg','PCS',1,1,'2026-03-13 09:24:14','2026-03-13 09:24:14'),(22,'TY-RAF-LJX-CAR','LJX - PAGANI HUAYRA METAL 1:32 SCALE CAR','TY-RAF-LJX-CAR','Metal Car','assets/products/5dac02446adfd679.png|assets/products/c2b7175ff5f34c36.png','PCS',1,1,'2026-03-13 09:37:30','2026-03-13 09:37:30'),(23,'TY-RAF-MERCEDES-BENZ-CAR','MERCEDES BENZ CAR - 1:32','TY-RAF-MERCEDES-BENZ-CAR','MERCEDES BENZ CAR - 1:32 (metal)','assets/products/e1ea440575bc6a68.png|assets/products/fcee5e971c08af44.png','PCS',1,1,'2026-03-13 09:53:57','2026-03-13 09:53:57'),(24,'TY-RAF-MC5-DIECAST-SMOKE-CAR','MC5 DIECAST SMOKE CAR','TY-RAF-MC5-DIECAST-SMOKE-CAR','MC5 DIECAST SMOKE CAR','assets/products/0287073bfaf5800a.png|assets/products/24009b3f6467726a.png','PCS',1,1,'2026-03-13 10:11:12','2026-03-13 10:11:12'),(25,'TY-RAF-METAL-THAR','METAL THAR CAR - 1:32','TY-RAF-METAL-THAR',NULL,'assets/products/b08e798f17e4df98.png|assets/products/e9c8c95b606390cb.png|assets/products/96df5a6d0e44ae4f.png|assets/products/0db170345a4af5e3.png','PCS',1,1,'2026-03-13 10:32:41','2026-03-13 10:32:41'),(26,'TY-RAF-RRTT-RFHL','RC EXQUISITE CAR MODELS - DIE CAST','TY-RAF-RRTT-RFHL',NULL,NULL,'PCS',1,1,'2026-03-13 10:58:51','2026-03-13 10:58:51'),(28,'TY-RAF-LLT-EMT','RC THAR - DUAL SPAY - OPENABLE DOOR','TY-RAF-LLT-EMT',NULL,NULL,'PCS',1,1,'2026-03-13 11:04:52','2026-03-13 11:04:52'),(29,'TY-RAF-AZL-FRT','SUPER QLU - BSF JEEP','TY-RAF-AZL-FRT',NULL,NULL,'PCS',1,1,'2026-03-13 11:07:35','2026-03-13 11:07:35'),(30,'TY-RAF-CITY-FIRE-ENGINE','MAAZ - CITY FIRE ENGINE','TY-RAF-CITY-FIRE-ENGINE',NULL,NULL,'PCS',1,1,'2026-03-13 11:11:02','2026-03-13 11:11:02'),(32,'TY-RAF-RRFT-RZAT','MOKA - REMOTE DOOR TO OPEN','TY-RAF-RRFT-RZAT',NULL,NULL,'PCS',1,1,'2026-03-13 11:17:25','2026-03-13 11:17:25'),(33,'TY-RAF-AFL-AML','RAMBO CAR TRAILER','TY-RAF-AFL-AML',NULL,NULL,'PCS',1,1,'2026-03-13 11:21:37','2026-03-13 11:21:37'),(34,'TY-RAF-LFT-EEL','4WD OFF ROAD - THAR','TY-RAF-LFT-EEL',NULL,NULL,'PCS',1,1,'2026-03-13 11:25:07','2026-03-13 11:25:07'),(35,'TY-RAF-AIR','HINDAL CEMENT MIXXER','TY-RAF-AIR',NULL,NULL,'PCS',1,1,'2026-03-13 11:31:29','2026-03-13 11:31:29'),(36,'TY-RAF-EAT-HHL','RC OFF ROAD SPRAY DINOSAUR CAR','TY-RAF-EAT-HHL',NULL,'assets/products/317a17ac139f94e9.jpg|assets/products/931c6413c2ec086b.jpg','PCS',1,1,'2026-03-15 07:29:05','2026-03-15 07:29:05'),(37,'TY-RAF-RATT-RLTT','EIGHT WHEELED ROBOT DOG','TY-RAF-RATT-RLTT',NULL,'assets/products/f28ab4104f0e1631.jpg','PCS',1,1,'2026-03-15 07:38:43','2026-03-15 07:38:43'),(39,'TY-RAF-ZLT-LEL','4WD CRAWLER STUNT CAR','TY-RAF-ZLT-LEL',NULL,NULL,'PCS',1,1,'2026-03-15 07:46:27','2026-03-15 07:46:27'),(40,'TY-RAF-RATT1-RLTT1','MULTIFUNCTION RACING TUNT (HAND SENSOR)','TY-RAF-RATT1-RLTT1',NULL,NULL,'PCS',1,1,'2026-03-15 07:52:30','2026-03-15 07:52:30'),(41,'TY-RAF-LAT-EL','SOMERSAULT STUNT CAR - ROTATING TYRES','TY-RAF-LAT-EL',NULL,'assets/products/f75f9ef3fb634a76.jpg','PCS',1,1,'2026-03-15 08:12:40','2026-03-15 08:12:40'),(42,'TY-RAF-EZT-IT','POLICE MOTORCYCLE - SIMULATION','TY-RAF-EZT-IT',NULL,NULL,'PCS',1,1,'2026-03-15 08:22:34','2026-03-15 08:22:34'),(43,'TY-RAF-LIL-EL','INERTIA SLIDE CAR','TY-RAF-LIL-EL',NULL,'assets/products/6a88bf2da49a40a0.jpg','PCS',1,1,'2026-03-15 08:27:32','2026-03-15 08:27:32'),(44,'TY-RAF-RAL-RLL','INDUCTION FLIGHT (HELICOPTER)','TY-RAF-RAL-RLL',NULL,NULL,'PCS',1,1,'2026-03-15 08:31:33','2026-03-15 08:31:33'),(46,'TY-RAF-RTFT-RAMT','COMBAT FLIGHT - DRONE THUNDER','TY-RAF-RTFT-RAMT',NULL,NULL,'PCS',1,1,'2026-03-15 08:38:58','2026-03-15 08:38:58'),(47,'TY-RAF-RAL','JUNIOR EDUCATIONAL ABACUS ','TY-RAF-RAL',NULL,NULL,'PCS',1,1,'2026-03-15 08:53:08','2026-03-15 08:53:08'),(48,'TY-RAF-ITT-RTTT','DIY INSTANT DIGITAL CAMERA','TY-RAF-ITT-RTTT',NULL,NULL,'PCS',1,1,'2026-03-15 09:05:40','2026-03-15 09:05:40'),(49,'TY-RAF-AAL-AIT','AUDIORA MUSIC PARTY','TY-RAF-AAL-AIT',NULL,NULL,'PCS',1,1,'2026-03-15 09:11:25','2026-03-15 09:11:25'),(50,'TY-RAF-HT-MT','LED DRAWING PEN CASE','TY-RAF-HT-MT',NULL,'assets/products/4e6e9326c8d5a3cf.jpg','PCS',1,1,'2026-03-17 08:27:14','2026-03-17 08:27:14'),(51,'TY-RAF-JBE','Jewellery botique (EKTA)','TY-RAF-JBE',NULL,'assets/products/26a3b6ef8e2da606.png','PCS',1,1,'2026-03-17 08:35:49','2026-03-17 08:35:49'),(52,'TY-RAF-RAH-RET','Jewellery designer (EKTA)','TY-RAF-RAH-RET',NULL,'assets/products/168edb0a3cdfe45e.png','PCS',1,1,'2026-03-17 08:38:38','2026-03-17 08:38:38'),(53,'TY-RAF-AMF','5in1 Party pack (EKTA)','TY-RAF-AMF',NULL,'assets/products/000ce53997e9838c.jpg','PCS',1,1,'2026-03-17 08:44:04','2026-03-17 08:44:04'),(54,'TY-RAF-MLU','MY LITTLE UNICORN 3PC','TY-RAF-MLU',NULL,'assets/products/06ee91c5dd0f90eb.jpg','PCS',1,1,'2026-03-17 08:48:56','2026-03-17 08:48:56'),(55,'TY-RAF-RRL-RZL','ELECTRIC CRAB','TY-RAF-RRL-RZL',NULL,'assets/products/4c2d22de483a1c53.png','PCS',1,1,'2026-03-17 08:54:45','2026-03-17 08:54:45'),(56,'TY-RAF-ML','CLASSIC DOCTOR - PLAY SET','TY-RAF-ML',NULL,NULL,'PCS',1,1,'2026-03-17 09:02:03','2026-03-17 09:02:03'),(57,'TY-RAF-AHT',' 6 IN 1 SOLOR ENERGY SERIES-1 (ANNIE)','TY-RAF-AHT',NULL,'assets/products/c56a7f02e33d1a7d.png','PCS',1,1,'2026-03-17 09:05:11','2026-03-17 09:05:11'),(58,'TY-RAF-AIT','The young scientist-1 (EKTA)','TY-RAF-AIT',NULL,'assets/products/d4164c43bb33e0bd.jpg','PCS',1,1,'2026-03-17 09:10:07','2026-03-17 09:10:07'),(59,'TY-RAF-LIL','14 IN 1 EDUCATIONAL SOLAR ROBOT','TY-RAF-LIL',NULL,'assets/products/f0635df9eb3a5b9d.png','PCS',1,1,'2026-03-17 09:17:08','2026-03-17 09:17:08'),(60,'TY-RAF-ILL-RTHT','MECHANIX - ENGINEERING SYSTEM FOR CREATIVE KIDS','TY-RAF-ILL-RTHT',NULL,NULL,'PCS',1,1,'2026-03-17 09:21:46','2026-03-17 09:21:46'),(61,'TY-RAF-RHT-ARL','PANDA TOUCH LAMP (RECHARGABLE)','TY-RAF-RHT-ARL',NULL,'assets/products/405bef442c63eccd.jpg','PCS',1,1,'2026-03-17 09:39:49','2026-03-17 09:39:49'),(62,'TY-RAF-RZL','MEMORY CHESS ','TY-RAF-RZL',NULL,'assets/products/269ada96905026bb.jpg','PCS',1,1,'2026-03-17 09:45:19','2026-03-17 09:45:19'),(63,'TY-RAF-AI-TTT','AI INTELLIGENCE TIC TAC TOE','TY-RAF-AI-TTT',NULL,'assets/products/ff88bdcef5209956.jpg','PCS',1,1,'2026-03-17 09:49:52','2026-03-17 09:49:52'),(64,'TY-RAF-SC','SPEED CUBE - 490','TY-RAF-SC',NULL,NULL,'PCS',1,1,'2026-03-17 09:53:43','2026-03-17 09:53:43'),(65,'TY-RAF-MBL','MONEY BANK LABUBU','TY-RAF-MBL',NULL,NULL,'PCS',1,1,'2026-03-17 09:57:47','2026-03-17 09:57:47'),(66,'TY-RAF-LZT-EHL','FROGGY DOCTOR SET','TY-RAF-LZT-EHL',NULL,NULL,'PCS',1,1,'2026-03-17 10:00:25','2026-03-17 10:00:25'),(67,'TY-RAF-SM','SONU MONU','TY-RAF-SM',NULL,NULL,'PCS',1,1,'2026-03-17 10:08:50','2026-03-17 10:08:50'),(68,'TY-RAF-FRT','DOLL AHNNA','TY-RAF-FRT',NULL,NULL,'PCS',1,1,'2026-03-17 10:12:16','2026-03-17 10:12:16'),(69,'TY-RAF-AAL-AMT','DOLL KHUSHI','TY-RAF-AAL-AMT',NULL,NULL,'PCS',1,1,'2026-03-17 10:13:51','2026-03-17 10:13:51'),(70,'TY-RAF-RZL-RIL','DOLL LILY','TY-RAF-RZL-RIL',NULL,NULL,'PCS',1,1,'2026-03-17 10:15:53','2026-03-17 10:15:53'),(71,'TY-RAF-EFT','PRINCE BOY','TY-RAF-EFT',NULL,NULL,'PCS',1,1,'2026-03-18 07:06:00','2026-03-18 07:06:00'),(73,'TY-RAF-ART-AEL','KIDS KITCHEN ATTACHI SET (ANGAA)','TY-RAF-ART-AEL',NULL,'assets/products/4e257ad7d69b175c.jpeg','PCS',1,1,'2026-03-18 07:10:44','2026-03-18 07:10:44'),(74,'TY-RAF-JAM','JET AIME - MAHI DOLL','TY-RAF-JAM',NULL,'assets/products/4ab664b5353cad2a.jpg','PCS',1,1,'2026-03-18 07:15:16','2026-03-18 07:15:16'),(75,'TY-RAF-LTT-EAL','MAHI DOLL BIG','TY-RAF-LTT-EAL',NULL,'assets/products/a2b8c53c819a2ca0.jpg','PCS',1,1,'2026-03-18 07:18:33','2026-03-18 07:18:33'),(76,'TY-RAF-MM','MANKU MERMAID DOLL','TY-RAF-MM',NULL,NULL,'PCS',1,1,'2026-03-18 07:21:33','2026-03-18 07:21:33'),(77,'TY-RAF-AAL-AIL','OH MY SWEETU','TY-RAF-AAL-AIL',NULL,'assets/products/13c264f6277a9ff2.png|assets/products/fa127640e96461da.png','PCS',1,1,'2026-03-18 07:32:06','2026-03-18 07:32:06'),(78,'TY-RAF-HP','MANKU HAPPY GIRL','TY-RAF-HP',NULL,NULL,'PCS',1,1,'2026-03-18 07:34:47','2026-03-18 07:34:47'),(79,'TY-RAF-RAT-RLT','DIE-CAST MODEL','TY-RAF-RAT-RLT',NULL,'assets/products/5ccfd707f90e3bde.png|assets/products/176fcac27682cd1b.png','PCS',1,1,'2026-03-20 11:27:46','2026-03-20 11:27:46'),(80,'TY-RAF-RZT-RHL','ALLOY TRENDY TOY MODEL','TY-RAF-RZT-RHL',NULL,'assets/products/81f30befa4740eba.jpg|assets/products/29a8a1e2025bc42e.jpg','PCS',1,1,'2026-03-20 11:43:38','2026-03-20 11:43:38'),(81,'TY-RAF-MFT-RRHT','STUNT MOKA FOUR WHEEL DRIVE','TY-RAF-MFT-RRHT',NULL,'assets/products/6fba4aece83b3351.jpg|assets/products/93cff8d39d143e16.jpg','PCS',1,1,'2026-03-20 11:57:08','2026-03-20 11:57:08'),(82,'TY-RAF-ZFT-LZT','ROCK CRAWLER - LIGHTING SPRAY','TY-RAF-ZFT-LZT',NULL,'assets/products/675b655d98dc39cb.jpg|assets/products/5bc6ff4b0fe370a2.jpg','PCS',1,1,'2026-03-20 12:38:08','2026-03-20 12:38:08'),(83,'BEVE-RB-BLU-250','Red Bull Energy Drink | Sugar Free | Energizing & Fizzy','BEVE-RB-BLU-250',NULL,'assets/products/6c6fc1ecf8eaba92.webp|assets/products/be84b94273528de2.webp|assets/products/56718c43aa1dd0eb.webp','PCS',1,1,'2026-03-22 11:18:06','2026-03-30 04:03:24'),(84,'BEVE-RB-PINK-250','Red Bull Energy Drink The Pink Edition','BEVE-RB-PINK-250',NULL,'assets/products/a40b072ced3d55c0.webp|assets/products/6464f4ca47d5d76c.webp|assets/products/75373d001a960715.webp','PCS',1,1,'2026-03-22 12:05:37','2026-03-30 04:03:30'),(85,'BEVE-RB-YELLOW-250','Red Bull Yellow Edition Energy Drink | Energising & Bold','BEVE-RB-YELLOW-250',NULL,'assets/products/18f3a98589a42595.webp|assets/products/c30660031e1b2acc.webp|assets/products/b4bdb7b7bc2a9dbc.webp','PCS',1,1,'2026-03-22 12:08:03','2026-03-30 04:03:42'),(86,'BEVE-RB-Blue-Gray-350','Red Bull Energy Drink | Ready to Drink Beverage','BEVE-RB-Blue-Gray-350',NULL,'assets/products/37b5fb7e16e67cc3.webp|assets/products/1e0af292daeadf36.webp|assets/products/93efa26bc793acc2.webp','PCS',1,1,'2026-03-22 15:04:47','2026-03-30 04:03:17'),(87,'BEVE-MONSTAR-YELLOW-TD-500','Monster-S The Doctor Imported Energy Drink','BEVE-MONSTAR-YELLOW-TD-500',NULL,'assets/products/1e2e98484b1d2b2a.webp|assets/products/cfa6d3d6941b181b.webp|assets/products/b30bee19d98e0ea6.webp','PCS',1,1,'2026-03-22 15:12:25','2026-03-30 04:03:11'),(88,'BEVE-MONSTAR-BLUE-UFM-500','Monster Energy Ultra Fiesta Mango Zero Sugar','BEVE-MONSTAR-BLUE-UFM-500',NULL,'assets/products/837293aca8b54a1f.webp|assets/products/6044e7e7b9f25c5e.webp|assets/products/9e569cdd5fefdfeb.webp','PCS',1,1,'2026-03-22 15:15:59','2026-03-30 04:03:03'),(89,'BEVE-RB-BLACK-GOLD-250','Red Bull Zero Sugar Energy Drink','BEVE-RB-BLACK-GOLD-250',NULL,'assets/products/b2f986bec2c4a1f6.webp|assets/products/51d669a7cd4eb0b7.webp|assets/products/dd1edec16a901abf.webp','PCS',1,1,'2026-03-22 15:31:14','2026-03-30 04:03:48'),(90,'BEVE-RB-GOLD-BLUE-250','Red Bull Kratingdaeng Soft Drink','BEVE-RB-GOLD-BLUE-250',NULL,'assets/products/5137fa20f17a9a0a.webp|assets/products/e51990121d9de2e0.webp|assets/products/2a7f0589d24ec8b1.webp','PCS',1,1,'2026-03-22 15:58:03','2026-03-30 04:03:36');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_batches`
--

DROP TABLE IF EXISTS `stock_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_batches` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `batch_code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `vendor_id` bigint unsigned NOT NULL,
  `purchased_qty` int NOT NULL,
  `remaining_qty` int NOT NULL,
  `unit_cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `received_at` datetime NOT NULL,
  `remarks` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stock_batches_product` (`product_id`),
  KEY `idx_stock_batches_vendor` (`vendor_id`),
  KEY `idx_stock_batches_remaining` (`remaining_qty`),
  CONSTRAINT `fk_stock_batches_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_batches_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_batches`
--

LOCK TABLES `stock_batches` WRITE;
/*!40000 ALTER TABLE `stock_batches` DISABLE KEYS */;
INSERT INTO `stock_batches` VALUES (3,'DEC-25-DEL',6,4,4,4,1260.00,'2025-12-07 21:36:00','Bose Quiet Comfort Ultra Headphones ','2026-03-10 16:07:41','2026-03-20 14:01:49'),(5,'DEC-25-DEL',4,3,2,2,900.00,'2025-12-17 18:27:00','2 Drone e88 pro','2026-03-11 15:07:02','2026-03-18 13:29:41'),(6,'DEC-25-DEL',5,3,1,1,1600.00,'2025-12-17 20:39:00','Drone E99 Pro','2026-03-11 15:09:25','2026-03-20 14:28:21'),(7,'DEC-25-DEL',7,3,1,1,1350.00,'2025-12-17 20:41:00','Drone J2','2026-03-11 15:11:20','2026-03-20 14:27:36'),(8,'DEC-25-DEL',9,4,1,1,1800.00,'2025-12-17 12:35:00','APPLE MAX - GOLD','2026-03-12 07:05:50','2026-03-20 14:01:33'),(9,'DEC-25-DEL',8,4,1,1,1260.00,'2025-12-17 12:40:00','Bose QuietComfort Ultra headphones Chilled Lilac - Limited Edition','2026-03-12 07:10:41','2026-03-18 09:55:06'),(10,'DEC-25-DEL',10,4,1,1,450.00,'2025-12-17 14:09:00','BS - 7 WIRELESS SPEAKER - BLACK','2026-03-12 08:40:04','2026-03-18 09:54:15'),(11,'DEC-25-DEL',11,4,1,1,450.00,'2025-12-17 14:10:00','BS - 07 WIRELESS SPEAKER','2026-03-12 08:41:12','2026-03-18 09:53:50'),(12,'DEC-25-DEL',12,4,2,2,1000.00,'2025-12-17 15:05:00','APPLE EARBUDS - 2ND GEN','2026-03-12 09:35:58','2026-03-18 09:51:56'),(13,'DEC-25-DEL',13,4,2,2,85.00,'2025-12-17 15:24:00','4 IN 1 - FAST CHARGING CABLE','2026-03-12 09:54:49','2026-03-18 09:51:12'),(14,'DEC-25-DEL',14,4,2,2,950.00,'2025-12-17 16:16:00','APPLE WATCH SERIES 9','2026-03-12 10:47:38','2026-03-18 09:50:46'),(15,'DEC-25-DEL',15,4,2,2,450.00,'2025-12-17 16:44:00','APPLE 20W USB- POWER ADAPTER','2026-03-12 11:15:51','2026-03-18 09:50:16'),(16,'DEC-25-DEL',18,4,6,6,10.00,'2025-12-17 21:54:00',NULL,'2026-03-12 16:24:27','2026-03-18 09:48:46'),(17,'DEC-25-DEL',19,4,4,4,10.00,'2025-12-17 21:58:00',NULL,'2026-03-12 16:28:51','2026-03-18 09:48:26'),(18,'DEC-25-DEL',20,5,12,12,90.00,'2025-12-17 14:41:00',NULL,'2026-03-13 09:11:52','2026-03-20 14:54:49'),(19,'DEC-25-DEL',21,5,4,4,415.00,'2025-12-17 14:58:00',NULL,'2026-03-13 09:28:43','2026-03-18 14:58:47'),(20,'DEC-25-DEL',22,5,1,1,410.00,'2025-12-17 15:08:00',NULL,'2026-03-13 09:38:51','2026-03-18 13:22:31'),(21,'DEC-25-DEL',23,5,1,1,410.00,'2025-12-17 15:25:00',NULL,'2026-03-13 09:55:10','2026-03-18 13:30:55'),(22,'DEC-25-DEL',24,5,2,2,170.00,'2025-12-17 15:43:00',NULL,'2026-03-13 10:13:27','2026-03-18 13:25:54'),(23,'DEC-25-DEL',25,5,2,2,110.00,'2025-12-17 16:03:00',NULL,'2026-03-13 10:34:01','2026-03-18 13:15:58'),(24,'DEC-25-DEL',26,5,1,1,1100.00,'2025-12-17 16:31:00',NULL,'2026-03-13 11:01:29','2026-03-18 13:15:29'),(25,'DEC-25-DEL',28,5,1,1,550.00,'2025-12-17 16:35:00',NULL,'2026-03-13 11:05:52','2026-03-18 13:14:27'),(26,'DEC-25-DEL',29,5,1,1,245.00,'2025-12-17 16:38:00',NULL,'2026-03-13 11:08:22','2026-03-18 13:14:04'),(27,'DEC-25-DEL',30,5,1,1,246.00,'2025-12-17 16:41:00',NULL,'2026-03-13 11:11:48','2026-03-18 13:13:36'),(28,'DEC-25-DEL',32,5,1,1,930.00,'2025-12-17 16:47:00',NULL,'2026-03-13 11:18:05','2026-03-18 13:13:08'),(29,'DEC-25-DEL',33,5,1,1,235.00,'2025-12-17 16:52:00',NULL,'2026-03-13 11:22:19','2026-03-18 13:12:50'),(30,'DEC-25-DEL',34,5,1,0,550.00,'2025-12-17 16:55:00',NULL,'2026-03-13 11:25:49','2026-03-30 09:27:05'),(31,'DEC-25-DEL',35,5,1,1,285.00,'2025-12-17 17:02:00',NULL,'2026-03-13 11:32:10','2026-03-18 13:11:21'),(32,'DEC-25-DEL',36,5,1,1,635.00,'2025-12-17 13:00:00',NULL,'2026-03-15 07:30:42','2026-03-18 13:19:57'),(33,'DEC-25-DEL',37,5,1,1,1230.00,'2025-12-17 13:10:00',NULL,'2026-03-15 07:40:56','2026-03-18 13:21:24'),(34,'DEC-25-DEL',39,5,1,0,450.00,'2025-12-17 13:17:00',NULL,'2026-03-15 07:47:18','2026-03-29 09:14:18'),(35,'DEC-25-DEL',40,5,1,1,1230.00,'2025-12-17 13:23:00',NULL,'2026-03-15 07:53:34','2026-03-18 13:22:47'),(36,'DEC-25-DEL',41,5,8,8,66.50,'2025-12-17 13:44:00',NULL,'2026-03-15 08:14:30','2026-03-20 14:59:14'),(37,'DEC-25-DEL',42,5,8,8,82.00,'2025-12-17 13:53:00',NULL,'2026-03-15 08:23:26','2026-03-20 14:58:37'),(38,'DEC-25-DEL',43,5,9,9,67.00,'2025-12-17 13:58:00',NULL,'2026-03-15 08:28:24','2026-03-20 14:57:58'),(39,'DEC-25-DEL',44,5,4,4,128.00,'2025-12-17 14:02:00',NULL,'2026-03-15 08:33:02','2026-03-20 13:16:25'),(40,'DEC-25-DEL',46,5,1,1,1030.00,'2025-12-17 14:09:00',NULL,'2026-03-15 08:39:44','2026-03-20 13:26:10'),(41,'DEC-25-DEL',47,5,2,2,130.00,'2025-12-17 14:24:00',NULL,'2026-03-15 08:54:38','2026-03-20 13:24:48'),(42,'DEC-25-DEL',48,5,1,1,800.00,'2025-12-17 14:36:00',NULL,'2026-03-15 09:06:54','2026-03-20 13:23:36'),(43,'DEC-25-DEL',49,5,1,1,225.00,'2025-12-17 14:42:00',NULL,'2026-03-15 09:12:14','2026-03-20 13:02:52'),(44,'DEC-25-DEL',50,5,1,1,70.00,'2025-12-17 14:00:00',NULL,'2026-03-17 08:30:50','2026-03-20 15:19:55'),(45,'DEC-25-DEL',51,5,1,1,120.00,'2025-12-17 14:06:00',NULL,'2026-03-17 08:36:42','2026-03-20 13:16:50'),(46,'DEC-25-DEL',52,5,1,1,128.00,'2025-12-17 14:09:00',NULL,'2026-03-17 08:39:46','2026-03-20 13:17:14'),(47,'DEC-25-DEL',53,5,1,0,300.00,'2025-12-17 14:15:00',NULL,'2026-03-17 08:45:26','2026-03-30 09:27:05'),(48,'DEC-25-DEL',54,5,2,2,75.00,'2025-12-17 14:19:00',NULL,'2026-03-17 08:49:48','2026-03-20 14:57:21'),(49,'DEC-25-DEL',55,5,1,1,115.00,'2025-12-17 14:27:00',NULL,'2026-03-17 08:57:15','2026-03-20 13:24:31'),(50,'DEC-25-DEL',56,5,1,1,95.00,'2025-12-17 14:32:00',NULL,'2026-03-17 09:02:54','2026-03-20 14:56:52'),(51,'DEC-25-DEL',57,5,1,0,270.00,'2025-12-17 14:35:00',NULL,'2026-03-17 09:05:58','2026-03-30 05:06:29'),(52,'DEC-25-DEL',58,5,1,1,213.00,'2025-12-17 14:41:00',NULL,'2026-03-17 09:11:17','2026-03-20 13:22:48'),(53,'DEC-25-DEL',59,5,1,1,585.00,'2025-12-17 14:47:00',NULL,'2026-03-17 09:17:56','2026-03-20 13:01:33'),(54,'DEC-25-DEL',60,5,1,1,855.00,'2025-12-17 14:52:00',NULL,'2026-03-17 09:22:28','2026-03-20 13:01:14'),(55,'DEC-25-DEL',61,5,1,1,174.00,'2025-12-17 15:10:00',NULL,'2026-03-17 09:40:35','2026-03-18 13:24:51'),(56,'DEC-25-DEL',62,5,1,1,155.00,'2025-12-17 15:16:00',NULL,'2026-03-17 09:46:12','2026-03-18 13:25:27'),(57,'DEC-25-DEL',63,5,2,1,154.00,'2025-12-17 15:20:00',NULL,'2026-03-17 09:50:40','2026-03-30 09:28:09'),(58,'DEC-25-DEL',64,5,2,2,36.00,'2025-12-17 15:24:00',NULL,'2026-03-17 09:54:17','2026-03-18 14:58:17'),(59,'DEC-25-DEL',65,5,1,1,145.00,'2025-12-17 15:28:00',NULL,'2026-03-17 09:58:34','2026-03-18 13:30:23'),(60,'DEC-25-DEL',66,5,1,1,540.00,'2025-12-17 15:30:00',NULL,'2026-03-17 10:01:02','2026-03-18 13:27:01'),(61,'DEC-25-DEL',67,5,2,2,85.00,'2025-12-17 15:39:00',NULL,'2026-03-17 10:09:26','2026-03-20 14:53:36'),(62,'DEC-25-DEL',68,5,1,1,310.00,'2025-12-17 15:42:00',NULL,'2026-03-17 10:12:44','2026-03-18 13:26:13'),(63,'DEC-25-DEL',69,5,1,1,225.00,'2025-12-17 15:44:00',NULL,'2026-03-17 10:14:30','2026-03-18 13:17:20'),(64,'DEC-25-DEL',70,5,1,1,145.00,'2025-12-17 15:46:00',NULL,'2026-03-17 10:16:29','2026-03-18 13:16:45'),(65,'DEC-25-DEL',71,5,1,1,650.00,'2025-12-17 12:36:00',NULL,'2026-03-18 07:06:51','2026-03-18 13:17:46'),(66,'DEC-25-DEL',73,5,1,1,215.00,'2025-12-17 12:41:00',NULL,'2026-03-18 07:11:44','2026-03-18 13:18:15'),(67,'DEC-25-DEL',74,5,1,1,210.00,'2025-12-17 12:45:00',NULL,'2026-03-18 07:16:03','2026-03-18 13:18:45'),(68,'DEC-25-DEL',75,5,1,1,515.00,'2025-12-17 12:49:00',NULL,'2026-03-18 07:19:26','2026-03-18 13:19:03'),(69,'DEC-25-DEL',76,5,1,1,126.00,'2025-12-17 12:52:00',NULL,'2026-03-18 07:22:10','2026-03-18 13:19:32'),(70,'DEC-25-DEL',77,5,1,1,230.00,'2025-12-17 13:02:00',NULL,'2026-03-18 07:32:56','2026-03-18 13:20:29'),(71,'DEC-25-DEL',78,5,1,1,132.00,'2025-12-17 13:06:00',NULL,'2026-03-18 07:37:09','2026-03-18 13:20:55'),(72,'DEC-25-DEL',12,4,2,2,1000.00,'2025-12-17 18:20:00',NULL,'2026-03-18 12:50:30','2026-03-18 12:50:30'),(73,'DEC-25-DEL',16,4,2,2,450.00,'2025-12-17 18:23:00',NULL,'2026-03-18 12:53:49','2026-03-18 12:53:49'),(74,'DEC-25-DEL',17,4,2,2,450.00,'2025-12-18 18:26:00',NULL,'2026-03-18 12:56:26','2026-03-18 12:56:26'),(75,'DEC-25-DEL',79,5,2,1,120.00,'2025-12-17 17:01:00',NULL,'2026-03-20 11:31:24','2026-03-30 09:27:05'),(76,'DEC-25-DEL',80,5,2,2,140.00,'2025-12-17 17:16:00',NULL,'2026-03-20 11:46:11','2026-03-20 11:46:11'),(77,'DEC-25-DEL',81,5,1,1,930.00,'2025-12-17 17:28:00',NULL,'2026-03-20 11:58:34','2026-03-20 11:58:34'),(78,'DEC-25-DEL',82,5,1,1,430.00,'2025-12-17 18:10:00',NULL,'2026-03-20 12:40:11','2026-03-20 12:40:11'),(79,'BEVE-MONSTER1',88,6,1,1,299.00,'2026-04-05 14:39:00',NULL,'2026-04-05 09:09:36','2026-04-05 09:09:36');
/*!40000 ALTER TABLE `stock_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stock_movements`
--

DROP TABLE IF EXISTS `stock_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `batch_id` bigint unsigned DEFAULT NULL,
  `movement_type` enum('PURCHASE','SALE','ADJUSTMENT','RETURN_IN','RETURN_OUT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty_in` int NOT NULL DEFAULT '0',
  `qty_out` int NOT NULL DEFAULT '0',
  `reference_type` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference_id` bigint unsigned DEFAULT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_stock_movements_product` (`product_id`),
  KEY `idx_stock_movements_batch` (`batch_id`),
  KEY `idx_stock_movements_type` (`movement_type`),
  KEY `idx_stock_movements_reference` (`reference_type`,`reference_id`),
  KEY `idx_stock_movements_created_at` (`created_at`),
  CONSTRAINT `fk_stock_movements_batch` FOREIGN KEY (`batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_movements_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
INSERT INTO `stock_movements` VALUES (3,50,44,'SALE',0,1,'order',3,'Order #ORD-20260320-635300','2026-03-20 15:01:03'),(4,39,34,'SALE',0,1,'order',4,'Order #ORD-20260329-3AF0D6','2026-03-29 09:14:18'),(5,57,51,'SALE',0,1,'order',5,'Order #ORD-20260330-561AAB','2026-03-30 05:06:29'),(6,34,30,'SALE',0,1,'order',6,'Order #ORD-20260330-FF5B11','2026-03-30 09:27:05'),(7,53,47,'SALE',0,1,'order',6,'Order #ORD-20260330-FF5B11','2026-03-30 09:27:05'),(8,79,75,'SALE',0,1,'order',6,'Order #ORD-20260330-FF5B11','2026-03-30 09:27:05'),(9,63,57,'SALE',0,1,'order',7,'Order #ORD-20260330-169BCA','2026-03-30 09:28:09');
/*!40000 ALTER TABLE `stock_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_activities`
--

DROP TABLE IF EXISTS `user_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_activities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `module` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entity_id` bigint unsigned DEFAULT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_user_activities_user` (`user_id`),
  KEY `idx_user_activities_module` (`module`),
  KEY `idx_user_activities_action` (`action`),
  KEY `idx_user_activities_entity` (`entity_id`),
  KEY `idx_user_activities_created_at` (`created_at`),
  CONSTRAINT `fk_user_activities_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=139 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_activities`
--

LOCK TABLES `user_activities` WRITE;
/*!40000 ALTER TABLE `user_activities` DISABLE KEYS */;
INSERT INTO `user_activities` VALUES (28,1,'auth','login',1,'Logged in','2026-03-10 05:40:48'),(29,1,'admin','user_create',2,'Created user: chandranathpatra@gmail.com','2026-03-10 05:41:48'),(30,1,'catalog','vendor_create',3,'Created vendor: SAMPOORAN TRADERS','2026-03-10 06:08:48'),(31,1,'catalog','vendor_update',3,'Updated vendor: Drone - SAMPOORAN TRADERS','2026-03-10 06:09:23'),(32,1,'catalog','vendor_create',4,'Created vendor: Electronic - RAIZON TECHNOLOGY','2026-03-10 06:17:33'),(33,1,'auth','login',1,'Logged in','2026-03-10 14:53:25'),(34,1,'catalog','product_update',5,'Updated product: DRONE E99-PRO','2026-03-10 15:40:06'),(35,1,'catalog','product_create',7,'Created product: DRONEJ2','2026-03-10 15:45:34'),(36,1,'inventory','procurement_rule_create',4,'Created procurement rule: BOSE 10','2026-03-10 15:55:42'),(37,1,'inventory','procurement_rule_deactivate',4,'Deactivated procurement rule: BOSE 10','2026-03-10 15:56:41'),(38,1,'inventory','procurement_rule_activate',4,'Activated procurement rule: BOSE 10','2026-03-10 15:56:44'),(39,1,'inventory','procurement_rule_create',5,'Created procurement rule: DRONE 10','2026-03-10 15:58:20'),(40,1,'inventory','batch_create',3,'Created stock batch: DEC-25-DEL','2026-03-10 16:07:41'),(41,1,'auth','login',1,'Logged in','2026-03-29 13:43:04'),(42,1,'admin','user_update',2,'Updated user: chandranathpatra@gmail.com','2026-03-29 13:45:58'),(43,1,'auth','logout',1,'Logged out','2026-03-29 13:46:05'),(44,2,'auth','login',2,'Logged in','2026-03-29 13:46:17'),(45,2,'auth','logout',2,'Logged out','2026-03-29 13:46:35'),(46,1,'auth','login',1,'Logged in','2026-03-29 13:46:37'),(47,1,'catalog','product_update',88,'Updated product: Monster Energy Ultra Fiesta Mango Zero Sugar','2026-03-29 13:57:31'),(48,1,'catalog','product_update',87,'Updated product: Monster-S The Doctor Imported Energy Drink','2026-03-29 13:57:41'),(49,1,'catalog','product_update',86,'Updated product: Red Bull Energy Drink | Ready to Drink Beverage','2026-03-29 13:57:49'),(50,1,'catalog','product_update',83,'Updated product: Red Bull Energy Drink | Sugar Free | Energizing & Fizzy','2026-03-29 13:58:02'),(51,1,'catalog','product_update',84,'Updated product: Red Bull Energy Drink The Pink Edition','2026-03-29 13:58:13'),(52,1,'catalog','product_update',90,'Updated product: Red Bull Kratingdaeng Soft Drink','2026-03-29 13:58:19'),(53,1,'catalog','product_update',85,'Updated product: Red Bull Yellow Edition Energy Drink | Energising & Bold','2026-03-29 13:58:27'),(54,1,'catalog','product_update',89,'Updated product: Red Bull Zero Sugar Energy Drink','2026-03-29 13:58:33'),(55,1,'gaming','food_beverage_create',5,'Created food/beverage item: Thums Up - 200 ML','2026-03-29 14:03:10'),(56,1,'gaming','food_beverage_create',6,'Created food/beverage item: Cappuccino','2026-03-29 14:03:54'),(57,1,'gaming','food_beverage_create',7,'Created food/beverage item: Red Bull - 200 ML','2026-03-29 14:04:26'),(58,1,'auth','logout',1,'Logged out','2026-03-29 15:36:14'),(59,2,'auth','login',2,'Logged in','2026-03-29 15:36:30'),(60,2,'auth','logout',2,'Logged out','2026-03-29 15:36:40'),(61,1,'auth','login',1,'Logged in','2026-03-29 15:36:42'),(62,1,'auth','logout',1,'Logged out','2026-03-29 15:36:45'),(63,1,'auth','login',1,'Logged in','2026-03-29 15:37:16'),(64,1,'auth','logout',1,'Logged out','2026-03-29 15:37:31'),(65,2,'auth','login',2,'Logged in','2026-03-29 15:37:50'),(66,2,'auth','logout',2,'Logged out','2026-03-29 15:43:21'),(67,1,'auth','login',1,'Logged in','2026-03-29 15:43:23'),(68,1,'sales','customer_create',1,'Created customer: Souvik Ghosh','2026-03-29 15:44:23'),(69,1,'sales','customer_create',2,'Created customer: Riju Dhar','2026-03-29 15:44:45'),(70,1,'sales','customer_create',3,'Created customer: MAX Police ','2026-03-29 15:45:15'),(71,1,'sales','customer_create',4,'Created customer: Payel Golui Patra','2026-03-29 15:45:49'),(72,1,'gaming','price_rule_create',2,'Created gaming price rule.','2026-03-29 15:50:26'),(73,1,'gaming','price_rule_create',3,'Created gaming price rule.','2026-03-29 15:50:41'),(74,1,'gaming','price_rule_create',4,'Created gaming price rule.','2026-03-29 15:50:54'),(75,1,'auth','login',1,'Logged in','2026-03-30 03:53:10'),(76,1,'gaming','visit_start',13,'Started gaming session #13','2026-03-30 03:53:25'),(77,1,'catalog','product_update',88,'Updated product: Monster Energy Ultra Fiesta Mango Zero Sugar','2026-03-30 03:57:34'),(78,1,'catalog','product_update',87,'Updated product: Monster-S The Doctor Imported Energy Drink','2026-03-30 03:57:47'),(79,1,'catalog','product_update',86,'Updated product: Red Bull Energy Drink | Ready to Drink Beverage','2026-03-30 03:57:57'),(80,1,'catalog','product_update',83,'Updated product: Red Bull Energy Drink | Sugar Free | Energizing & Fizzy','2026-03-30 03:58:06'),(81,1,'catalog','product_update',84,'Updated product: Red Bull Energy Drink The Pink Edition','2026-03-30 03:58:15'),(82,1,'catalog','product_update',90,'Updated product: Red Bull Kratingdaeng Soft Drink','2026-03-30 03:58:23'),(83,1,'catalog','product_update',85,'Updated product: Red Bull Yellow Edition Energy Drink | Energising & Bold','2026-03-30 03:58:34'),(84,1,'catalog','product_update',89,'Updated product: Red Bull Zero Sugar Energy Drink','2026-03-30 03:58:43'),(85,1,'catalog','product_update',88,'Updated product: Monster Energy Ultra Fiesta Mango Zero Sugar','2026-03-30 04:03:03'),(86,1,'catalog','product_update',87,'Updated product: Monster-S The Doctor Imported Energy Drink','2026-03-30 04:03:11'),(87,1,'catalog','product_update',86,'Updated product: Red Bull Energy Drink | Ready to Drink Beverage','2026-03-30 04:03:17'),(88,1,'catalog','product_update',83,'Updated product: Red Bull Energy Drink | Sugar Free | Energizing & Fizzy','2026-03-30 04:03:24'),(89,1,'catalog','product_update',84,'Updated product: Red Bull Energy Drink The Pink Edition','2026-03-30 04:03:30'),(90,1,'catalog','product_update',90,'Updated product: Red Bull Kratingdaeng Soft Drink','2026-03-30 04:03:36'),(91,1,'catalog','product_update',85,'Updated product: Red Bull Yellow Edition Energy Drink | Energising & Bold','2026-03-30 04:03:42'),(92,1,'catalog','product_update',89,'Updated product: Red Bull Zero Sugar Energy Drink','2026-03-30 04:03:48'),(93,1,'gaming','visit_end',13,'Ended gaming session #13, total ₹100','2026-03-30 04:06:59'),(94,1,'gaming','visit_start',14,'Started gaming session #14','2026-03-30 04:35:36'),(95,1,'auth','logout',1,'Logged out','2026-03-30 04:37:53'),(96,2,'auth','login',2,'Logged in','2026-03-30 04:38:09'),(97,2,'auth','logout',2,'Logged out','2026-03-30 04:51:09'),(98,1,'auth','login',1,'Logged in','2026-03-30 04:51:11'),(99,1,'gaming','food_beverage_create',8,'Created food/beverage item: Black Coffee','2026-03-30 04:51:37'),(100,1,'auth','logout',1,'Logged out','2026-03-30 04:54:19'),(101,2,'auth','login',2,'Logged in','2026-03-30 04:54:42'),(102,2,'auth','logout',2,'Logged out','2026-03-30 04:55:13'),(103,1,'auth','login',1,'Logged in','2026-03-30 04:55:14'),(104,1,'auth','logout',1,'Logged out','2026-03-30 04:55:28'),(105,2,'auth','login',2,'Logged in','2026-03-30 04:55:43'),(106,2,'gaming','invoice_create',17,'Generated invoice GINV-20260330-EFFC96 for gaming session #13','2026-03-30 05:04:46'),(107,2,'sales','order_create',5,'Created order: ORD-20260330-561AAB','2026-03-30 05:06:29'),(108,2,'sales','invoice_create',18,'Invoice INV-20260330-DD8056 for order ORD-20260330-561AAB','2026-03-30 05:06:34'),(109,2,'sales','order_status',5,'Order status set to PAID','2026-03-30 05:06:34'),(110,2,'gaming','visit_end',14,'Ended gaming session #14, total ₹2000','2026-03-30 05:07:19'),(111,2,'auth','logout',2,'Logged out','2026-03-30 05:07:32'),(112,1,'auth','login',1,'Logged in','2026-03-30 05:07:34'),(113,1,'auth','login',1,'Logged in','2026-03-30 07:34:29'),(114,1,'auth','logout',1,'Logged out','2026-03-30 07:34:36'),(115,2,'auth','login',2,'Logged in','2026-03-30 07:34:52'),(116,2,'gaming','visit_start',15,'Started gaming session #15','2026-03-30 07:35:36'),(117,2,'sales','order_create',6,'Created order: ORD-20260330-FF5B11','2026-03-30 09:27:05'),(118,2,'sales','order_status',6,'Order status set to CONFIRMED','2026-03-30 09:27:10'),(119,2,'sales','invoice_create',19,'Invoice INV-20260330-07DF83 for order ORD-20260330-FF5B11','2026-03-30 09:27:13'),(120,2,'sales','order_status',6,'Order status set to PAID','2026-03-30 09:27:13'),(121,2,'sales','order_create',7,'Created order: ORD-20260330-169BCA','2026-03-30 09:28:09'),(122,2,'sales','order_status',7,'Order status set to CONFIRMED','2026-03-30 09:28:13'),(123,2,'sales','invoice_create',20,'Invoice INV-20260330-6B8335 for order ORD-20260330-169BCA','2026-03-30 09:28:15'),(124,2,'sales','order_status',7,'Order status set to PAID','2026-03-30 09:28:15'),(125,2,'auth','logout',2,'Logged out','2026-03-30 10:02:32'),(126,1,'auth','login',1,'Logged in','2026-03-30 10:02:33'),(127,1,'auth','login',1,'Logged in','2026-04-03 07:41:51'),(128,1,'auth','logout',1,'Logged out','2026-04-03 07:52:27'),(129,2,'auth','login',2,'Logged in','2026-04-03 07:52:47'),(130,2,'auth','logout',2,'Logged out','2026-04-03 07:52:52'),(131,1,'auth','login',1,'Logged in','2026-04-03 07:52:54'),(132,1,'gaming','visit_end',15,'Ended gaming session #15, total ₹100','2026-04-03 07:55:44'),(133,1,'gaming','visit_start',16,'Started gaming session #16','2026-04-03 07:58:41'),(134,1,'gaming','food_beverage_create',9,'Created food/beverage item: Tea','2026-04-03 08:03:01'),(135,1,'auth','login',1,'Logged in','2026-04-05 09:03:06'),(136,1,'catalog','vendor_create',6,'Created vendor: Beve & Food - Zepto','2026-04-05 09:06:18'),(137,1,'inventory','batch_create',79,'Created stock batch: BEVE-MONSTER1','2026-04-05 09:09:36'),(138,1,'gaming','visit_start',17,'Started gaming session #17','2026-04-05 09:17:59');
/*!40000 ALTER TABLE `user_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('ADMIN','STAFF') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'STAFF',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_users_email` (`email`),
  KEY `idx_users_role` (`role`),
  KEY `idx_users_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@example.com',NULL,'$2y$12$KtlvG2i6XFx7RIi4MbZvvO.70vOYGaodahkLRauTwm0T3yt5bhZmm','ADMIN',1,'2026-04-05 09:03:06','2026-03-07 12:13:11','2026-04-05 09:03:06'),(2,'Chandranath Patra','chandranathpatra@gmail.com','9902526648','$2y$12$qd2TiyFaypRVxor8b2h55.6RNKQ9ZQ47e6IbHTXm2OnBBcW5wL0HG','STAFF',1,'2026-04-03 07:52:47','2026-03-10 05:41:48','2026-04-03 07:52:47');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vendors`
--

DROP TABLE IF EXISTS `vendors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vendors` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_person` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_vendors_name` (`name`),
  KEY `idx_vendors_active` (`is_active`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
INSERT INTO `vendors` VALUES (3,'Drone - SAMPOORAN TRADERS - DRN-ST','Harsh banshal','8595714076','eonns777@gmail.com','Shop No. 894, Old Lajpat Rai Market Chandni Chowk, Delhi - 6\r\n\r\nGaurav - 8851388208\r\nHarsh - 8595714076',1,'2026-03-10 06:08:48','2026-03-10 11:28:09'),(4,'Electronic - RAIZON TECHNOLOGY - ELC-RT','Tarun Yadav','9711581409',NULL,'Off. G-2A, Ashok Bhawan, 93, Nehru Place, New Delhi - 110019\r\n\r\nTarun Yadav - 9711581409\r\nRahul Yadav - 8860105033\r\ninsta - @tarun_yadav0007',1,'2026-03-10 06:17:33','2026-03-10 11:28:42'),(5,'Toys - Fazal Elahi - TOY-RAF','RAF','9999477988',NULL,'R. A. Fazal Elahi, 276/1, gali toliya, Narain Market, Sadar Bazaar, Delhi, 110006\r\n\r\nRAF - 9999477988',1,'2026-03-10 11:25:55','2026-03-18 13:09:54'),(6,'Beve & Food - Zepto',NULL,NULL,NULL,NULL,1,'2026-04-05 09:06:18','2026-04-05 09:06:18');
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-05 18:29:56
