<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2015 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

if(version_compare(PHP_VERSION,'5.4.0','<'))  die('require PHP > 5.4.0 !');
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
// 开启调试模式
define('APP_DEBUG', true);
define('EXTEND_PATH', APP_PATH.'common/library');
//全局静态变量
include 'config.php';
// 加载框架引导文件
require __DIR__ . '/Thinkphp/start.php';


$build = [
	'__file__'	=>	['helper.php'],
	
	'common' => [
		'controller' => ['Common'],
	],
	'admin' => [
		'controller' => ['Common','Index','Login','System','Member','Portal','Mod','Addon'],
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
	'addon' => [],
	
];

\think\Build::run($build);
//\think\Build::run($build,'application',true);
//\think\Build::module('member');