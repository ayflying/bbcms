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

/*
Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');
*/
return [
    'update/download' => 'update/update/download',
    'update/list' => 'update/index/index',
    'update/sql' => 'update/sql/index',
    
	'tid/[:tid]' => ['@portal/lists/index',['method' => 'get'],['tid'=>'\d+']],
	'aid/[:aid]' => ['@portal/article/index',['method' => 'get'],['aid'=>'\d+']],
    'add/:tid' => ['@portal/post/add',['method' => 'post|get'],['tid'=>'\d+']],
	'edit/:aid' => ['@portal/post/edit',['method' => 'post|get'],['aid'=>'\d+']],
    
    's' => '@portal/search/index',
    
    'uid/:uid' => '@member/index/index',
    
    'addons/:name' => '@addon/index/index',
];

