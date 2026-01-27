-- MySQL dump 10.13  Distrib 8.0.44, for Win64 (x86_64)
--
-- Host: localhost    Database: a_docaria
-- ------------------------------------------------------
-- Server version	8.4.7

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
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (3,'Bolos de Mel'),(2,'Broas'),(1,'Rebuçados');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clients` (
  `id` int NOT NULL AUTO_INCREMENT,
  `idFaturacao` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nif` varchar(255) DEFAULT NULL,
  `contact` varchar(255) DEFAULT NULL,
  `address` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idFaturacao` (`idFaturacao`),
  UNIQUE KEY `nif` (`nif`)
) ENGINE=InnoDB AUTO_INCREMENT=189 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,'0001','Consumidor Final',NULL,NULL,NULL),(2,'0002','Sabores da Falesia Lda.',NULL,NULL,NULL),(3,'0003','Corações da Colina Lda',NULL,NULL,NULL),(4,'0004','Bendix Tours',NULL,NULL,NULL),(5,'0005','Enmanuel Fernandes de Abreu',NULL,NULL,NULL),(6,'0006','Snack Bar Estrela Dos Alamos',NULL,NULL,NULL),(7,'0007','World Travel - Agencia de Viagens da Madeira, Lda',NULL,NULL,NULL),(8,'0008','Mundo exemplar, Lda',NULL,NULL,NULL),(9,'0009','Clube ANA Madeira',NULL,NULL,NULL),(10,'0010','Andrade, Caldeira e Abreu, Lda.',NULL,NULL,NULL),(11,'0011','José Humberto Rodrigues',NULL,NULL,NULL),(12,'0012','MFF Ecoturismo, Biologia e Conservação Unipessoal, Lda',NULL,NULL,NULL),(13,'0013','Eira do Serrado Empreendimentos Turisticos, Lda',NULL,NULL,NULL),(14,'0014','Casa Do Povo De Santa Cruz',NULL,NULL,NULL),(15,'0015','Enmanuel Fernandes de Abreu',NULL,NULL,NULL),(16,'0016','Teresa Maria Teixeira Vieira Mata',NULL,NULL,NULL),(17,'0017','Duarte Dinis',NULL,NULL,NULL),(18,'0018','Aresta Comapestre',NULL,NULL,NULL),(19,'0019','Helena F.F. Gomes',NULL,NULL,NULL),(20,'0020','Inturmed Lda',NULL,NULL,NULL),(21,'0021','Português Café II',NULL,NULL,NULL),(22,'0022','Abel Ricardo De Freitas',NULL,NULL,NULL),(23,'0023','Plano i9',NULL,NULL,NULL),(24,'0024','Variantepositiva - Consultadoria, Contabilidade e Projetos de investimento, Unipessoal, Lda.',NULL,NULL,NULL),(25,'0025','Duarte Andrade Viveiros',NULL,NULL,NULL),(26,'0026','Escola da APEL',NULL,NULL,NULL),(27,'0027','CDCR dos CTT da Madeira',NULL,NULL,NULL),(28,'0028','Boutique Areeiro. Comercio de Bordados, LDA',NULL,NULL,NULL),(29,'0029','Relatotrofeu .Lda',NULL,NULL,NULL),(30,'0030','Andrade, Caldeira e Abreu, Lda.',NULL,NULL,NULL),(31,'0031','University of Madeira Students\' Union Instituição de Utilidade Pública',NULL,NULL,NULL),(32,'0032','Sorum Madeira - Sociedade de Destilação da Madeira, Lda',NULL,NULL,NULL),(33,'0033','Rodrigues e Cro, Lda',NULL,NULL,NULL),(34,'0034','Mateus & Nunes, Lda',NULL,NULL,NULL),(35,'0035','Diálogo Notável, Lda',NULL,NULL,NULL),(36,'0036','Associação de Compartes Ilha Autêntica',NULL,NULL,NULL),(37,'0037','Agência Marinhense de Jornais e Revistas, Lda',NULL,NULL,NULL),(38,'0038','António Silva',NULL,NULL,NULL),(39,'0039','Escala Constante, Lda',NULL,NULL,NULL),(40,'0040','Ilídio Adriano Nunes Vieira',NULL,NULL,NULL),(41,'0041','Ilídio Adriano Nunes Vieira',NULL,NULL,NULL),(42,'0042','Manuel Afonso Sousa Luis',NULL,NULL,NULL),(43,'0043','Tatiana Filipa Gonsalves Silva, Unipessoal, Lda.',NULL,NULL,NULL),(44,'0044','DIVINEVIOLET, LDA',NULL,NULL,NULL),(45,'0045','DIVINEVIOLET, LDA',NULL,NULL,NULL),(46,'0046','Instituto de Desenvolvimento Regional, IP-RAM',NULL,NULL,NULL),(47,'0047','O Pilar das Refeições Sociedade Restauração Lda.',NULL,NULL,NULL),(48,'0048','Sebastião & Pereira, Lda',NULL,NULL,NULL),(49,'0049','Avelino Pedro Leça Correia',NULL,NULL,NULL),(50,'0050','Morela Lopez de Gouveia',NULL,NULL,NULL),(51,'0051','Sabores do Curral Lda',NULL,NULL,NULL),(52,'0052','Enxurros Produtos Alimentares Lda.',NULL,NULL,NULL),(53,'0053','Carlos Alberto Pascoal Pelixo',NULL,NULL,NULL),(54,'0054','Fernandes & Caires Lda',NULL,NULL,NULL),(55,'0055','Diogo calaça unip Lda',NULL,NULL,NULL),(56,'0056','Costa e Costa e Filhos',NULL,NULL,NULL),(57,'0057','Rocha e Ornelas',NULL,NULL,NULL),(58,'0058','Nítida Hipótese',NULL,NULL,NULL),(59,'0059','José Rodrigues de Barros',NULL,NULL,NULL),(60,'0060','Sympathy Makers Lda',NULL,NULL,NULL),(61,'0061','Carolina Vanessa Figueira unip Lda',NULL,NULL,NULL),(62,'0062','Madeira Way Unip. Lda',NULL,NULL,NULL),(63,'0063','Saldanha e Almeida, Lda',NULL,NULL,NULL),(64,'0064','Casa do Povo de Santa Maria Maior',NULL,NULL,NULL),(65,'0065','Nitido Ditado Lda',NULL,NULL,NULL),(66,'0066','SmileMachine Unipessoal Lda',NULL,NULL,NULL),(67,'0067','Paladares do Curral',NULL,NULL,NULL),(68,'0068','Moment Massage Unipessoal Lda',NULL,NULL,NULL),(69,'0069','NCAS - Produtos Alimentares, Unipessoal, Lda.',NULL,NULL,NULL),(70,'0070','Maria & Leodoro, Lda',NULL,NULL,NULL),(71,'0071','Pereira Gonçalves e Teixeira Dias, Lda',NULL,NULL,NULL),(72,'0072','Oriana Gonsalves',NULL,NULL,NULL),(73,'0073','Elegante & Catita',NULL,NULL,NULL),(74,'0074','Ribeiro Frio exploração de restaurantes',NULL,NULL,NULL),(75,'0075','JOSÉ M F SOUSA',NULL,NULL,NULL),(76,'0076','Tabelas do costume, lda',NULL,NULL,NULL),(77,'0077','Viafull Market Lda.',NULL,NULL,NULL),(78,'0078','Splendidchoice, Lda',NULL,NULL,NULL),(79,'0079','Tropicalshop, Lda',NULL,NULL,NULL),(80,'0080','Juan Sousa Unipessoal Lda.',NULL,NULL,NULL),(81,'0081','Riu Hotels, S.A. Sucursal Portugal Hotel Riu',NULL,NULL,NULL),(82,'0082','Albano Almeida, Lda.',NULL,NULL,NULL),(83,'0083','Carlos Vieira e Ferreira Lda.',NULL,NULL,NULL),(84,'0084','Agostinho Cristo',NULL,NULL,NULL),(85,'0085','Modelo Continente Hipermercados, S. A.',NULL,NULL,NULL),(86,'0086','Associação Essência Cristã',NULL,NULL,NULL),(87,'0087','Fortunecharm. Lda',NULL,NULL,NULL),(88,'0088','Rialitos Lda.',NULL,NULL,NULL),(89,'0089','Agência de Viagens Blandy, Lda.',NULL,NULL,NULL),(90,'0090','Dinarte José Fernandes',NULL,NULL,NULL),(91,'0091','Batuta D.Estrelas - Souvenirs, Lda',NULL,NULL,NULL),(92,'0092','Fidalgatitude CVC Lda',NULL,NULL,NULL),(93,'0093','Azulexultante Lda',NULL,NULL,NULL),(94,'0094','José Avelino Gonçalves e filho, Lda',NULL,NULL,NULL),(95,'0095','SAVOY - INVESTIMENTOS TURÍSTICOS, S.A.',NULL,NULL,NULL),(96,'0096','UAp Comando Zona Mil Madeira',NULL,NULL,NULL),(97,'0097','RC MUSEUM, UNIPESSOAL LDA',NULL,NULL,NULL),(98,'0098','Joao carlos fernandes',NULL,NULL,NULL),(99,'0099','J. Faria Filhos, LDA.',NULL,NULL,NULL),(100,'0100','Encarnação Saldanha',NULL,NULL,NULL),(101,'0101','Maria José Pereira G G Pestana',NULL,NULL,NULL),(102,'0102','José António & Marta Aguiar, Investimentos, LDA',NULL,NULL,NULL),(103,'0103','José Piloto',NULL,NULL,NULL),(104,'0104','Directo e Simples Mediação Imobiliária Lda.',NULL,NULL,NULL),(105,'0105','Salpicasa Unipessoal Lda.',NULL,NULL,NULL),(106,'0106','Ricardina Pinto Sá Rodrigues',NULL,NULL,NULL),(107,'0107','Nelia Maria Saldanha Cardoso',NULL,NULL,NULL),(108,'0108','Maribel Nunes',NULL,NULL,NULL),(109,'0109','Maria Odete g Valente Oliveira',NULL,NULL,NULL),(110,'0110','Luis Rijo Unipessoal Lda.',NULL,NULL,NULL),(111,'0111','Restaurante Grelhados, Lda',NULL,NULL,NULL),(112,'0112','NeptuneMystery,Unip,Lda',NULL,NULL,NULL),(113,'0113','Guillot Stéphanie',NULL,NULL,NULL),(114,'0114','Roberto Pestana',NULL,NULL,NULL),(115,'0115','José Luiz Rodrigues Da Paixão Supermercado Story Center',NULL,NULL,NULL),(116,'0116','Freitas e Martins, Lda. Casa da Palha',NULL,NULL,NULL),(117,'0117','Luis Faria',NULL,NULL,NULL),(118,'0118','Sandra Abreu Macedo unip.Lda',NULL,NULL,NULL),(119,'0119','DragonPyramid Unipessoal Lda.',NULL,NULL,NULL),(120,'0120','Lourenço Marques Caldeira da Silva',NULL,NULL,NULL),(121,'0121','Carmelita de Fatima Rodrigues',NULL,NULL,NULL),(122,'0122','Juan Miguel Gonçalves Unipessoal LDA',NULL,NULL,NULL),(123,'0123','Tânia Freitas',NULL,NULL,NULL),(124,'0124','Silêncio Transumante Lda',NULL,NULL,NULL),(125,'0125','Dunaspar',NULL,NULL,NULL),(126,'0126','Associação Juntos por Santa Clara',NULL,NULL,NULL),(127,'0127','Verónica Camacho & Camacho, Lda.',NULL,NULL,NULL),(128,'0128','Spad',NULL,NULL,NULL),(129,'0129','Madeira Now Unip, Lda.',NULL,NULL,NULL),(130,'0130','Empreendimentos Top Gama, Lda',NULL,NULL,NULL),(131,'0131','Francisco Ascenção',NULL,NULL,NULL),(132,'0132','Fátima & Luis Nóbrega, Lda.',NULL,NULL,NULL),(133,'1','Manuel José da Silva Fernandes',NULL,NULL,NULL),(134,'10','Gabriel Augusto Figueira Sousa',NULL,NULL,NULL),(135,'11','Domingos Perestrelo',NULL,NULL,NULL),(136,'12','Vale das Freiras',NULL,NULL,NULL),(137,'13','Aroma Lirico unipessoal',NULL,NULL,NULL),(138,'14','Casa do Povo Caniço',NULL,NULL,NULL),(139,'15','LABORATÓRIO EDOL',NULL,NULL,NULL),(140,'16','luciano goncalves pereira',NULL,NULL,NULL),(141,'17','MERCADINHO DA AJUDA PROD . ALIM. LDA',NULL,NULL,NULL),(142,'18','MERCADINHO DA AJUDA PROD ALIM. LDA',NULL,NULL,NULL),(143,'19','MERCADINHO DA AJUDA PROD. ALIM. LDA 2',NULL,NULL,NULL),(144,'20','PARTIDO COMUNISTA PORTUGUES',NULL,NULL,NULL),(145,'21','Curral Pão,LDA',NULL,NULL,NULL),(146,'22','José Miguel Jesus Quintal',NULL,NULL,NULL),(147,'23','A MELHOR TRADICAO -UNIP. LDA',NULL,NULL,NULL),(148,'24','A MELHOR TRADICAO-UNIP. LDA A CHAROLA II',NULL,NULL,NULL),(149,'26','NETMACHINE',NULL,NULL,NULL),(150,'27','Pricesa do Oceano unipessoal LDA',NULL,NULL,NULL),(151,'28','Martinho Pinto Figueira unipessoal Lda',NULL,NULL,NULL),(152,'29','jorge gouveia',NULL,NULL,NULL),(153,'3','norberto carlos abreu santos',NULL,NULL,NULL),(154,'30','EAD',NULL,NULL,NULL),(155,'31','Elvio Edgar T.Gil',NULL,NULL,NULL),(156,'32','Rafael Antonio Pinto Silva',NULL,NULL,NULL),(157,'33','A Garra Do Leão',NULL,NULL,NULL),(158,'34','Gasista LDA',NULL,NULL,NULL),(159,'35','Jorge Pestana L Aguiar',NULL,NULL,NULL),(160,'36','Casa Dos Vinhos Da Madeira Lda',NULL,NULL,NULL),(161,'37','LUIS GOUVEIA FREITAS',NULL,NULL,NULL),(162,'38','CASA DO POVO CURRAL DAS FREIRAS',NULL,NULL,NULL),(163,'39','Agostinho de Castro Unip LDA',NULL,NULL,NULL),(164,'4','João Eleutério Vieira Quintal',NULL,NULL,NULL),(165,'40','ASS.GRUPO ROMARIAS DO ROCHAO',NULL,NULL,NULL),(166,'41','SO USA SA',NULL,NULL,NULL),(167,'42','STYLE - UNIPESSOAL LDA.',NULL,NULL,NULL),(168,'43','FABIO JOSE CONÇEICAO CAMACHO UNIP LDA',NULL,NULL,NULL),(169,'44','Capitulo Pratico Lda',NULL,NULL,NULL),(170,'45','V2M Hotelaria Lda',NULL,NULL,NULL),(171,'46','Carla Maria Teixeira Gomes Spinola',NULL,NULL,NULL),(172,'47','Jose Henrique de Lima Junior',NULL,NULL,NULL),(173,'48','Susana Silva De Abreu',NULL,NULL,NULL),(174,'49','Snack Bar Pinguim Unipessoal Lda',NULL,NULL,NULL),(175,'5','C-Frutas Legumes Madeira Lda',NULL,NULL,NULL),(176,'50','Sweets and Sugar, S.A',NULL,NULL,NULL),(177,'51','Centro Cultural De Santo António',NULL,NULL,NULL),(178,'52','Joséfa Silva',NULL,NULL,NULL),(179,'53','Real vision lda',NULL,NULL,NULL),(180,'54','Bruno Luis A Bernardino',NULL,NULL,NULL),(181,'55','Arlindo Calisto P De Freitas',NULL,NULL,NULL),(182,'56','Joao Diogo DE JESUS LDA UNIPESOAL',NULL,NULL,NULL),(183,'57','Joséfa Silva Unipessoal Lda',NULL,NULL,NULL),(184,'58','Carreiro Machado & Nunes Pires. Lda',NULL,NULL,NULL),(185,'6','Joao Luis Abreu Sousa',NULL,NULL,NULL),(186,'7','CURRAL ARTE-ARTIGOS REG DO CURRAL,LDA',NULL,NULL,NULL),(187,'8','João Freitas Fernandes',NULL,NULL,NULL),(188,'9','Madalena cercado ruiz',NULL,NULL,NULL);
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_product` (`product_id`),
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `fk_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
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
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int NOT NULL,
  `vendedor_id` int NOT NULL,
  `trabalhador_id` int DEFAULT NULL,
  `invoice` varchar(255) DEFAULT NULL,
  `order_date` date NOT NULL,
  `ready_date` date NOT NULL,
  `delivery_date` date NOT NULL,
  `desired_date` date DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `iva` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `status` enum('concluido','entregue','preparacao') NOT NULL,
  `payment_status` enum('nao_pago','pago','parcial') NOT NULL,
  `payment_method` enum('cartao','dinheiro','cheque') DEFAULT NULL,
  `notes` text,
  PRIMARY KEY (`id`),
  KEY `fk_order_client` (`client_id`),
  KEY `fk_order_vendedor` (`vendedor_id`),
  KEY `fk_order_trabalhador` (`trabalhador_id`),
  CONSTRAINT `fk_order_client` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`),
  CONSTRAINT `fk_order_trabalhador` FOREIGN KEY (`trabalhador_id`) REFERENCES `users` (`id`),
  CONSTRAINT `fk_order_vendedor` FOREIGN KEY (`vendedor_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `label` varchar(100) NOT NULL,
  `subcategory_id` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `fk_products_subcategory` (`subcategory_id`),
  CONSTRAINT `fk_products_subcategory` FOREIGN KEY (`subcategory_id`) REFERENCES `subcategories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Rebuçados de Funcho','Funcho',1,0.90,1),(2,'Rebuçados de Eucalipto','Eucalipto',1,0.90,1),(3,'Rebuçado de Maracujá','Maracujá',1,0.90,1),(4,'Rebuçado de Banana','Banana',1,0.90,1),(5,'Rebuçado de Castanha','Castanha',1,0.90,1),(6,'Rebuçado de Limão','Limão',1,0.90,1),(7,'Rebuçado de Mango','Mango',1,0.90,1),(8,'Rebuçado de Ginja','Ginja',1,0.90,1),(9,'Rebuçado de Frutos Silvestres','Frutos Silvestres',1,0.90,1),(10,'Rebuçado de Mel de Cana','Mel de Cana',1,0.90,1),(11,'Rebuçado de Ananás','Ananás',1,0.90,1),(12,'Rebuçado de Morango','Morango',1,0.90,1),(13,'Rebuçado de Gengibre','Gengibre',1,0.90,1),(14,'Rebuçado de Tangerina','Tangerina',1,0.90,1),(15,'Rebuçado de Mistos','Mistos',1,0.90,1),(16,'Broas de Mel','Mel',2,1.70,1),(17,'Broas de Castanha','Castanha',2,1.70,1),(18,'Broas de Manteiga','Manteiga',2,1.70,1),(19,'Broas de Coco','Coco',2,1.70,1),(20,'Broas de Areia','Areias',2,3.50,1),(21,'Palitos de Cerveja','Palitos de Cerveja',2,3.00,1),(22,'Coquinhos','Coquinhos',2,4.00,0),(23,'Suspiros','Suspiros',2,1.00,0),(24,'Biscoitos de Marmelada','Biscoitos de Marmelada',2,3.00,0),(25,'Broas de Mel (Mini - 500gr)','Mel (Mini - 500gr)',2,4.50,1),(26,'Bolo de Mel Normal 100gr','100gr',3,1.00,1),(27,'Bolo de Mel Normal 160gr','160gr',3,1.80,1),(28,'Bolo de Mel Normal 250gr','250gr',3,1.60,1),(29,'Bolo de Mel Normal 450gr','450gr',3,2.60,1),(30,'Bolo de Mel Normal 700gr','700gr',3,5.00,1),(31,'Bolo de Mel Normal 800gr','800gr',3,6.00,1),(32,'Bolo de Mel Papel 100gr','100gr',4,1.20,1),(33,'Bolo de Mel Papel 250gr','250gr',4,1.80,1),(34,'Bolo de Mel Papel 450gr','450gr',4,2.80,1),(35,'Bolo de Mel Papel 700gr','700gr',4,5.20,1),(36,'Bolo de Mel Caixa 250gr','250gr',5,2.10,1),(37,'Bolo de Mel Caixa 450gr','450gr',5,3.00,1),(38,'Bolo de Mel Caixa Santana 160gr','Santana 160gr',5,2.20,1),(39,'Bolo de Mel Cesto 100gr','100gr',6,2.20,1),(40,'Bolo de Mel Cesto 250gr','250gr',6,3.10,1),(41,'Bolo de Mel Cesto 450gr','450gr',6,4.45,1),(42,'Bolo de Mel Cesto 700gr','700gr',6,5.80,1),(43,'Bolo de Mel Cesto 160gr & Vinho','160gr & Vinho',6,4.75,1),(44,'Bolo de Mel Cesto 160gr & Rum','160gr & Rum',6,4.90,1),(45,'Bolo de Mel Cesto 450gr & Vinho','450gr & Vinho',6,6.10,1),(46,'Bolo de Mel Cesto 2x 100gr & 2x Vinho','2x 100gr & 2x Vinho',6,7.00,1),(47,'Bolo de Mel Cesto 100gr & Poncha','100gr & Poncha',6,4.30,1),(48,'Bolo de Mel Cesto 250gr & 2x Vinho','250gr & 2x Vinho',6,7.50,1);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subcategories`
--

DROP TABLE IF EXISTS `subcategories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `subcategories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `category_id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_subcategory_category` (`category_id`),
  CONSTRAINT `fk_subcategory_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subcategories`
--

LOCK TABLES `subcategories` WRITE;
/*!40000 ALTER TABLE `subcategories` DISABLE KEYS */;
INSERT INTO `subcategories` VALUES (1,1,'Sabores'),(2,2,'Variedades'),(3,3,'Normal'),(4,3,'Papel'),(5,3,'Caixa'),(6,3,'Cesto');
/*!40000 ALTER TABLE `subcategories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','vendedor','trabalhador') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Administrador','admin@adocaria.pt','admin','admin'),(2,'Vendedor','vendedor@adocaria.pt','vendedor','vendedor'),(3,'Trabalhador','trabalhador@adocaria.pt','trabalhador','trabalhador');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'a_docaria'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-19 15:19:28
