/*
SQLyog Community v10.3 
MySQL - 5.5.9 : Database - perkleit
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`perkleit` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `perkleit`;

/*Table structure for table `map` */

DROP TABLE IF EXISTS `map`;

CREATE TABLE `map` (
  `map_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `max_moves` int(10) unsigned NOT NULL,
  `startX` int(11) NOT NULL DEFAULT '0',
  `startY` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`map_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `map` */

insert  into `map`(`map_id`,`max_moves`,`startX`,`startY`) values (1,25,0,0);

/*Table structure for table `player` */

DROP TABLE IF EXISTS `player`;

CREATE TABLE `player` (
  `player_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_hash` char(43) COLLATE utf8_unicode_ci NOT NULL,
  `map_id` int(10) unsigned NOT NULL,
  `moves_remaining` int(11) NOT NULL,
  `x` int(11) DEFAULT NULL,
  `y` int(11) DEFAULT NULL,
  `last_updated` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`player_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `player` */

/*Table structure for table `tile` */

DROP TABLE IF EXISTS `tile`;

CREATE TABLE `tile` (
  `tile_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `map_id` int(10) unsigned NOT NULL,
  `tile_type_id` int(10) unsigned NOT NULL,
  `x` smallint(5) unsigned NOT NULL,
  `y` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`tile_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tile` */

insert  into `tile`(`tile_id`,`map_id`,`tile_type_id`,`x`,`y`) values (1,1,2,0,0),(2,1,4,0,1),(3,1,4,0,2),(4,1,4,0,3),(5,1,1,0,4),(6,1,1,0,5),(7,1,1,0,6),(8,1,1,0,7),(9,1,1,0,8),(10,1,3,0,9),(11,1,2,1,0),(12,1,1,1,1),(13,1,1,1,2),(14,1,1,1,3),(15,1,4,1,4),(16,1,1,1,5),(17,1,4,1,6),(18,1,4,1,7),(19,1,1,1,8),(20,1,3,1,9),(21,1,2,2,0),(22,1,4,2,1),(23,1,4,2,2),(24,1,1,2,3),(25,1,4,2,4),(26,1,1,2,5),(27,1,4,2,6),(28,1,1,2,7),(29,1,1,2,8),(30,1,3,2,9),(31,1,2,3,0),(32,1,1,3,1),(33,1,1,3,2),(34,1,1,3,3),(35,1,4,3,4),(36,1,1,3,5),(37,1,4,3,6),(38,1,1,3,7),(39,1,4,3,8),(40,1,3,3,9),(41,1,2,4,0),(42,1,4,4,1),(43,1,1,4,2),(44,1,4,4,3),(45,1,4,4,4),(46,1,1,4,5),(47,1,1,4,6),(48,1,4,4,7),(49,1,1,4,8),(50,1,3,4,9),(51,1,2,5,0),(52,1,4,5,1),(53,1,1,5,2),(54,1,1,5,3),(55,1,1,5,4),(56,1,1,5,5),(57,1,4,5,6),(58,1,1,5,7),(59,1,4,5,8),(60,1,3,5,9),(61,1,2,6,0),(62,1,1,6,1),(63,1,4,6,2),(64,1,4,6,3),(65,1,1,6,4),(66,1,1,6,5),(67,1,4,6,6),(68,1,1,6,7),(69,1,4,6,8),(70,1,3,6,9),(71,1,2,7,0),(72,1,1,7,1),(73,1,1,7,2),(74,1,1,7,3),(75,1,4,7,4),(76,1,1,7,5),(77,1,1,7,6),(78,1,1,7,7),(79,1,4,7,8),(80,1,3,7,9);

/*Table structure for table `tile_types` */

DROP TABLE IF EXISTS `tile_types`;

CREATE TABLE `tile_types` (
  `tile_type_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `open` tinyint(3) unsigned NOT NULL DEFAULT '1',
  `special` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `booster` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`tile_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `tile_types` */

insert  into `tile_types`(`tile_type_id`,`open`,`special`,`booster`) values (1,1,0,0),(2,1,1,0),(3,1,2,0),(4,0,0,0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
