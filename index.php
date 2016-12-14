<?php
// +----------------------------------------------------------------------
// | BBCMS
// +----------------------------------------------------------------------
// | Copyright (c) 2014-2017 http://www.luoe.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author:  ayflying<ay@7cuu.com>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', './application/');
// 开启调试模式
define('EXTEND_PATH', APP_PATH.'common/library');
//全局静态变量
include 'config.php';
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';


$build = [
	'__file__'	=>	['helper.php'],
	
	'common' => [
		'controller' => ['Common'],
	],
	'admin' => [
		'controller' => ['Common','Login','System','Member','Portal','Mod','Addon','Api','Update'],
		'view'       => ['header','footer'],
	],
	'portal'   => [
		'__dir__'    => ['behavior', 'controller', 'model', 'view'],
		'controller' => ['Index','Common','Post', 'Article','Lists','Search'],
		'model'      => ['PortalArticle','PortalAddonarticle','PortalAttachment','PortalMenu'],
	],
	'member' => [
		'controller' => ['Common','Login','User'],
		'model'	=> ['MemberUser','MemberUserProfile'],
		'validate'	=> ['MemberUser'],
	],
    'update' => [
        '__dir__' => ['sql'],
        'controller' => ['Sql', 'Update'],
    ],
    'install' => [
        'controller' => ['Update'],
    ],
	'addon' => [],
	
];

//\think\Build::run($build);
//\think\Build::run($build,'application',true);
//\think\Build::module('member');