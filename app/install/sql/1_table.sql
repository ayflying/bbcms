SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for bb_addon
-- ----------------------------
DROP TABLE IF EXISTS `bb_addon`;
CREATE TABLE `bb_addon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `copyright` varchar(255) DEFAULT NULL,
  `version` varchar(255) DEFAULT NULL,
  `identifier` varchar(255) NOT NULL DEFAULT '',
  `directory` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `settings` text,
  `status` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- ----------------------------
-- Table structure for bb_member_action
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_action`;
CREATE TABLE `bb_member_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `module` varchar(255) DEFAULT NULL,
  `controller` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_group
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_group`;
CREATE TABLE `bb_member_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `value` varchar(255) NOT NULL DEFAULT '',
  `create_time` int(11) NOT NULL DEFAULT '0',
  `update_time` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`gid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_message
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_message`;
CREATE TABLE `bb_member_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `from` int(11) DEFAULT NULL,
  `text` text,
  `read` int(1) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_user
-- ----------------------------

DROP TABLE IF EXISTS `bb_member_user`;
CREATE TABLE `bb_member_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL  DEFAULT '0',
  `headimgurl` varchar(255) DEFAULT NULL,
  `email` varchar(50) NOT NULL DEFAULT '',
  `username` varchar(50) NOT NULL DEFAULT '',
  `password` varchar(50) NOT NULL DEFAULT '',
  `guid` varchar(50) NOT NULL DEFAULT '',
  `money` double(255,2) NOT NULL DEFAULT '0.00',
  `idcard` varchar(255) DEFAULT NULL,
  `mobile` double(13,0) DEFAULT NULL,
  `Integral3` int(11) DEFAULT NULL,
  `duoshuo` int(11) DEFAULT NULL,
  `leave` int(3) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_ip` varchar(50) DEFAULT NULL,
  `update_ip` varchar(50) DEFAULT NULL,
  `status` smallint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_user_profile`;
CREATE TABLE `bb_member_user_profile` (
  `uid` mediumint(8) unsigned NOT NULL,
  `realname` varchar(255) NOT NULL DEFAULT '',
  `gender` varchar(3) DEFAULT NULL,
  `birthyear` smallint(6) unsigned NOT NULL DEFAULT '0',
  `birthmonth` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `birthday` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `constellation` varchar(255) NOT NULL DEFAULT '',
  `zodiac` varchar(255) NOT NULL DEFAULT '',
  `telephone` varchar(255) NOT NULL DEFAULT '',
  `address` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(255) NOT NULL DEFAULT '',
  `nationality` varchar(255) NOT NULL DEFAULT '',
  `birthprovince` varchar(255) NOT NULL DEFAULT '',
  `birthcity` varchar(255) NOT NULL DEFAULT '',
  `resideprovince` varchar(255) NOT NULL DEFAULT '',
  `residecity` varchar(255) NOT NULL DEFAULT '',
  `residedist` varchar(20) NOT NULL DEFAULT '',
  `residecommunity` varchar(255) NOT NULL DEFAULT '',
  `residesuite` varchar(255) NOT NULL DEFAULT '',
  `graduateschool` varchar(255) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `education` varchar(255) NOT NULL DEFAULT '',
  `occupation` varchar(255) NOT NULL DEFAULT '',
  `position` varchar(255) NOT NULL DEFAULT '',
  `revenue` varchar(255) NOT NULL DEFAULT '',
  `affectivestatus` varchar(255) NOT NULL DEFAULT '',
  `lookingfor` varchar(255) NOT NULL DEFAULT '',
  `bloodtype` varchar(255) NOT NULL DEFAULT '',
  `height` varchar(255) NOT NULL DEFAULT '',
  `weight` varchar(255) NOT NULL DEFAULT '',
  `alipay` varchar(255) NOT NULL DEFAULT '',
  `icq` varchar(255) NOT NULL DEFAULT '',
  `qq` varchar(255) NOT NULL DEFAULT '',
  `yahoo` varchar(255) NOT NULL DEFAULT '',
  `msn` varchar(255) NOT NULL DEFAULT '',
  `taobao` varchar(255) NOT NULL DEFAULT '',
  `site` varchar(255) NOT NULL DEFAULT '',
  `bio` text,
  `interest` text,
  `field1` text,
  `field2` text,
  `field3` text,
  `field4` text,
  `field5` text,
  `field6` text,
  `field7` text,
  `field8` text,
  `birthdist` varchar(20) NOT NULL DEFAULT '',
  `birthcommunity` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_user_profile_setting
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_user_profile_setting`;
CREATE TABLE `bb_member_user_profile_setting` (
  `fieldid` varchar(255) NOT NULL DEFAULT '',
  `available` tinyint(1) NOT NULL DEFAULT '0',
  `invisible` tinyint(1) NOT NULL DEFAULT '0',
  `needverify` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `displayorder` smallint(6) unsigned NOT NULL DEFAULT '0',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `unchangeable` tinyint(1) NOT NULL DEFAULT '0',
  `showinthread` tinyint(1) NOT NULL DEFAULT '0',
  `allowsearch` tinyint(1) NOT NULL DEFAULT '0',
  `formtype` varchar(255) NOT NULL,
  `size` smallint(6) unsigned NOT NULL DEFAULT '0',
  `choices` text,
  `validate` text,
  `showincard` tinyint(1) NOT NULL DEFAULT '0',
  `showinregister` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_operate_ad
-- ----------------------------
DROP TABLE IF EXISTS `bb_operate_ad`;
CREATE TABLE `bb_operate_ad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` text,
  `start_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `status` smallint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_operate_flink
-- ----------------------------
DROP TABLE IF EXISTS `bb_operate_flink`;
CREATE TABLE `bb_operate_flink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `status` smallint(1) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_addonarticle
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_addonarticle`;
CREATE TABLE `bb_portal_addonarticle` (
  `aid` int(11) NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_article
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_article`;
CREATE TABLE `bb_portal_article` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `click` int(11) NOT NULL DEFAULT '0',
  `likes` int(11) NOT NULL DEFAULT '0',
  `ulikes` int(11) NOT NULL DEFAULT '0',
  `collect` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `mod` int(11) NOT NULL DEFAULT '0',
  `litpic` varchar(255) DEFAULT NULL,
  `thumb` varchar(2048) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` smallint(1) NOT NULL DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_attachment
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_attachment`;
CREATE TABLE `bb_portal_attachment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `aid` int(11) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `original` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `size` double DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_collect
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_collect`;
CREATE TABLE `bb_portal_collect` (
  `id` int(11) NOT NULL,
  `uid` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_comment
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_comment`;
CREATE TABLE `bb_portal_comment` (
  `cid` int(11) NOT NULL,
  `aid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `content` text,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_favorite
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_favorite`;
CREATE TABLE `bb_portal_favorite` (
  `id` int(11) NOT NULL,
  `aid` int(11) DEFAULT NULL,
  `uid` int(11) DEFAULT NULL,
  `create_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_menu
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_menu`;
CREATE TABLE `bb_portal_menu` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `type` int(11) NOT NULL DEFAULT '1',
  `weight` int(11) NOT NULL DEFAULT '0',
  `pid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) DEFAULT NULL,
  `mod` int(11) NOT NULL DEFAULT '0',
  `jump` varchar(255) DEFAULT NULL,
  `template_article` varchar(50) DEFAULT NULL,
  `template_list` varchar(50) DEFAULT NULL,
  `template_add` varchar(50) DEFAULT NULL,
  `template_edit` varchar(50) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_mod
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_mod`;
CREATE TABLE `bb_portal_mod` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `data` text,
  `table` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_system_settings
-- ----------------------------
DROP TABLE IF EXISTS `bb_system_settings`;
CREATE TABLE `bb_system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `value` text,
  `title` varchar(50) DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `class` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

