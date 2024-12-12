-- MariaDB dump 10.19  Distrib 10.4.28-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: webshop
-- ------------------------------------------------------
-- Server version	10.4.28-MariaDB

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
-- Table structure for table `bills`
--

DROP TABLE IF EXISTS `bills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bills` (
  `id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `pay_method` varchar(255) DEFAULT NULL,
  `payment_service_provider` varchar(255) DEFAULT NULL,
  `total_price` varchar(255) NOT NULL,
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `canceled_at` datetime DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_22775DD08D9F6D38` (`order_id`),
  KEY `IDX_22775DD0A76ED395` (`user_id`),
  CONSTRAINT `FK_22775DD08D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `FK_22775DD0A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bills`
--

LOCK TABLES `bills` WRITE;
/*!40000 ALTER TABLE `bills` DISABLE KEYS */;
/*!40000 ALTER TABLE `bills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_3AF34668139DF194` (`promotion_id`),
  KEY `IDX_3AF34668727ACA70` (`parent_id`),
  CONSTRAINT `FK_3AF34668139DF194` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`),
  CONSTRAINT `FK_3AF34668727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Gym','gym',NULL,NULL),(2,'Formal','formal',NULL,NULL),(3,'Party','party',NULL,NULL),(4,'Casual','casual',NULL,NULL),(5,'T-Shirt','t-shirt',4,NULL),(6,'Shirt','shirt',4,NULL),(7,'Polo','polo',4,NULL),(8,'Jeans','jeans',4,NULL),(9,'Shorts','shorts',4,NULL);
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `doctrine_migration_versions`
--

LOCK TABLES `doctrine_migration_versions` WRITE;
/*!40000 ALTER TABLE `doctrine_migration_versions` DISABLE KEYS */;
INSERT INTO `doctrine_migration_versions` VALUES ('App\\Migrations\\Version20241129014558','2024-11-29 02:46:00',325),('App\\Migrations\\Version20241201122643','2024-12-01 13:26:45',104),('App\\Migrations\\Version20241203154958','2024-12-03 16:50:01',113),('App\\Migrations\\Version20241203155713','2024-12-03 16:57:16',41),('App\\Migrations\\Version20241203155901','2024-12-03 16:59:03',9),('App\\Migrations\\Version20241203163816','2024-12-03 17:38:23',111),('App\\Migrations\\Version20241203163820','2024-12-03 17:38:23',3),('App\\Migrations\\Version20241204015323','2024-12-04 02:53:25',61),('App\\Migrations\\Version20241204015358','2024-12-04 02:54:00',11),('App\\Migrations\\Version20241204051140','2024-12-04 06:11:43',135),('App\\Migrations\\Version20241204111802','2024-12-04 12:18:06',11),('App\\Migrations\\Version20241209085906','2024-12-09 09:59:08',103);
/*!40000 ALTER TABLE `doctrine_migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mail_service_manager`
--

DROP TABLE IF EXISTS `mail_service_manager`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mail_service_manager` (
  `email` varchar(255) NOT NULL,
  `sent_verify_email_at` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mail_service_manager`
--

LOCK TABLES `mail_service_manager` WRITE;
/*!40000 ALTER TABLE `mail_service_manager` DISABLE KEYS */;
/*!40000 ALTER TABLE `mail_service_manager` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL,
  `products` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
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
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` longtext NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `rate` int(11) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `details` longtext DEFAULT NULL,
  `images` longtext DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `total_rates` int(11) NOT NULL,
  `total_reviews` int(11) NOT NULL,
  `promotion_id` int(11) DEFAULT NULL,
  `sold_number` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B3BA5A5A139DF194` (`promotion_id`),
  KEY `IDX_B3BA5A5A12469DE2` (`category_id`),
  CONSTRAINT `FK_B3BA5A5A12469DE2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_B3BA5A5A139DF194` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (3,'Polo with Tipping Details','The Polo with Tipping combines classic polo styling with a modern twist. Featuring tipped collars and cuffs, this polo shirt is perfect for both casual and semi-formal occasions. Its soft fabric ensures comfort, while the sleek design elevates your everyday wardrobe.',34.99,12,0,'polo-with-tipping-details','<h3>Detailed Description:</h3>\r\n<p>The <strong>Polo with Tipping</strong> is a refined and versatile wardrobe staple, blending classic polo styling with contemporary design elements. Made from a premium cotton blend, this shirt provides exceptional softness and breathability, making it ideal for all-day wear. The subtle tipping on the collar and cuffs adds a pop of contrast, giving the polo a sporty yet sophisticated look.</p>\r\n<p>This polo shirt is designed with a regular fit, ensuring comfort without compromising on style. The soft, ribbed collar sits neatly around the neck, while the three-button placket provides a tailored, clean finish. The tipped details are available in various color combinations, giving you options to match your personal style.</p>\r\n<p>Perfect for a variety of settings, this <strong>Polo with Tipping</strong> can be worn with jeans, chinos, or shorts for a casual look, or dressed up with tailored trousers for a smart-casual appearance. Whether you\'re heading out for lunch or a weekend outing, this polo shirt will have you looking effortlessly stylish.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Premium Cotton Blend</strong>: Soft, breathable fabric for comfort.</li>\r\n<li><strong>Tipped Collar and Cuffs</strong>: Adds a stylish contrast to the classic design.</li>\r\n<li><strong>Regular Fit</strong>: Offers a comfortable and relaxed silhouette.</li>\r\n<li><strong>Three-Button Placket</strong>: A timeless design element for a tailored finish.</li>\r\n<li><strong>Versatile Style</strong>: Perfect for casual or semi-formal occasions.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aed24e97341732963620.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aed25011be1732963621.png\"}]',7,0,0,NULL,0,0,NULL,'2024-11-30 11:46:28','2024-11-30 11:46:28'),(4,'Black Striped T-shirt','The Black Striped T-shirt offers a bold, minimalist design with contrasting stripes for a stylish and modern look. Perfect for any casual occasion, it combines comfort and effortless style, making it an essential piece for your wardrobe.',19.99,4,0,'black-striped-t-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>Black Striped T-shirt</strong> is the perfect blend of simplicity and style. Designed with horizontal black stripes across a solid black base, it gives a chic, contemporary look that easily pairs with jeans, shorts, or casual trousers. This t-shirt is crafted from a soft cotton blend that ensures comfort throughout the day, whether you\'re relaxing at home or heading out with friends.</p>\r\n<p>The stripes are subtly woven into the fabric, offering a sleek yet bold contrast against the dark background. The classic crew neck design and regular fit ensure it maintains a flattering silhouette that suits all body types. Its breathable and lightweight fabric makes it ideal for warmer weather or layering under jackets and cardigans during cooler months.</p>\r\n<p>Perfect for casual outings, weekend activities, or even as a relaxed office wear option, the <strong>Black Striped T-shirt</strong> is versatile and timeless, providing a stylish option for every occasion.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Premium Cotton Blend</strong>: Soft, breathable fabric for all-day comfort.</li>\r\n<li><strong>Bold Stripes Design</strong>: Horizontal stripes for a modern, sleek look.</li>\r\n<li><strong>Regular Fit</strong>: Comfortable and flattering for all body types.</li>\r\n<li><strong>Classic Crew Neck</strong>: Timeless design for easy pairing with any outfit.</li>\r\n<li><strong>Versatile</strong>: Ideal for casual wear, outdoor activities, or layering.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aed7b334cc1732963707.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aed7b3c2ba1732963707.png\"}]',5,0,0,NULL,0,0,NULL,'2024-11-30 11:47:59','2024-11-30 11:47:59'),(5,'Skinny Fit Jeans','The Skinny Fit Jeans are a must-have for modern wardrobes, offering a sleek, tailored fit that contours to your body. Designed for comfort and style, they are perfect for creating a sharp, trendy look for any occasion.',39.99,4,0,'skinny-fit-jeans','<h3>Detailed Description:</h3>\r\n<p>The <strong>Skinny Fit Jeans</strong> are the ultimate combination of comfort and style. Crafted from a high-quality denim blend, these jeans provide a form-fitting silhouette that hugs the body from waist to ankle, offering a streamlined, sharp look. The stretch fabric ensures flexibility and ease of movement, making them comfortable enough for all-day wear.</p>\r\n<p>With a classic five-pocket design, these jeans include sturdy stitching and metal hardware that add durability to their sleek appearance. The waistband sits comfortably at the waist, and the slim leg design creates a flattering, modern profile that works well with a variety of outfits. Whether paired with a t-shirt, button-up shirt, or hoodie, these jeans elevate any casual or semi-casual outfit.</p>\r\n<p>The <strong>Skinny Fit Jeans</strong> are ideal for those who love a modern, sharp look and prefer a more tailored fit. Whether you\'re going out with friends, running errands, or heading to a casual work setting, these jeans will keep you looking effortlessly stylish.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Tailored Fit</strong>: Hugs the body from waist to ankle for a sleek, modern look.</li>\r\n<li><strong>Stretch Denim</strong>: Provides comfort and flexibility throughout the day.</li>\r\n<li><strong>Classic Five-Pocket Design</strong>: Offers durability and functionality.</li>\r\n<li><strong>High-Quality Denim</strong>: Soft yet strong fabric for long-lasting wear.</li>\r\n<li><strong>Versatile Style</strong>: Pairs well with a variety of tops and shoes for any casual or semi-casual look.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aedf3a36fe1732963827.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aedf3aad651732963827.png\"}]',8,0,0,NULL,0,0,NULL,'2024-11-30 11:50:07','2024-11-30 11:50:07'),(6,'Checkered Shirt','The Checkered Shirt is a classic piece that brings a timeless pattern to your wardrobe. With its vibrant checkered design, it&#039;s the perfect shirt for adding a touch of style to any casual or semi-formal occasion.',29.99,0,0,'checkered-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>Checkered Shirt</strong> is a versatile and stylish addition to any wardrobe. Featuring a bold, checkered pattern in a variety of colors, this shirt combines comfort and fashion effortlessly. Made from soft, breathable cotton, it ensures all-day comfort whether you\'re at the office, out with friends, or enjoying a weekend getaway.</p>\r\n<p>The shirt is designed with a traditional button-down front, collar, and long sleeves, giving it a sharp, clean look. The regular fit provides a balanced silhouette, allowing you to wear it tucked or untucked depending on your preference. Whether paired with jeans, chinos, or layered under a jacket, the <strong>Checkered Shirt</strong> is the perfect way to add visual interest to your outfit.</p>\r\n<p>Its classic checkered design works well for a variety of occasions, making it ideal for casual outings, smart-casual events, or even office wear when styled appropriately.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Checkered Pattern</strong>: Classic and bold, available in multiple color combinations.</li>\r\n<li><strong>Soft Cotton Fabric</strong>: Ensures comfort and breathability.</li>\r\n<li><strong>Button-Down Design</strong>: Timeless shirt style with a neat finish.</li>\r\n<li><strong>Long Sleeves</strong>: Versatile for all seasons, can be rolled up for a more relaxed look.</li>\r\n<li><strong>Regular Fit</strong>: Provides a balanced, comfortable fit for most body types.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aee59f414e1732963929.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aee5a08b071732963930.png\"}]',6,0,0,NULL,0,0,NULL,'2024-11-30 11:51:45','2024-11-30 11:51:45'),(7,'Sleeve Striped T-Shirt','The Sleeve Striped T-Shirt combines a classic crew-neck t-shirt with stylish striped sleeves for a contemporary twist. Comfortable, trendy, and perfect for casual outings, this t-shirt adds a unique flair to your wardrobe.',24.99,4,0,'sleeve-striped-t-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>Sleeve Striped T-Shirt</strong> takes a simple wardrobe essential and gives it a modern update. Featuring a solid-colored body with bold, striped sleeves, this t-shirt offers a fresh and dynamic look. Made from soft, breathable cotton, it ensures comfort and durability throughout the day, whether you\'re out with friends, running errands, or just relaxing.</p>\r\n<p>The contrasting stripes on the sleeves add an athletic yet stylish touch, elevating the otherwise minimalist design. The classic crew neck provides a timeless silhouette, while the regular fit ensures a flattering shape that suits all body types. The fabric is lightweight and breathable, making it a perfect choice for warmer weather or layering under jackets during cooler seasons.</p>\r\n<p>Ideal for casual days, weekend outings, or a relaxed get-together, the <strong>Sleeve Striped T-Shirt</strong> is versatile enough to pair with jeans, shorts, or even joggers for a laid-back, sporty look.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Striped Sleeves</strong>: Bold stripes for a stylish and sporty contrast.</li>\r\n<li><strong>Soft Cotton Fabric</strong>: Lightweight and breathable for all-day comfort.</li>\r\n<li><strong>Classic Crew Neck</strong>: Timeless design for easy pairing with any outfit.</li>\r\n<li><strong>Regular Fit</strong>: Provides a comfortable, flattering shape.</li>\r\n<li><strong>Versatile Style</strong>: Perfect for casual or laid-back occasions.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aeeb15ab5f1732964017.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aeeb1622a61732964017.png\"}]',5,0,0,NULL,0,0,NULL,'2024-11-30 11:53:10','2024-11-30 11:53:10'),(8,'Vertical Stripped Shirt','The Vertical Striped Shirt adds a modern touch to the classic striped design, offering a sleek and stylish look. Ideal for both casual and semi-formal occasions, this shirt combines comfort and fashion effortlessly.',34.99,85,0,'vertical-stripped-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>Vertical Striped Shirt</strong> offers a fresh and fashionable take on a traditional striped pattern. With its bold vertical stripes, this shirt creates a slimming effect while adding a dynamic visual element to your outfit. Crafted from a soft, breathable cotton blend, it provides ultimate comfort for all-day wear, whether you&rsquo;re in the office or out on the town.</p>\r\n<p>The shirt features a classic button-down front with a neat collar and long sleeves, making it easy to dress up or down. The vertical stripes in contrasting colors provide a clean and modern look, perfect for those who want to stand out while maintaining a polished appearance. The regular fit ensures a comfortable, flattering shape, and the lightweight fabric makes it perfect for layering in cooler months or wearing alone in warmer weather.</p>\r\n<p>Pair it with chinos, dress pants, or even jeans for a stylish, semi-formal look, or wear it casually with shorts for a more laid-back outfit. The <strong>Vertical Striped Shirt</strong> is versatile enough for various occasions, whether it\'s a business-casual day at work or a weekend outing with friends.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Vertical Stripes</strong>: A modern twist on the classic striped design, creating a slimming and stylish look.</li>\r\n<li><strong>Soft Cotton Blend</strong>: Breathable and comfortable for all-day wear.</li>\r\n<li><strong>Button-Down Design</strong>: Classic shirt style with a neat and tailored finish.</li>\r\n<li><strong>Long Sleeves</strong>: Ideal for layering or wearing on its own.</li>\r\n<li><strong>Regular Fit</strong>: Provides a comfortable and flattering silhouette.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674aef56de4451732964182.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674aef56e71831732964182.png\"}]',6,0,0,NULL,0,0,NULL,'2024-11-30 11:56:00','2024-11-30 11:56:00'),(9,'Courage Graphic T-Shirt','The Courage Graphic T-Shirt is a bold and inspiring piece featuring an empowering graphic design. With its comfortable fit and motivating message, it’s perfect for anyone looking to add a touch of confidence and style to their wardrobe.',22.99,7,0,'courage-graphic-t-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>Courage Graphic T-Shirt</strong> combines powerful symbolism with casual style, featuring an eye-catching graphic that inspires strength and resilience. The shirt is crafted from a high-quality, soft cotton blend that provides all-day comfort and breathability. Its lightweight fabric makes it a perfect option for layering or wearing on its own during warmer months.</p>\r\n<p>The standout feature of this t-shirt is the bold graphic design that emphasizes the theme of courage. Whether it&rsquo;s a motivational quote or an iconic image, the graphic adds personality and a sense of empowerment to the shirt. With a classic crew neck and a regular fit, it provides a flattering shape for all body types, ensuring comfort without sacrificing style.</p>\r\n<p>Perfect for casual outings, workouts, or just expressing your inner strength, the <strong>Courage Graphic T-Shirt</strong> is more than just a piece of clothing&mdash;it&rsquo;s a statement. Pair it with jeans, joggers, or shorts for a laid-back look, or wear it with a jacket for a more layered, fashionable outfit.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Bold Graphic Design</strong>: Features a powerful graphic that inspires confidence and courage.</li>\r\n<li><strong>Soft Cotton Blend</strong>: Comfortable and breathable fabric for all-day wear.</li>\r\n<li><strong>Classic Crew Neck</strong>: Timeless design that pairs well with any casual outfit.</li>\r\n<li><strong>Regular Fit</strong>: Provides a comfortable and flattering fit for all body types.</li>\r\n<li><strong>Versatile Style</strong>: Perfect for casual outings, workouts, or expressing your personal style.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674af01a269e21732964378.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674af01a2dca31732964378.png\"}]',5,0,0,NULL,0,0,NULL,'2024-11-30 11:59:27','2024-11-30 11:59:27'),(10,'Loose Fit Bermuda Shorts','The Loose Fit Bermuda Shorts offer ultimate comfort and laid-back style with their relaxed fit and breathable fabric. Perfect for warm weather, these shorts are designed for all-day wear, combining comfort with a casual, effortless look.',19.99,123,0,'loose-fit-bermuda-shorts','<h3>Detailed Description:</h3>\r\n<p>The <strong>Loose Fit Bermuda Shorts</strong> are designed for those who prioritize comfort and freedom of movement without compromising on style. Made from a soft, lightweight fabric, these shorts are perfect for hot summer days or casual outdoor activities. The loose fit provides a relaxed, easy-going silhouette, allowing for maximum airflow and comfort.</p>\r\n<p>The Bermuda-style length hits just above the knee, making them versatile for both active and leisure wear. Whether you\'re lounging at the beach, running errands, or enjoying a day out with friends, these shorts offer both comfort and functionality. The elastic waistband with an adjustable drawstring ensures a secure, customizable fit, while side pockets provide convenience for storing small essentials.</p>\r\n<p>Pair the <strong>Loose Fit Bermuda Shorts</strong> with a casual t-shirt, tank top, or polo for a laid-back summer outfit. Their versatile design and comfortable fit make them a must-have addition to any warm-weather wardrobe.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Loose Fit</strong>: Relaxed and comfortable fit for ease of movement.</li>\r\n<li><strong>Lightweight Fabric</strong>: Breathable and soft for all-day wear in hot weather.</li>\r\n<li><strong>Bermuda Length</strong>: Hits just above the knee for a stylish, versatile look.</li>\r\n<li><strong>Elastic Waistband with Drawstring</strong>: Adjustable for a secure and customizable fit.</li>\r\n<li><strong>Side Pockets</strong>: Convenient storage for small essentials.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674af073e1fc41732964467.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674af073e99a41732964467.png\"}]',9,0,0,NULL,0,0,NULL,'2024-11-30 12:00:45','2024-11-30 12:00:45'),(11,'One Life Graphic T-shirt','The One Life Graphic T-shirt is an inspiring and motivational piece that reminds you to live life to the fullest. Featuring a bold graphic and a comfortable fit, this t-shirt is perfect for anyone seeking both style and a positive message.',24.99,0,0,'one-life-graphic-t-shirt','<h3>Detailed Description:</h3>\r\n<p>The <strong>One Life Graphic T-shirt</strong> is designed to inspire and motivate, featuring a striking graphic with the empowering message of living life to its fullest. Crafted from a soft, high-quality cotton blend, this shirt offers comfort and breathability for all-day wear. Its lightweight fabric makes it ideal for warmer weather or for layering under jackets during cooler months.</p>\r\n<p>The eye-catching graphic on the front stands out with bold typography and meaningful artwork, giving the shirt a distinct personality. With its classic crew neck and regular fit, it provides a comfortable and flattering silhouette that works for all body types. Whether you\'re out with friends, heading to a casual event, or just expressing your personal philosophy, the <strong>One Life Graphic T-shirt</strong> makes a statement.</p>\r\n<p>Pair it with jeans, shorts, or joggers for an easy, stylish look that exudes confidence and positivity.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Motivational Graphic</strong>: Features a bold design with the powerful message \"One Life\" to inspire and encourage.</li>\r\n<li><strong>Soft Cotton Blend</strong>: Comfortable and breathable fabric for everyday wear.</li>\r\n<li><strong>Classic Crew Neck</strong>: Timeless design that suits a variety of outfits.</li>\r\n<li><strong>Regular Fit</strong>: Provides a comfortable, flattering fit for most body types.</li>\r\n<li><strong>Versatile Style</strong>: Perfect for casual outings, workouts, or expressing your personal philosophy.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674af0c3f23dc1732964547.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674af0c40a9ef1732964548.png\"}]',5,0,0,NULL,0,0,NULL,'2024-11-30 12:02:07','2024-11-30 12:02:07'),(12,'Polo with Contrast Trims','The Polo with Contrast Trims combines classic polo style with modern contrast detailing. Featuring a sleek design and high-quality fabric, this polo shirt adds sophistication and versatility to any wardrobe, perfect for both casual and semi-formal occasions.',29.99,64,0,'polo-with-contrast-trims','<h3>Detailed Description:</h3>\r\n<p>The <strong>Polo with Contrast Trims</strong> is a stylish update to the classic polo shirt, designed with sleek contrast accents along the collar and sleeve cuffs for a contemporary twist. Made from a soft, breathable cotton blend, it offers all-day comfort and is ideal for both casual and semi-formal occasions. The fabric provides a smooth, premium feel, making it perfect for warmer weather or layering during cooler seasons.</p>\r\n<p>This polo features a classic three-button placket and a ribbed collar, maintaining the timeless appeal of polo shirts. The contrast trims along the collar and sleeves add a modern touch, creating a bold, fashionable look. With its regular fit, the shirt offers a flattering silhouette that suits most body types, providing a smart-casual appearance without sacrificing comfort.</p>\r\n<p>Whether you\'re heading to a casual brunch, a day at the office, or a weekend outing, the <strong>Polo with Contrast Trims</strong> will keep you looking sharp and stylish. Pair it with chinos, jeans, or even tailored shorts for a versatile look that works for a variety of occasions.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Contrast Trims</strong>: Modern accents on the collar and sleeves for a stylish edge.</li>\r\n<li><strong>Soft Cotton Blend</strong>: Comfortable and breathable for everyday wear.</li>\r\n<li><strong>Three-Button Placket</strong>: Classic polo shirt style for a neat and refined look.</li>\r\n<li><strong>Ribbed Collar</strong>: Timeless design that adds structure to the shirt.</li>\r\n<li><strong>Regular Fit</strong>: Offers a comfortable and flattering silhouette for most body types.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674af120323e41732964640.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674af1203b45e1732964640.png\"}]',7,0,0,NULL,0,0,NULL,'2024-11-30 12:03:33','2024-11-30 12:03:33'),(13,'T-Shirt with Tape Details','The T-Shirt with Tape Details offers a modern, sporty vibe with unique tape detailing along the sleeves and sides. Comfortable, stylish, and versatile, this t-shirt is perfect for adding a trendy touch to your casual wardrobe.',22.99,1,0,'t-shirt-with-tape-details','<h3>Detailed Description:</h3>\r\n<p>The <strong>T-Shirt with Tape Details</strong> is a fresh, fashion-forward take on the classic t-shirt. This shirt features bold tape detailing along the sleeves and sides, adding a unique and sporty edge to a timeless design. Made from a soft, breathable cotton blend, this t-shirt is comfortable enough for all-day wear, whether you\'re lounging, out with friends, or running errands.</p>\r\n<p>The shirt is designed with a classic crew neck and a regular fit, ensuring a flattering and relaxed silhouette for all body types. The tape detailing adds a trendy, athletic-inspired element, making it stand out from standard t-shirts. Lightweight and versatile, it pairs easily with jeans, shorts, or joggers for a casual look that&rsquo;s both fashionable and comfortable.</p>\r\n<p>Ideal for a variety of occasions, the <strong>T-Shirt with Tape Details</strong> is perfect for casual outings, street-style looks, or even as a statement piece in your everyday wardrobe.</p>\r\n<hr>\r\n<h3>Key Features:</h3>\r\n<ul>\r\n<li><strong>Tape Details</strong>: Bold, sporty tape detailing along the sleeves and sides for a modern touch.</li>\r\n<li><strong>Soft Cotton Blend</strong>: Comfortable and breathable fabric for all-day wear.</li>\r\n<li><strong>Classic Crew Neck</strong>: Timeless design that complements any casual outfit.</li>\r\n<li><strong>Regular Fit</strong>: Offers a relaxed, flattering silhouette.</li>\r\n<li><strong>Versatile Style</strong>: Ideal for casual outings, street style, or laid-back weekends.</li>\r\n</ul>','[{\"lg\":\"\\/public\\/upload\\/images\\/1674af16384c0a1732964707.png\",\"sm\":\"\\/public\\/upload\\/images\\/1674af1638c25e1732964707.png\"}]',5,0,0,NULL,0,0,NULL,'2024-11-30 12:04:51','2024-11-30 12:04:51');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promo_codes`
--

DROP TABLE IF EXISTS `promo_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_percent` int(11) NOT NULL,
  `discount_amount` int(11) NOT NULL,
  `discount_max` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promo_codes`
--

LOCK TABLES `promo_codes` WRITE;
/*!40000 ALTER TABLE `promo_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `promo_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `discount_percent` int(11) DEFAULT NULL,
  `discount_amount` int(11) DEFAULT NULL,
  `discount_max` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `comment` longtext NOT NULL,
  `rate` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `updated_at` datetime NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_claims`
--

DROP TABLE IF EXISTS `role_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `role_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `claim_name` varchar(255) NOT NULL,
  `claim_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1585F20FD60322AC` (`role_id`),
  CONSTRAINT `FK_1585F20FD60322AC` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_claims`
--

LOCK TABLES `role_claims` WRITE;
/*!40000 ALTER TABLE `role_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'Admin');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subscriptions`
--

DROP TABLE IF EXISTS `subscriptions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subscriptions` (
  `email` varchar(255) NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `sent_verify_email_at` datetime NOT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subscriptions`
