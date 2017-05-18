INSERT INTO `bb_system_settings` VALUES ('1', 'webid', '1', '网站编号', 'text', '0');
INSERT INTO `bb_system_settings` VALUES ('2', 'webname', '', '网站名称', 'text', '1');
INSERT INTO `bb_system_settings` VALUES ('3', 'weburl', '', '网址地址', 'text', '1');
INSERT INTO `bb_system_settings` VALUES ('4', 'keywords', '', '关键词', 'text', '2');
INSERT INTO `bb_system_settings` VALUES ('5', 'description', '', '描述', 'text', '2');
INSERT INTO `bb_system_settings` VALUES ('6', 'statistics', '', '统计', 'textarea', '2');
INSERT INTO `bb_system_settings` VALUES ('7', 'api_key', '', ' api密钥', 'text', '1');
INSERT INTO `bb_system_settings` VALUES ('8', 'record', '', '备案号', 'text', '1');
INSERT INTO `bb_system_settings` VALUES ('9', 'theme', 'default', '模版主题', 'text', '0');
INSERT INTO `bb_system_settings` VALUES ('10', 'qq', '', 'QQ', 'text', '1');
INSERT INTO `bb_system_settings` VALUES ('11', 'logo', '', '网站上面的图标logo', 'text', '2');
INSERT INTO `bb_system_settings` VALUES ('12', 'Integral1', '积分', '积分1', 'text', '3');
INSERT INTO `bb_system_settings` VALUES ('13', 'Integral2', '钻石', '积分2', 'text', '3');
INSERT INTO `bb_system_settings` VALUES ('14', 'Integral3', '', '积分3', 'text', '3');
INSERT INTO `bb_system_settings` VALUES ('15', 'image_width', '', '图片最大宽度', 'text', '4');
INSERT INTO `bb_system_settings` VALUES ('16', 'image_height', '', '图片最大高度', 'text', '4');
INSERT INTO `bb_system_settings` VALUES ('17', 'pic_width', '', '缩略图宽度', 'text', '4');
INSERT INTO `bb_system_settings` VALUES ('18', 'pic_height', '', '缩略图高度', 'text', '4');
INSERT INTO `bb_system_settings` VALUES ('19', 'version', '1.0.0.20170511', '系统版本', 'text', '0');
INSERT INTO `bb_member_group` VALUES ('1', '管理员', '1', '', '0', '0');
INSERT INTO `bb_member_group` VALUES ('2', '编辑', '1', '', '0', '0');
INSERT INTO `bb_member_group` VALUES ('3', '会员', '1', '', '0', '0');
INSERT INTO `bb_member_group` VALUES ('4', '游客', '1', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('address', '1', '1', '0', '邮寄地址', '', '90', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('affectivestatus', '1', '1', '0', '情感状态', '', '70', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('alipay', '1', '1', '0', '支付宝', '', '77', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('bio', '1', '1', '0', '自我介绍', '', '72', '0', '0', '0', '0', 'textarea', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthcity', '1', '0', '0', '出生地', '', '88', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthcommunity', '1', '0', '0', '出生小区', '', '0', '0', '0', '0', '0', '0', '0', 'select', '0', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthday', '1', '0', '0', '生日', '', '97', '0', '0', '0', '0', 'select', '0', '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthdist', '1', '0', '0', '出生县', '出生行政区/县', '0', '0', '0', '0', '0', '0', '0', 'select', '0', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthmonth', '1', '0', '0', '出生月份', '', '0', '0', '0', '0', '0', 'select', '0', '1,2,3,4,5,6,7,8,9,10,11,12', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthprovince', '1', '0', '0', '出生省份', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('birthyear', '1', '0', '0', '出生年份', '', '0', '0', '0', '0', '1', 'select', '0', '1960,1961,1962,1963,1964,1965,1966,1967,1968,1969,1970,1971,1972,1973,1974,1975,1976,1977,1978,1979,1980,1981,1982,1983,1984,1985,1986,1987,1988,1989,1990,1991,1992,1993,1994,1995,1996,1997,1998,1999,2000,2001,2002,2003,2004,2005,2006,2007,2008,2009,2010,2011,2012,2013,2014,2015,2016,2017,2018,2019', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('bloodtype', '1', '1', '0', '血型', '', '78', '0', '0', '0', '0', 'select', '0', '其它,\nA,\nB,\nA,B\n,O', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('company', '1', '0', '0', '公司', '', '84', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('constellation', '1', '1', '0', '星座', '星座(根据生日自动计算)', '96', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('education', '1', '0', '0', '学历', '', '85', '0', '0', '0', '0', 'select', '0', '博士,\n硕士,\n本科,\n专科,\n中学,\n小学,\n其它', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field1', '1', '0', '0', '游戏名字', '游戏里面的名字', '49', '0', '0', '0', '1', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field2', '1', '0', '0', '正在游戏', '正在玩什么游戏？', '50', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field3', '0', '1', '0', '自定义字段3', '', '61', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field4', '1', '0', '0', '特异功能', '示例：飞行、远距传物、时间穿梭、特能吃墨西哥的沙沙酱和玉米片', '50', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field5', '0', '1', '0', '自定义字段5', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field6', '0', '1', '0', '自定义字段6', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field7', '0', '1', '0', '自定义字段7', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('field8', '0', '1', '0', '自定义字段8', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('gender', '1', '0', '0', '性别', '', '98', '1', '0', '0', '1', 'select', '0', '女,男', '', '0', '1');
INSERT INTO `bb_member_user_profile_setting` VALUES ('graduateschool', '1', '0', '0', '毕业学校', '', '86', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('height', '0', '1', '0', '身高', '单位 cm', '65', '0', '0', '0', '0', 'text', '0', '', '/^\\d{1,3}$/', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('icq', '0', '1', '0', 'ICQ', '', '63', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('interest', '1', '0', '0', '兴趣爱好', '', '71', '0', '0', '0', '0', 'textarea', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('lookingfor', '1', '0', '0', '交友目的', '希望在网站找到什么样的朋友', '79', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('msn', '1', '1', '0', 'MSN', '', '75', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('nationality', '0', '0', '0', '国籍', '', '67', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('occupation', '1', '0', '0', '职业', '', '83', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('position', '1', '0', '0', '职位', '', '82', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('qq', '1', '1', '0', 'QQ', '', '76', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('realname', '1', '0', '0', '真实姓名', '', '99', '0', '0', '0', '1', 'text', '0', '', '', '0', '1');
INSERT INTO `bb_member_user_profile_setting` VALUES ('residecity', '1', '0', '0', '居住地', '', '87', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('residecommunity', '1', '0', '0', '居住小区', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('residedist', '1', '0', '0', '居住县', '居住行政区/县', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('resideprovince', '1', '0', '0', '居住省份', '', '0', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('residesuite', '0', '0', '0', '房间', '小区、写字楼门牌号', '66', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('revenue', '1', '0', '0', '年收入', '单位 元', '81', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('site', '1', '0', '0', '个人主页', '', '73', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('taobao', '1', '1', '0', '阿里旺旺', '', '74', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('telephone', '1', '1', '0', '固定电话', '', '94', '0', '0', '0', '0', 'text', '0', '', '/^((\\(?\\d{3,4}\\)?)|(\\d{3,4}-)?)\\d{7,8}$/', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('weight', '0', '1', '0', '体重', '单位 kg', '64', '0', '0', '0', '0', 'text', '0', '', '/^\\d{1,3}$/', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('yahoo', '0', '1', '0', 'YAHOO帐号', '', '62', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('zipcode', '1', '1', '0', '邮编', '', '89', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');
INSERT INTO `bb_member_user_profile_setting` VALUES ('zodiac', '1', '1', '0', '生肖', '生肖(根据生日自动计算)', '95', '0', '0', '0', '0', 'text', '0', '', '', '0', '0');