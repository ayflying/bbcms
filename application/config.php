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
    'version' => '0.3.5.20161012',
	// +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------
    
	// 应用调试模式
	//'app_debug' => true,
	'url_route_on' => true,
    
	'default_module' => 'portal',
	// 开启应用Trace调试
	//'app_trace' =>  true,
	
    // 扩展函数文件
	'extra_file_list'        => [ APP_PATH . 'helper.php', THINK_PATH . 'helper.php'],
	// 注册的根命名空间
	'root_namespace' => [
		'addons' => 'addons',
		'lib' => '../application/common/library',
		
	],
	/* 网址配置 */
	// pathinfo分隔符
	'pathinfo_depr'          => '-',
    // 默认语言
    'default_lang'           => 'zh-cn',
	// 开启语言切换
    //'lang_switch_on' => true,
    
    
    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log'                    => [
        // 日志记录方式，内置 file socket 支持扩展
        'type'  => 'File',
        // 日志保存目录
        'path'  => LOG_PATH,
        // 日志记录级别
        'level' => ['error','sql'],
    ],
    
    
	// +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    
	'template'               => [
		'tpl_cache'          => false, // 开启模板编译缓存
		'view_path' => VIEW_PATH.DS,	// 模板路径
		'taglib_pre_load'    =>    'app\\common\\taglib\\Bb',	// 预先加载的标签库
	],
	 // 视图输出字符串内容替换
	'view_replace_str'=>[
		'__Tpl__' => VIEW_PATH,
		'__PUBLIC__' => './public/',
	],
	
	
	//'extra_config_list' => ['database','route','ueditor'],
	//'extra_config_list' => [__DIR__.'/config/db.php'],
];