-- MySQL dump 10.13  Distrib 9.4.0, for macos14.7 (x86_64)
--
-- Host: localhost    Database: gameshala_erp
-- ------------------------------------------------------
-- Server version	9.4.0

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
  CONSTRAINT `fk_batch_rules_batch` FOREIGN KEY (`batch_id`) REFERENCES `stock_batches` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_batch_rules_rule` FOREIGN KEY (`procurement_rule_id`) REFERENCES `procurement_rules` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `batch_procurement_rules`
--

LOCK TABLES `batch_procurement_rules` WRITE;
/*!40000 ALTER TABLE `batch_procurement_rules` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `coupons`
--

LOCK TABLES `coupons` WRITE;
/*!40000 ALTER TABLE `coupons` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `food_beverage_items`
--

LOCK TABLES `food_beverage_items` WRITE;
/*!40000 ALTER TABLE `food_beverage_items` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_categories`
--

LOCK TABLES `gaming_categories` WRITE;
/*!40000 ALTER TABLE `gaming_categories` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_modes`
--

LOCK TABLES `gaming_modes` WRITE;
/*!40000 ALTER TABLE `gaming_modes` DISABLE KEYS */;
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
  CONSTRAINT `fk_gaming_price_rules_category` FOREIGN KEY (`gaming_category_id`) REFERENCES `gaming_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_price_rules_mode` FOREIGN KEY (`gaming_mode_id`) REFERENCES `gaming_modes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_price_rules`
--

LOCK TABLES `gaming_price_rules` WRITE;
/*!40000 ALTER TABLE `gaming_price_rules` DISABLE KEYS */;
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
  CONSTRAINT `fk_gaming_visit_food_items_item` FOREIGN KEY (`food_beverage_item_id`) REFERENCES `food_beverage_items` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_gaming_visit_food_items_visit` FOREIGN KEY (`gaming_visit_id`) REFERENCES `gaming_visits` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
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
  CONSTRAINT `fk_gaming_visits_rule` FOREIGN KEY (`gaming_price_rule_id`) REFERENCES `gaming_price_rules` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gaming_visits`
--

LOCK TABLES `gaming_visits` WRITE;
/*!40000 ALTER TABLE `gaming_visits` DISABLE KEYS */;
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
  CONSTRAINT `fk_invoices_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_invoices_gaming_visit` FOREIGN KEY (`gaming_visit_id`) REFERENCES `gaming_visits` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `invoices`
--

LOCK TABLES `invoices` WRITE;
/*!40000 ALTER TABLE `invoices` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2026-03-08-150000','App\\Database\\Migrations\\TruncateAllTablesExceptUsers','default','App',1773082548,24);
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
  `listing_price_snapshot` decimal(12,2) DEFAULT NULL,
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
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
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
  CONSTRAINT `fk_orders_coupon` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_customer` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `procurement_rules`
--

LOCK TABLES `procurement_rules` WRITE;
/*!40000 ALTER TABLE `procurement_rules` DISABLE KEYS */;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
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
  UNIQUE KEY `uq_stock_batches_code` (`batch_code`),
  KEY `idx_stock_batches_product` (`product_id`),
  KEY `idx_stock_batches_vendor` (`vendor_id`),
  KEY `idx_stock_batches_remaining` (`remaining_qty`),
  CONSTRAINT `fk_stock_batches_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_stock_batches_vendor` FOREIGN KEY (`vendor_id`) REFERENCES `vendors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_batches`
--

LOCK TABLES `stock_batches` WRITE;
/*!40000 ALTER TABLE `stock_batches` DISABLE KEYS */;
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
  CONSTRAINT `fk_stock_movements_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stock_movements`
--