--

LOCK TABLES `subscriptions` WRITE;
/*!40000 ALTER TABLE `subscriptions` DISABLE KEYS */;
/*!40000 ALTER TABLE `subscriptions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_claims`
--

DROP TABLE IF EXISTS `user_claims`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_claims` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `claim_type` varchar(255) NOT NULL,
  `claim_value` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_DBF10883A76ED395` (`user_id`),
  CONSTRAINT `FK_DBF10883A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_claims`
--

LOCK TABLES `user_claims` WRITE;
/*!40000 ALTER TABLE `user_claims` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_claims` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_logins`
--

DROP TABLE IF EXISTS `user_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_logins` (
  `login_provider` varchar(255) NOT NULL,
  `provider_key` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`login_provider`,`provider_key`),
  UNIQUE KEY `UNIQ_6341CC99A76ED395` (`user_id`),
  CONSTRAINT `FK_6341CC99A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_logins`
--

LOCK TABLES `user_logins` WRITE;
/*!40000 ALTER TABLE `user_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tokens`
--

DROP TABLE IF EXISTS `user_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tokens` (
  `user_id` int(11) NOT NULL,
  `login_provider` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  PRIMARY KEY (`user_id`,`login_provider`),
  UNIQUE KEY `UNIQ_CF080AB3A76ED395` (`user_id`),
  CONSTRAINT `FK_CF080AB3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tokens`
--

LOCK TABLES `user_tokens` WRITE;
/*!40000 ALTER TABLE `user_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `phone_number` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `address` longtext DEFAULT NULL,
  `roles` varchar(255) DEFAULT NULL,
  `is_deleted` tinyint(1) NOT NULL,
  `used_promo_codes` varchar(255) DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `is_verified_email` tinyint(1) NOT NULL,
  `can_reviews` longtext DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (32,'khoa123','khoa.nguyenngkoaz2207@hcmut.edu.vn','$2y$10$3zW6aTCBrRF4DC0Y1p3gSuEuBXjRl5nCYdIFsA2Sa8RBzUM.cMn/W','0905770857','Nguyen','Khoa',NULL,'[]',0,NULL,NULL,'2024-12-12 09:30:17',1,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-12 15:59:26
