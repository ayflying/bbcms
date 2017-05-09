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
	
	'dispatch_success_tmpl'  => './jump',
	'dispatch_error_tmpl'    => './jump',
	
	
];