LOCK TABLES `stock_movements` WRITE;
/*!40000 ALTER TABLE `stock_movements` DISABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=63 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_activities`
--

LOCK TABLES `user_activities` WRITE;
/*!40000 ALTER TABLE `user_activities` DISABLE KEYS */;
INSERT INTO `user_activities` VALUES (1,1,'inventory','procurement_rule_create',2,'Created procurement rule: Discount for Holi on colors','2026-03-07 08:13:03'),(2,1,'inventory','batch_create',1,'Created stock batch: BATCH-2026-01','2026-03-07 08:50:33'),(3,1,'inventory','batch_update',1,'Updated stock batch: BATCH-2026-01','2026-03-07 08:50:44'),(4,1,'sales','customer_create',1,'Created customer: John Deep','2026-03-07 09:16:43'),(5,1,'sales','customer_deactivate',1,'Deactivated customer: John Deep','2026-03-07 09:17:02'),(6,1,'sales','customer_activate',1,'Activated customer: John Deep','2026-03-07 09:17:06'),(7,1,'sales','order_create',1,'Created order: ORD-20260307-C7E04A','2026-03-07 09:46:37'),(8,1,'sales','invoice_create',1,'Invoice INV-20260307-899508 for order ORD-20260307-C7E04A','2026-03-07 09:49:16'),(9,1,'sales','order_status',1,'Order status set to PAID','2026-03-07 09:49:16'),(10,1,'sales','order_status',1,'Order status set to CONFIRMED','2026-03-07 09:53:53'),(11,1,'sales','order_status',1,'Order status set to PAID','2026-03-07 09:53:58'),(12,1,'sales','order_status',1,'Order status set to CONFIRMED','2026-03-07 09:56:54'),(13,1,'sales','order_status',1,'Order status set to PAID','2026-03-07 10:17:26'),(14,1,'auth','logout',1,'Logged out','2026-03-07 10:50:57'),(15,1,'auth','login',1,'Logged in','2026-03-07 10:51:08'),(16,1,'catalog','product_create',2,'Created product: VR BOX 3','2026-03-07 11:48:10'),(17,1,'catalog','vendor_create',2,'Created vendor: VR Enterprise Pvt Ltd','2026-03-07 11:48:47'),(18,1,'inventory','procurement_rule_create',3,'Created procurement rule: Diwali VR BOX Sale','2026-03-07 11:50:03'),(19,1,'inventory','batch_create',2,'Created stock batch: BATCH-2026-07','2026-03-07 11:50:46'),(20,1,'catalog','coupon_create',2,'Created coupon: SAVE10','2026-03-07 11:51:44'),(21,1,'sales','customer_create',2,'Quick-add customer: C Patra','2026-03-07 11:52:15'),(22,1,'sales','order_create',2,'Created order: ORD-20260307-2399CA','2026-03-07 11:53:01'),(23,1,'sales','invoice_create',2,'Invoice INV-20260307-127DB1 for order ORD-20260307-2399CA','2026-03-07 11:53:12'),(24,1,'sales','order_status',2,'Order status set to PAID','2026-03-07 11:53:12'),(25,1,'auth','logout',1,'Logged out','2026-03-07 11:55:20'),(26,1,'auth','login',1,'Logged in','2026-03-07 11:55:31'),(27,1,'catalog','product_create',3,'Created product: Water bottle','2026-03-07 12:07:10'),(28,1,'auth','login',1,'Logged in','2026-03-08 09:58:53'),(29,1,'sales','order_create',3,'Created order: ORD-20260308-9F7A8C','2026-03-08 10:08:27'),(30,1,'sales','invoice_create',15,'Invoice INV-20260308-51EF4D for order ORD-20260308-9F7A8C','2026-03-08 10:08:31'),(31,1,'sales','order_status',3,'Order status set to PAID','2026-03-08 10:08:31'),(32,1,'catalog','coupon_update',2,'Updated coupon: SAVE10','2026-03-08 10:16:38'),(33,1,'sales','order_create',4,'Created order: ORD-20260308-8C7D10','2026-03-08 10:16:59'),(34,1,'sales','invoice_create',16,'Invoice INV-20260308-8DF685 for order ORD-20260308-8C7D10','2026-03-08 10:17:03'),(35,1,'sales','order_status',4,'Order status set to PAID','2026-03-08 10:17:03'),(36,1,'inventory','batch_create',3,'Created stock batch: BATCH-2026-08','2026-03-08 10:24:09'),(40,1,'sales','order_create',8,'Created order: ORD-20260308-58AD14','2026-03-08 10:33:15'),(41,1,'sales','invoice_create',17,'Invoice INV-20260308-41E75F for order ORD-20260308-58AD14','2026-03-08 10:33:19'),(42,1,'sales','order_status',8,'Order status set to PAID','2026-03-08 10:33:19'),(43,1,'sales','order_create',9,'Created order: ORD-20260308-3082DB','2026-03-08 10:36:53'),(44,1,'sales','invoice_create',18,'Invoice INV-20260308-D33BA8 for order ORD-20260308-3082DB','2026-03-08 10:36:55'),(45,1,'sales','order_status',9,'Order status set to PAID','2026-03-08 10:36:55'),(46,1,'inventory','batch_update',2,'Updated stock batch: BATCH-2026-07','2026-03-08 10:41:29'),(47,1,'sales','order_create',10,'Created order: ORD-20260308-B8B102','2026-03-08 10:42:06'),(48,1,'sales','invoice_create',19,'Invoice INV-20260308-B9DF9C for order ORD-20260308-B8B102','2026-03-08 10:42:09'),(49,1,'sales','order_status',10,'Order status set to PAID','2026-03-08 10:42:09'),(50,1,'catalog','product_create',4,'Created product: Jaipuri Sandal','2026-03-08 10:45:58'),(51,1,'catalog','vendor_create',3,'Created vendor: jaipur Sandal Pvt Ltd','2026-03-08 10:46:22'),(52,1,'inventory','procurement_rule_create',4,'Created procurement rule: Holi Sandal Discount','2026-03-08 10:46:51'),(53,1,'inventory','batch_create',4,'Created stock batch: BATCH-2026-09','2026-03-08 10:47:22'),(54,1,'sales','customer_create',3,'Quick-add customer: Susmita Senapati','2026-03-08 10:48:00'),(55,1,'sales','order_create',11,'Created order: ORD-20260308-71C2F6','2026-03-08 10:49:04'),(56,1,'sales','invoice_create',20,'Invoice INV-20260308-D183F1 for order ORD-20260308-71C2F6','2026-03-08 10:49:12'),(57,1,'sales','order_status',11,'Order status set to PAID','2026-03-08 10:49:12'),(58,1,'gaming','visit_start',13,'Started gaming session #13','2026-03-08 10:51:01'),(59,1,'gaming','visit_food_add',13,'Added food/beverage to session #13','2026-03-08 10:51:14'),(60,1,'gaming','visit_food_add',13,'Added food/beverage to session #13','2026-03-08 10:51:19'),(61,1,'gaming','visit_end',13,'Ended gaming session #13, total ₹122.33','2026-03-08 10:51:24'),(62,1,'gaming','invoice_create',21,'Generated invoice GINV-20260308-2F0D9E for gaming session #13','2026-03-08 10:51:36');
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@example.com',NULL,'$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi','ADMIN',1,'2026-03-08 09:58:53','2026-03-07 12:13:11','2026-03-08 09:58:53');
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vendors`
--

LOCK TABLES `vendors` WRITE;
/*!40000 ALTER TABLE `vendors` DISABLE KEYS */;
/*!40000 ALTER TABLE `vendors` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-03-10  0:26:19
