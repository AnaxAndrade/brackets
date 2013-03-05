# ************************************************************
# Sequel Pro SQL dump
# Version 4004
#
# http://www.sequelpro.com/
# http://code.google.com/p/sequel-pro/
#
# Host: localhost (MySQL 5.5.25)
# Database: brackets
# Generation Time: 2013-03-05 06:47:13 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table bracket_match
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bracket_match`;

CREATE TABLE `bracket_match` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bracket_id` int(10) unsigned NOT NULL,
  `match_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bracket_match_bracket_id_foreign` (`bracket_id`),
  KEY `bracket_match_match_id_foreign` (`match_id`),
  CONSTRAINT `bracket_match_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bracket_match_bracket_id_foreign` FOREIGN KEY (`bracket_id`) REFERENCES `brackets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bracket_match` WRITE;
/*!40000 ALTER TABLE `bracket_match` DISABLE KEYS */;

INSERT INTO `bracket_match` (`id`, `bracket_id`, `match_id`, `created_at`, `updated_at`)
VALUES
	(36,1,36,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(37,1,37,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(38,1,38,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(39,1,39,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(40,1,40,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(41,1,41,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(42,1,42,'2013-03-05 05:25:03','2013-03-05 05:25:03');

/*!40000 ALTER TABLE `bracket_match` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bracket_player
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bracket_player`;

CREATE TABLE `bracket_player` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bracket_id` int(10) unsigned NOT NULL,
  `player_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `bracket_player_bracket_id_foreign` (`bracket_id`),
  KEY `bracket_player_player_id_foreign` (`player_id`),
  CONSTRAINT `bracket_player_bracket_id_foreign` FOREIGN KEY (`bracket_id`) REFERENCES `brackets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `bracket_player_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `bracket_player` WRITE;
/*!40000 ALTER TABLE `bracket_player` DISABLE KEYS */;

INSERT INTO `bracket_player` (`id`, `bracket_id`, `player_id`, `created_at`, `updated_at`)
VALUES
	(1,1,1,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(2,1,2,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(3,1,3,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(4,1,4,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(5,1,5,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(6,1,6,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(7,1,7,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(8,1,8,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(9,1,9,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(10,1,10,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(11,1,11,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(12,1,12,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(13,1,13,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(14,1,14,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(15,1,15,'2013-03-04 21:08:59','2013-03-04 21:08:59');

/*!40000 ALTER TABLE `bracket_player` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table brackets
# ------------------------------------------------------------

DROP TABLE IF EXISTS `brackets`;

CREATE TABLE `brackets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `players_per_team` int(11) NOT NULL,
  `losses` int(11) NOT NULL,
  `current_round` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `winning_team_id` int(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `brackets` WRITE;
/*!40000 ALTER TABLE `brackets` DISABLE KEYS */;

INSERT INTO `brackets` (`id`, `name`, `players_per_team`, `losses`, `current_round`, `created_at`, `updated_at`, `winning_team_id`)
VALUES
	(1,'This is the name',2,1,16,'2013-03-04 21:08:59','2013-03-05 06:42:34',NULL);

/*!40000 ALTER TABLE `brackets` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table laravel_migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `laravel_migrations`;

CREATE TABLE `laravel_migrations` (
  `bundle` varchar(50) NOT NULL,
  `name` varchar(200) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`bundle`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `laravel_migrations` WRITE;
/*!40000 ALTER TABLE `laravel_migrations` DISABLE KEYS */;

INSERT INTO `laravel_migrations` (`bundle`, `name`, `batch`)
VALUES
	('application','2013_03_04_195108_create_bracket',1);

/*!40000 ALTER TABLE `laravel_migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table match_team
# ------------------------------------------------------------

DROP TABLE IF EXISTS `match_team`;

CREATE TABLE `match_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `match_id` int(10) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `match_team_match_id_foreign` (`match_id`),
  KEY `match_team_team_id_foreign` (`team_id`),
  CONSTRAINT `match_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `match_team_match_id_foreign` FOREIGN KEY (`match_id`) REFERENCES `matches` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `match_team` WRITE;
/*!40000 ALTER TABLE `match_team` DISABLE KEYS */;

INSERT INTO `match_team` (`id`, `match_id`, `team_id`, `created_at`, `updated_at`)
VALUES
	(41,36,97,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(42,36,98,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(43,37,99,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(44,37,100,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(45,38,101,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(46,38,102,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(47,39,103,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(48,39,104,'2013-03-05 05:25:03','2013-03-05 05:25:03');

/*!40000 ALTER TABLE `match_team` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table matches
# ------------------------------------------------------------

DROP TABLE IF EXISTS `matches`;

CREATE TABLE `matches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(200) NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `round_id` int(10) unsigned DEFAULT NULL,
  `winning_team_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `matches_round_id_foreign` (`round_id`),
  KEY `matches_winning_team_id_foreign` (`winning_team_id`),
  CONSTRAINT `matches_winning_team_id_foreign` FOREIGN KEY (`winning_team_id`) REFERENCES `teams` (`id`) ON DELETE SET NULL,
  CONSTRAINT `matches_round_id_foreign` FOREIGN KEY (`round_id`) REFERENCES `rounds` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `matches` WRITE;
/*!40000 ALTER TABLE `matches` DISABLE KEYS */;

INSERT INTO `matches` (`id`, `status`, `completed_at`, `round_id`, `winning_team_id`, `created_at`, `updated_at`)
VALUES
	(36,'Complete',NULL,16,NULL,'2013-03-05 05:25:03','2013-03-05 06:32:03'),
	(37,'Complete',NULL,16,NULL,'2013-03-05 05:25:03','2013-03-05 06:33:03'),
	(38,'Complete',NULL,16,NULL,'2013-03-05 05:25:03','2013-03-05 06:33:13'),
	(39,'Complete',NULL,16,NULL,'2013-03-05 05:25:03','2013-03-05 06:38:28'),
	(40,'Complete',NULL,17,NULL,'2013-03-05 05:25:03','2013-03-05 06:40:39'),
	(41,'Complete',NULL,17,NULL,'2013-03-05 05:25:03','2013-03-05 06:39:06'),
	(42,'Complete',NULL,18,NULL,'2013-03-05 05:25:03','2013-03-05 06:43:12');

/*!40000 ALTER TABLE `matches` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table player_team
# ------------------------------------------------------------

DROP TABLE IF EXISTS `player_team`;

CREATE TABLE `player_team` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `player_id` int(10) unsigned NOT NULL,
  `team_id` int(10) unsigned NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `player_team_player_id_foreign` (`player_id`),
  KEY `player_team_team_id_foreign` (`team_id`),
  CONSTRAINT `player_team_player_id_foreign` FOREIGN KEY (`player_id`) REFERENCES `players` (`id`) ON DELETE CASCADE,
  CONSTRAINT `player_team_team_id_foreign` FOREIGN KEY (`team_id`) REFERENCES `teams` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `player_team` WRITE;
/*!40000 ALTER TABLE `player_team` DISABLE KEYS */;

INSERT INTO `player_team` (`id`, `player_id`, `team_id`, `created_at`, `updated_at`)
VALUES
	(181,8,97,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(182,2,97,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(183,7,98,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(184,13,98,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(185,10,99,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(186,1,99,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(187,14,100,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(188,4,100,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(189,9,101,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(190,15,101,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(191,5,102,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(192,6,102,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(193,12,103,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(194,3,103,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(195,11,104,'2013-03-05 05:25:03','2013-03-05 05:25:03');

/*!40000 ALTER TABLE `player_team` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table players
# ------------------------------------------------------------

DROP TABLE IF EXISTS `players`;

CREATE TABLE `players` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(200) NOT NULL,
  `last_name` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `players` WRITE;
/*!40000 ALTER TABLE `players` DISABLE KEYS */;

INSERT INTO `players` (`id`, `first_name`, `last_name`, `email`, `created_at`, `updated_at`)
VALUES
	(1,'Fez',NULL,NULL,'2013-03-04 21:08:59','2013-03-05 00:04:12'),
	(2,'Stephen','Hyde',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(3,'Eric','Forman',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(4,'Laura','Pinciotti',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(5,'Redd','Forman',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(6,'Jackie','Berkhart',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(7,'Kitty','Forman',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(8,'Bob','Pinciotti',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(9,'Kelso',NULL,NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(10,'Leo',NULL,NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(11,'Nina',NULL,NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(12,'Laurie',NULL,NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(13,'Midge','Pinciotti',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(14,'Jimmy','Page',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59'),
	(15,'Mila','Kunis',NULL,'2013-03-04 21:08:59','2013-03-04 21:08:59');

/*!40000 ALTER TABLE `players` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table rounds
# ------------------------------------------------------------

DROP TABLE IF EXISTS `rounds`;

CREATE TABLE `rounds` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `index` int(11) NOT NULL,
  `bracket_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `rounds_bracket_id_foreign` (`bracket_id`),
  CONSTRAINT `rounds_bracket_id_foreign` FOREIGN KEY (`bracket_id`) REFERENCES `brackets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `rounds` WRITE;
/*!40000 ALTER TABLE `rounds` DISABLE KEYS */;

INSERT INTO `rounds` (`id`, `name`, `index`, `bracket_id`, `created_at`, `updated_at`)
VALUES
	(16,'First Round',1,1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(17,'Semi Finals',2,1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(18,'Finals',3,1,'2013-03-05 05:25:03','2013-03-05 05:25:03');

/*!40000 ALTER TABLE `rounds` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table teams
# ------------------------------------------------------------

DROP TABLE IF EXISTS `teams`;

CREATE TABLE `teams` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) DEFAULT NULL,
  `bracket_id` int(10) unsigned DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `teams_bracket_id_foreign` (`bracket_id`),
  CONSTRAINT `teams_bracket_id_foreign` FOREIGN KEY (`bracket_id`) REFERENCES `brackets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;

INSERT INTO `teams` (`id`, `name`, `bracket_id`, `created_at`, `updated_at`)
VALUES
	(97,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(98,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:02','2013-03-05 05:25:02'),
	(99,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(100,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(101,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(102,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(103,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03'),
	(104,'March 5, 2013, 5:25 am',1,'2013-03-05 05:25:03','2013-03-05 05:25:03');

/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
