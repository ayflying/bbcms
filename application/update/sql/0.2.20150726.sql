ALTER TABLE bb_portal_article change wtime create_time int(11);
			ALTER TABLE bb_portal_article change utime update_time int(11);
			ALTER TABLE bb_member_user change rtime create_time int(11);
			ALTER TABLE bb_member_user change utime update_time int(11);
			ALTER TABLE bb_member_user change rip create_ip char(50);
			ALTER TABLE bb_member_user change lip update_ip char(50);
			ALTER TABLE bb_member_user add gid int(11);
			CREATE TABLE `bb_member_group` (
				`gid` int(11) NOT NULL AUTO_INCREMENT,
				`name` char(50) DEFAULT NULL,
				`status` int(1) NOT NULL,
				`create_time` int(11) DEFAULT NULL,
				`update_time` int(11) DEFAULT NULL,
				PRIMARY KEY (`gid`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8;