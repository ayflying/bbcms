INSERT INTO `bb_system_settings` VALUES ('19','version','0.3.20161221','系统版本','text','0');
ALTER TABLE `bb_member_action` CHANGE `value` `module` varchar(255);
ALTER TABLE `bb_member_action` add `controller` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci after `module`;
ALTER TABLE `bb_member_action` add `action` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci after `controller`;