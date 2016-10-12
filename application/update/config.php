<?php
//配置文件
return [

    'dir' => [
		//'./ThinkPHP/Library/Think/Template/TagLib',
		'./thinkphp',
        './vendor',
		'./public/cloud',
		'./template/default',
		APP_PATH.'admin',
		APP_PATH.'portal',
		APP_PATH.'member',
		APP_PATH.'addon',
        APP_PATH.'install',
        APP_PATH.'update',
		APP_PATH.'common',	
	],
	'file' => [
		'./robots.txt',
		'./.htaccess',
		'./public/install.lock',
        APP_PATH.'config.php',
        APP_PATH.'helper.php',
        APP_PATH.'route.php',
        APP_PATH.'common.php',
        
		//'./Public/logo.png',
		//'./Uploads/index.html',
		//'./Uploads/litpic/index.html',
	],
	
	'key' => [
		'ay',
		'anyang',
	],
    
    
	'cache' => [
        'prefix' => 'update',
        'expire' => 10,
    ],
	'app_trace' =>  false,  //关闭网页Trace功能
    
];