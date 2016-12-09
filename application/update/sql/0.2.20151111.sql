ALTER TABLE bb_portal_article add collect int(11) after ulikes;
	CREATE TABLE `bb_portal_collect` (
		`id` int(11) NOT NULL,
		`uid` int(11) DEFAULT NULL,
		`aid` int(11) DEFAULT NULL,
		`title` varchar(255) DEFAULT NULL,
		`type` int(11) DEFAULT NULL,
		`create_time` int(11) DEFAULT NULL,
		PRIMARY KEY (`id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;