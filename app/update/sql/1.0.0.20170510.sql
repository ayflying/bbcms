DROP TABLE IF EXISTS `bb_addon_hooks`;
DROP TABLE IF EXISTS `bb_addon`;
CREATE TABLE `bb_addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `identifier` varchar(255) NOT NULL,
  `directory` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `settings` text,
  `status` smallint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;