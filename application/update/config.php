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
    
    
	'cache' => [
        //缓存前缀
        'prefix' => 'update',
        // 缓存类型为File
        'type'   => 'File', 
         // 缓存有效期为永久有效
        'expire' => 600,
         // 指定缓存目录
        //'path'   => APP_PATH . 'update/data/',
    ],
	'app_trace' =>  false,  //关闭网页Trace功能
    
];