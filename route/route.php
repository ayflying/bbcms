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


Route::rule('update/download','update/update/download');
Route::rule('update/list','update/index/index');
Route::rule('update/sql','update/sql/index');

Route::rule('tid/[:tid]','portal/lists/index','GET');
Route::rule('aid/[:aid]','portal/article/index','GET');
Route::rule('add/:tid','portal/post/add','GET|POST');
Route::rule('edit/:aid','portal/post/edit','GET|POST');

Route::rule('s','portal/search/index');
Route::rule('uid/:uid','member/index/index');
Route::rule('addons/:name','addon/index/index');

Route::pattern([
    'tid' => '\d+',
    'aid'   => '\d+',
]);

/*
return [
    'update/download' => 'update/update/download',
    'update/list' => 'update/index/index',
    'update/sql' => 'update/sql/index',
    
	'tid/[:tid]' => ['portal/lists/index',['method' => 'get'],['tid'=>'\d+']],
	'aid/[:aid]' => ['portal/article/index',['method' => 'get'],['aid'=>'\d+']],
    'add/:tid' => ['portal/post/add',['method' => 'post|get'],['tid'=>'\d+']],
	'edit/:aid' => ['portal/post/edit',['method' => 'post|get'],['aid'=>'\d+']],
    
    's' => 'portal/search/index',
    
    'uid/:uid' => 'member/index/index',
    
    'addons/:name' => 'addon/index/index',
];
*/
