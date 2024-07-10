-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.25-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             12.4.0.6659
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for pastebin
CREATE DATABASE IF NOT EXISTS `pastebin` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci */;
USE `pastebin`;

-- Dumping structure for table pastebin.pastes
CREATE TABLE IF NOT EXISTS `pastes` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `content` mediumtext COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `visibility` enum('private','public') COLLATE utf8mb4_unicode_520_ci DEFAULT 'public',
  `status` enum('unpublished','published') COLLATE utf8mb4_unicode_520_ci DEFAULT 'published',
  `createdBy` char(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `createdAddress` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `createdDateTime` varchar(16) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `modifiedBy` char(20) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `modifiedAddress` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `modifiedDateTime` varchar(16) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Dumping data for table pastebin.pastes: ~2 rows (approximately)
INSERT INTO `pastes` (`id`, `title`, `content`, `visibility`, `status`, `createdBy`, `createdAddress`, `createdDateTime`, `modifiedBy`, `modifiedAddress`, `modifiedDateTime`) VALUES
	(1, 'a', 'a', 'private', 'published', '1', '::1', '06-23-2024 11:27', '1', '::1', '06-25-2024 16:27'),
	(2, 'testere', 'testere', 'public', 'published', 'anonymous', '::1', '06-23-2024 11:48', '1', '::1', '06-25-2024 16:29');

-- Dumping structure for table pastebin.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `emailKey` char(30) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `emailVerified` enum('yes','no') COLLATE utf8mb4_unicode_520_ci DEFAULT 'no',
  `password` char(60) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `permissions` text COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `loginAddress` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `loginDateTime` varchar(16) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `registeredAddress` varchar(15) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `registeredDateTime` varchar(16) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- Dumping data for table pastebin.users: ~1 rows (approximately)
INSERT INTO `users` (`id`, `username`, `email`, `emailKey`, `emailVerified`, `password`, `permissions`, `loginAddress`, `loginDateTime`, `registeredAddress`, `registeredDateTime`) VALUES
	(1, 'a', 'a@gmail.com', 'RKi2VRBMy3TY1XG7eLUuWdlbVBmmZ', 'yes', '$2y$10$0ZaXry2UrSsL41v.NRef.OlVUzrVIfmImPcajdKSrsAT6V/pYaiAK', '0,1,2,3,4,5,6,7,8,9,10,11,12', '::1', '06-23-2024 11:49', '::1', '06-14-2024 00:31');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
