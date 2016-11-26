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
  `identifier` varchar(255) DEFAULT NULL,
  `directory` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `listen` varchar(255) DEFAULT NULL,
  `menu` varchar(255) DEFAULT NULL,
  `status` smallint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_addon_hooks
-- ----------------------------
DROP TABLE IF EXISTS `bb_addon_hooks`;
CREATE TABLE `bb_addon_hooks` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_action
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_action`;
CREATE TABLE `bb_member_action` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` char(50) DEFAULT NULL,
  `value` char(50) DEFAULT NULL,
  `status` smallint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_group
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_group`;
CREATE TABLE `bb_member_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(50) DEFAULT NULL,
  `status` int(1) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_user
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_user`;
CREATE TABLE `bb_member_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `gid` int(11) NOT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `email` char(50) NOT NULL,
  `username` char(50) NOT NULL,
  `password` char(50) NOT NULL,
  `money` double(255,2) NOT NULL,
  `idcard` char(255) DEFAULT NULL,
  `mobile` double(13,0) DEFAULT NULL,
  `Integral3` int(11) DEFAULT NULL,
  `duoshuo` int(11) DEFAULT NULL,
  `leave` int(3) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  `create_ip` char(50) DEFAULT NULL,
  `update_ip` char(50) DEFAULT NULL,
  `status` smallint(2) NOT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_member_user_profile
-- ----------------------------
DROP TABLE IF EXISTS `bb_member_user_profile`;
CREATE TABLE `bb_member_user_profile` (
  `uid` mediumint(8) unsigned NOT NULL,
  `realname` varchar(255) NOT NULL DEFAULT '',
  `gender` char(3) DEFAULT NULL,
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
  `bio` text NOT NULL,
  `interest` text NOT NULL,
  `field1` text NOT NULL,
  `field2` text NOT NULL,
  `field3` text NOT NULL,
  `field4` text NOT NULL,
  `field5` text NOT NULL,
  `field6` text NOT NULL,
  `field7` text NOT NULL,
  `field8` text NOT NULL,
  `birthdist` varchar(20) NOT NULL DEFAULT '',
  `birthcommunity` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
  `choices` text NOT NULL,
  `validate` text NOT NULL,
  `showincard` tinyint(1) NOT NULL DEFAULT '0',
  `showinregister` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`fieldid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_addonarticle
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_addonarticle`;
CREATE TABLE `bb_portal_addonarticle` (
  `aid` int(11) NOT NULL,
  `content` mediumtext,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_article
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_article`;
CREATE TABLE `bb_portal_article` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `tid` int(11) NOT NULL,
  `click` int(11) NOT NULL,
  `likes` int(11) NOT NULL,
  `ulikes` int(11) NOT NULL,
  `collect` int(11) DEFAULT NULL,
  `title` char(50) NOT NULL,
  `mod` int(11) NOT NULL,
  `litpic` char(255) DEFAULT NULL,
  `thumb` varchar(2048) DEFAULT NULL,
  `description` char(255) DEFAULT NULL,
  `status` smallint(1) NOT NULL,
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_portal_menu
-- ----------------------------
DROP TABLE IF EXISTS `bb_portal_menu`;
CREATE TABLE `bb_portal_menu` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `weight` int(11) DEFAULT '0',
  `pid` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `mod` int(11) NOT NULL,
  `jump` varchar(255) DEFAULT NULL,
  `template` char(255) DEFAULT NULL,
  `template2` char(255) DEFAULT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_system_settings
-- ----------------------------
DROP TABLE IF EXISTS `bb_system_settings`;
CREATE TABLE `bb_system_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `value` text,
  `title` varchar(50) DEFAULT NULL,
  `type` char(20) DEFAULT NULL,
  `class` int(5) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_wechat_menu
-- ----------------------------
DROP TABLE IF EXISTS `bb_wechat_menu`;
CREATE TABLE `bb_wechat_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `topid` int(11) DEFAULT NULL,
  `type` char(50) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_wechat_response
-- ----------------------------
DROP TABLE IF EXISTS `bb_wechat_response`;
CREATE TABLE `bb_wechat_response` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `from` varchar(255) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for bb_wechat_user
-- ----------------------------
DROP TABLE IF EXISTS `bb_wechat_user`;
CREATE TABLE `bb_wechat_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `openid` char(50) NOT NULL,
  `subscribe` int(1) DEFAULT NULL,
  `nickname` char(50) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `uname` varchar(255) DEFAULT NULL,
  `pword` varchar(255) DEFAULT NULL,
  `sex` int(1) NOT NULL,
  `tel` char(30) DEFAULT NULL,
  `country` char(50) DEFAULT NULL,
  `province` char(50) DEFAULT NULL,
  `city` char(50) DEFAULT NULL,
  `image1` varchar(255) DEFAULT NULL,
  `image2` varchar(255) DEFAULT NULL,
  `image3` varchar(255) DEFAULT NULL,
  `image4` varchar(255) DEFAULT NULL,
  `tag1` char(50) DEFAULT NULL,
  `tag2` char(50) DEFAULT NULL,
  `tag3` char(50) DEFAULT NULL,
  `tag4` char(50) DEFAULT NULL,
  `tag5` char(50) DEFAULT NULL,
  `tag6` char(50) DEFAULT NULL,
  `latitude` char(255) DEFAULT NULL,
  `longitude` char(255) DEFAULT NULL,
  `precision` char(255) DEFAULT NULL,
  `time` int(11) DEFAULT NULL,
  PRIMARY KEY (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
