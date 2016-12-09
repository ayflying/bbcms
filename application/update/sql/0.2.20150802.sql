ALTER TABLE `bb_portal_article` add `click` int(11) DEFAULT '0' after typeid;
		ALTER TABLE `bb_portal_article` change `del` `status` smallint(1);
		update `bb_portal_article` set `status` = '-1' where `status` = '1';
		update `bb_portal_article` set `status` = '1' where `status` = '0';
		
		
		CREATE TABLE `bb_member_action` (
			`gid` int(11) NOT NULL AUTO_INCREMENT,
			`name` char(50) DEFAULT NULL,
			`action` char(50) DEFAULT NULL,
			`status` smallint(1) default null,
			PRIMARY KEY (`gid`)
		)ENGINE=MyISAM DEFAULT CHARSET=utf8;