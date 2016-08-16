<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

return [
	//////////////////////
	/* 系统配置 */
	/////////////////////
	// 应用调试模式
	'app_debug' => true,
	'url_route_on' => true,
	'default_module' => 'portal',
	// 开启应用Trace调试
	/***///'app_trace' =>  true,
	// 扩展函数文件
	'extra_file_list'        => [ APP_PATH . 'helper.php', THINK_PATH . 'helper.php'],
	// 注册的根命名空间
	'root_namespace' => [
		'addons' => 'addons',
		'lib' => '../application/common/library',
		//'my'  => '../application/extend/my/',
		//'org' => '../application/extend/org/',
		
	],
	/* 网址配置 */
	// pathinfo分隔符
	'pathinfo_depr'          => '-',
	
	///////////////////////////
	/* 模板配置 */
	//////////////////////////
	'template'               => [
		// 模板路径
		'view_path' => VIEW_PATH,
		//'view_path' => './template/default/',
		
		// 预先加载的标签库
		'taglib_pre_load'    =>    'app\\common\\taglib\\Bb',
	],
	 // 视图输出字符串内容替换
	'view_replace_str'=>[
		'__Tpl__' => VIEW_PATH,
		'__PUBLIC__' => './public/',
	],
	
	
	'extra_config_list' => ['database','route','ueditor'],
	//'extra_config_list' => [__DIR__.'/config/db.php'],
];