<?php
//配置文件
return [

    'dir' => [
		'./thinkphp',
        './vendor',
		'./public/cloud',
		'./template/default',
		APP_PATH.'admin',
		APP_PATH.'portal',
		APP_PATH.'member',
		APP_PATH.'addon',
        APP_PATH.'update',
		APP_PATH.'common',
        APP_PATH.'extra',
        APP_PATH.'install',
	],
	'file' => [
        './update.php',
		'./robots.txt',
		'./.htaccess',
		'./public/install.lock',
        './README.md',
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
    
    
	'app_trace' =>  false,  //关闭网页Trace功能
    
];