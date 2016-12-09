CREATE TABLE `bb_system_ad` (
		`id` int(11) NOT NULL AUTO_INCREMENT,
		`uid` int(11) NOT NULL,
		`name` varchar(255) DEFAULT NULL,
		`value` text,
		`start_time` int(11) DEFAULT NULL,
		`end_time` int(11) DEFAULT NULL,
		`status` smallint(1) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;