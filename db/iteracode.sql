-- Adminer 4.2.5 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `ev_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `c` int(11) NOT NULL,
  `cpp` int(11) NOT NULL,
  `java` int(11) NOT NULL,
  `python` int(11) NOT NULL,
  PRIMARY KEY (`ev_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `event` (`ev_id`, `title`, `start`, `end`, `c`, `cpp`, `java`, `python`) VALUES
(7,	'Lomba Programming TIK ITERA',	1487622000,	1487708400,	1,	1,	1,	1),
(8,	'Lomba Programming',	1500811200,	1500818400,	1,	0,	0,	0);

DROP TABLE IF EXISTS `prefs`;
CREATE TABLE `prefs` (
  `name` varchar(30) NOT NULL,
  `start` int(11) NOT NULL,
  `end` int(11) NOT NULL,
  `c` int(11) NOT NULL,
  `cpp` int(11) NOT NULL,
  `java` int(11) NOT NULL,
  `python` int(11) NOT NULL,
  `formula` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `problems`;
CREATE TABLE `problems` (
  `sl` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `text` text NOT NULL,
  `time` int(11) NOT NULL DEFAULT '3000',
  `points` int(11) NOT NULL,
  `addtime` bigint(20) NOT NULL,
  `id_ev` int(11) NOT NULL,
  `duedate` bigint(20) NOT NULL,
  PRIMARY KEY (`sl`),
  KEY `id_ev` (`id_ev`),
  CONSTRAINT `problems_ibfk_1` FOREIGN KEY (`id_ev`) REFERENCES `event` (`ev_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `problems` (`sl`, `name`, `text`, `time`, `points`, `addtime`, `id_ev`, `duedate`) VALUES
(1,	'Penjumlahan 2 Bilangan',	'<span class=\"label label-success\">Task</span>\r\n<br>Program Menerima Input berupa 2 nilai dan menjumlahkan kedua nilai tersebut\r\n<br>\r\n<br><span class=\"label label-success\">Input</span>\r\n<br>1 2\r\n<br><span class=\"label label-success\">Output</span>\r\n<br>3\r\n<br>\r\n<br><span class=\"label label-success\">Input</span>\r\n<br>4 2\r\n<br><span class=\"label label-success\">Output</span>\r\n<br>6\r\n<br>	',	100,	100,	1491371002,	7,	0),
(3,	'Pengurangan',	'<span class=\"label label-success\">Task</span>\r\n<br>Pengurangan 2 buah bilangan\r\n<br>\r\n<br><span class=\"label label-success\">Input</span>\r\n<br>21\r\n<br>\r\n<br><span class=\"label label-success\">Output</span>\r\n<br>1\r\n<br>\r\n<br><span class=\"label label-success\">Input</span>\r\n<br>34\r\n<br>\r\n<br><span class=\"label label-success\">Output</span>\r\n<br>-1',	0,	100,	1498472740,	7,	0);

DROP TABLE IF EXISTS `solve`;
CREATE TABLE `solve` (
  `sl` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `attempts` int(11) NOT NULL DEFAULT '1',
  `soln` text NOT NULL,
  `lang` varchar(20) NOT NULL,
  `score` float NOT NULL,
  `grader` text,
  `time` bigint(20) NOT NULL,
  PRIMARY KEY (`sl`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DROP TABLE IF EXISTS `testcase`;
CREATE TABLE `testcase` (
  `sl` int(11) NOT NULL,
  `input` text,
  `output` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `testcase` (`sl`, `input`, `output`) VALUES
(1,	'1 2',	'3'),
(1,	'2 3',	'5'),
(1,	'6 7',	'13'),
(3,	'',	'');

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `sl` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(25) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `hash` varchar(80) NOT NULL,
  `email` varchar(100) NOT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `score` float NOT NULL,
  `id_ev` int(11) NOT NULL,
  PRIMARY KEY (`sl`),
  KEY `id_ev` (`id_ev`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_ev`) REFERENCES `event` (`ev_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `users` (`sl`, `username`, `salt`, `hash`, `email`, `status`, `score`, `id_ev`) VALUES
(1,	'admin',	'72wme',	'72JUnaLAjjGns',	'luky.lucky24@gmail.com',	1,	60,	7),
(3,	'user',	'f8qok',	'f8B9xJFHBWe5E',	'user@gmail.com',	1,	0,	7);

-- 2017-08-29 02:40:54
