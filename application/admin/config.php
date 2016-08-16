<?php
return [
	'template' => [
		// 模板路径
		'view_path'    => null,
	],
	
	
	
	// 视图输出字符串内容替换
	'view_replace_str'=>[
		'__Tpl__' => './application/admin/view',
		'__PUBLIC__' => './public/',
	],
	
	'dispatch_success_tmpl'  => '/jump',
	'dispatch_error_tmpl'    => '/jump',
	
	
	'menu' => [
		"系统首页" => array(
			"系统设置" => "System/index",
			"网站模板" => 'System/theme',
			"更新缓存" => 'System/cache',
			"在线升级" => "Update/index",
			"数据库" => "sql/index?type=import",
		),
		"内容管理" => array(
			"栏目管理" => 'Portal/Menu',
			"内容列表" => 'Portal/article_list',
			"回收站" => 'Portal/recycle',
			'功能模型' => 'Mod/mod',
		),
		"用户管理" => array(
			"用户列表" => "Member/user",
			"用户组" => 'Member/group',
			"权限" => 'Member/action',
		),
		"网站运营" => array(
			"广告设置" => 'ad/ad_list',
			"友情链接" => 'Flink/flink_list',
			"插件" => 'Addon/Addon_list',
		),
		"微信" => array(
			"用户回复" => "Wechat/response",
			"自定义菜单" => "Wechat/menu",
			"" => "",
		),
		
	],
];