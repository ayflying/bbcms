<?php
//配置文件
return [
	///////////////////////////
	/* 模板配置 */
	//////////////////////////
	'template'               => [
		// 模板路径
		'view_path' => './addons',
		//'view_path' => './template/default/',
		
		// 预先加载的标签库
		'taglib_pre_load'    =>    'app\\common\\taglib\\Bb',
	],
	 // 视图输出字符串内容替换
	'view_replace_str'=>[
		'__Tpl__' => './addons',
		'__PUBLIC__' => './public/',
	],
	
];